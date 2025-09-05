<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\Department;
use App\Models\District;
use Illuminate\Support\Collection;
use Livewire\Component;
use App\Models\Order;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed; // ¡Importante! Añadimos esto.

/**
 * Componente de Livewire para gestionar el formulario de creación de pedidos.
 *
 * Este componente maneja la lógica para que un usuario pueda finalizar su compra,
 * eligiendo entre recoger en tienda o envío a domicilio. Incluye validación
 * dinámica y desplegables en cascada para la selección de ubicación (provincia,
 *
 * @property int $envio_type Define el método de envío: 1 para recoger en tienda, 2 para envío a domicilio.
 * @property float $shopping_cost Costo asociado al envío.
 * @property \Illuminate\Support\Collection $departments Colección de todas las provincias disponibles.
 * @property \Illuminate\Support\Collection $cities Colección de ciudades, cargada dinámicamente según la provincia.
 * @property \Illuminate\Support\Collection $districts Colección de distritos/códigos postales, cargada dinámicamente.
 * @property int|string $department_id ID de la provincia seleccionada.
 * @property int|string $city_id ID de la ciudad seleccionada.
 * @property int|string $district_id ID del distrito seleccionado.
 * @property ?string $address Dirección de envío.
 * @property ?string $references Referencias para la dirección de envío.
 * @property ?string $contact Nombre de la persona que recibirá el pedido.
 * @property ?string $phone Teléfono de contacto.
 */

class CreateOrder extends Component
{
    // Tipos de datos explícitos para cada propiedad
    public int $envio_type = 1; // 1 = Recoger en tienda, 2 = Envío a domicilio
    public float $shopping_cost = 0;

    // Los IDs pueden ser un entero o un string vacío al inicio
    public int|string $department_id = "";
    public int|string $city_id = "";
    public int|string $district_id = "";

    // Estas propiedades pueden ser null al principio, por eso el '?'
    public ?string $address = null;
    public ?string $references = null;
    public ?string $contact = null;
    public ?string $phone = null;

    protected $validationAttributes = [
        'department_id' => 'Provincia',
        'city_id' => 'Ciudad',
        'district_id' => 'Código postal',
        'address' => 'Dirección de envío',
        'phone' => 'Teléfono de contacto',
        'contact' => 'Nombre de contacto',
        'references' => 'Referencia de envío'
    ];

    protected function rules()
    {
        $rules = [
            'contact' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'envio_type' => 'required|in:1,2',
        ];

        if ($this->envio_type == 2) {
            $rules['department_id'] = 'required|exists:departments,id';
            $rules['city_id'] = 'required|exists:cities,id';
            $rules['district_id'] = 'required|exists:districts,id';
            $rules['address'] = 'required|string|max:255';
            $rules['references'] = 'nullable|string|max:255'; // Referencias pueden ser opcionales
        }

        return $rules;
    }

    // --- Propiedades Computadas para eficiencia y limpieza ---
    // Livewire las cachea para no repetir consultas a la BD.

    #[Computed]
    public function departments(): Collection
    {
        return Department::all();
    }

    #[Computed]
    public function cities(): Collection
    {
        if ($this->department_id) {
            return City::where('department_id', $this->department_id)->get();
        }
        return collect(); // Devuelve una colección vacía si no hay departamento
    }

    #[Computed]
    public function districts(): Collection
    {
        if ($this->city_id) {
            return District::where('city_id', $this->city_id)->get();
        }
        return collect(); // Devuelve una colección vacía si no hay ciudad
    }

    // --- Hooks de ciclo de vida ---

    public function mount()
    {
        // El mount ahora está limpio. Las propiedades computadas se encargan de la carga de datos.
    }

    public function updatedEnvioType($value)
    {
        $this->resetValidation();

        if ($value == 1) {
            // Al cambiar a "Recoger en tienda", reseteamos todos los campos de dirección.
            $this->reset([
                'department_id',
                'city_id',
                'district_id',
                'address',
                'references',
                'shopping_cost'
            ]);
        }
    }

    public function updatedDepartmentId($value)
    {
        // Al cambiar de provincia, solo necesitamos resetear las selecciones hijas.
        // La carga de ciudades la hace automáticamente la propiedad computada 'cities'.
        $this->reset(['city_id', 'district_id', 'shopping_cost']);
    }

    public function updatedCityId($value)
    {
        // Al cambiar de ciudad, reseteamos el distrito y actualizamos el costo de envío.
        $this->reset('district_id');

        if ($city = $this->cities->find($value)) {
            $this->shopping_cost = $city->cost;
        } else {
            $this->shopping_cost = 0;
        }
    }

    public function create_order()
    {
        $this->validate();

        $subtotal_float = (float) Cart::subtotal(2, '.', '');

        $order = new Order();
        $order->user_id = Auth::id();
        $order->contact = $this->contact;
        $order->phone = $this->phone;
        $order->envio_type = $this->envio_type;
        $order->content = Cart::content();

        if ($this->envio_type == 2) {
            $order->shopping_cost = $this->shopping_cost;
            $order->total = $subtotal_float + $this->shopping_cost;

            // ¡CORREGIDO Y OPTIMIZADO!
            // Usamos las colecciones ya cargadas por las propiedades computadas.
            // Esto evita 3 consultas innecesarias a la base de datos.
            // Ya no necesitamos json_encode() aquí. El modelo Order se encarga de la conversión
            // gracias a la propiedad $casts. Le pasamos el array directamente.
            $order->envio = [
                'department' => $this->departments->find($this->department_id)->name,
                'city' => $this->cities->find($this->city_id)->name,
                'district' => $this->districts->find($this->district_id)->name, // ¡El bug está corregido aquí!
                'address' => $this->address,
                'references' => $this->references,
            ];
        } else {
            $order->shopping_cost = 0;
            $order->total = $subtotal_float;
        }
        $order->save();

        // El stock ahora se descuenta DESPUÉS de que el pago se haya confirmado.
        // Hemos movido esta lógica a PaymentController para evitar dobles descuentos y centralizar el proceso.

        return redirect()->route('orders.payment', $order);
    }

    public function render()
    {
        // El render es simple. La vista accede a las propiedades computadas directamente.
        return view('livewire.create-order');
    }
}
                
