<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use Livewire\Component;
use App\Models\City;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class CityComponent extends Component
{
        use WithPagination;

    // --- Propiedades del componente ---
    public Department $department;
    public $search = '';
    public $showEditModal = false;

    // --- Propiedades del formulario ---
    public ?City $editing = null;
    public $city_id = null;
    public $name = '';
    public $cost = 0; // Añadimos la propiedad para el costo de envío

    // El método mount se ejecuta al iniciar el componente.
    // Aquí recibimos el departamento gracias al Route Model Binding.
    public function mount(Department $department)
    {
        $this->department = $department;
    }

    // --- Reglas de validación ---
    protected function rules()
    {
        return [
            // El nombre debe ser único para el departamento actual, ignorando el ID de la ciudad que se edita.
            'name' => [
                'required',
                'min:3',
                Rule::unique('cities')->where('department_id', $this->department->id)->ignore($this->city_id),
            ],
            'cost' => 'required|numeric|min:0', // Añadimos la regla para el costo
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre de la ciudad es obligatorio.',
        'name.min' => 'El nombre debe tener al menos 3 caracteres.',
        'name.unique' => 'Ya existe una ciudad con este nombre en este departamento.',
        'cost.required' => 'El costo de envío es obligatorio.',
        'cost.numeric' => 'El costo debe ser un número.',
    ];

    // --- Métodos de reseteo ---
    public function resetForm()
    {
        $this->reset(['editing', 'city_id', 'name', 'cost']); // Añadimos 'cost' al reseteo
        $this->resetValidation();
    }

    // --- Acciones del CRUD ---
    public function create()
    {
        $this->resetForm();
        $this->showEditModal = true;
    }

    public function edit(City $city)
    {
        $this->resetForm();
        $this->editing = $city;
        $this->city_id = $city->id;
        $this->name = $city->name;
        $this->cost = $city->cost; // Cargamos el costo existente al editar
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editing) {
            // Actualizar ciudad existente
            $this->editing->update([
                'name' => $this->name,
                'cost' => $this->cost
            ]);
        } else {
            // Crear nueva ciudad y asociarla al departamento actual
            $this->department->cities()->create([
                'name' => $this->name,
                'cost' => $this->cost
            ]);
        }

        $this->showEditModal = false;
        $this->resetForm();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => $this->city_id ? 'Ciudad actualizada correctamente.' : 'Ciudad creada correctamente.',
        ]);
    }

    public function confirmDelete(City $city)
    {
        // Con onDelete('cascade') en la migración de 'districts', la base de datos
        // se encargará de eliminar los distritos asociados automáticamente.
        // Ya no es necesario comprobarlo aquí, simplificando el código.
        // Simplemente, mejoramos el mensaje para que el usuario esté advertido.
        $this->dispatch('swal:confirm', [
            'icon' => 'warning',
            'title' => '¿Estás seguro?',
            'text' => "¡No podrás revertir esto! Se eliminará la ciudad y todos sus distritos asociados.",
            'confirmButtonText' => '¡Sí, bórrala!',
            'method' => 'destroyCity',
            'params' => $city->id,
        ]);
    }

    #[On('destroyCity')]
    public function destroyCity($id)
    {
        City::find($id)->delete();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Eliminada!',
            'text' => 'La ciudad ha sido eliminada.',
        ]);
    }

    public function render()
    {
        $cities = $this->department->cities()
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.city-component', compact('cities'))
            ->layout('components.layouts.app.admin');
    }
}
