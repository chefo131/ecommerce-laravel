<?php

namespace App\Livewire;
use App\Models\Size;
use App\Models\Color;

/**
 * @mixin \Livewire\Component
 */
class AddCartItemSize extends AddCartItem
{
    public $size_id = '';
    public $color_id = '';
    public $options = [
        'size_id' => null,
        'size' => null, // Para el nombre de la talla
        'color_id' => null,
        'color' => null, // Para el nombre del color
        'image' => null
        // Puedes añadir 'size_name' y 'color_name' si quieres mostrarlos en el carrito
    ];

    /**
     * Reglas de validación para el componente.
     */
    protected function rules()
    {
        return [
            'size_id' => 'required',
            'color_id' => 'required',
        ];
    }

    protected $messages = [
        'size_id.required' => 'Por favor, selecciona una talla.',
        'color_id.required' => 'Por favor, selecciona un color.',
    ];

    /**
     * Se ejecuta cuando el componente es inicializado.
     * Carga las tallas disponibles para el producto y la imagen principal.
     */
    public function mount()
    {
        parent::mount(); // Llama al mount de la clase padre para cargar la imagen
        // Inicializamos las opciones específicas de este componente
        $this->options['size_id'] = null;
        $this->options['size'] = null;
        $this->options['color_id'] = null;
        $this->options['color'] = null;
    }

    /**
     * Se ejecuta cuando la propiedad $size_id (talla seleccionada) se actualiza.
     * Resetea la selección de color, la cantidad de stock, y las opciones del carrito relacionadas.
     * Carga los colores disponibles para la talla seleccionada.
     * Actualiza la propiedad $quantity con el stock total de la talla seleccionada.
     * @param string|int $value El ID de la talla seleccionada.
     */
    public function updatedSizeId($value)
    {
        $this->color_id = ''; // Resetea la selección de color
        $this->quantity = 0; // Resetea la cantidad
        $this->options['size_id'] = null; // Resetea la talla en las opciones del carrito
        $this->options['size'] = null; // Resetea el nombre de la talla
        $this->options['color_id'] = null;
        $this->options['color'] = null; // Resetea el nombre del color

        $this->qty = 1; // Resetea qty a su valor por defecto

        if (!empty($value)) {
            /** @var \App\Models\Size|null $size */
            $size = Size::find($value);
            if ($size) {
                $this->options['size_id'] = $size->id;
                $this->options['size'] = $size->name; // Guardamos el nombre de la talla
                // Aquí usamos el accesor 'stock' del modelo Size que calcula el total para esa talla
                $this->quantity = $size->stock;
            }
        }
    }

    /**
     * Se ejecuta cuando la propiedad $color_id (color seleccionado) se actualiza.
     * Resetea las opciones de color del carrito y la cantidad de items ($qty).
     * Si se selecciona un color válido para la talla actual, actualiza $quantity con el stock específico de esa combinación.
     * Si se deselecciona el color o no es válido, $quantity muestra el stock total de la talla previamente seleccionada.
     * @param string|int $value El ID del color seleccionado.
     */
    public function updatedColorId($value)
    {
        // No reseteamos quantity a 0 aquí directamente,
        // porque si se deselecciona un color, queremos volver al stock de la talla.
        // Se actualizará más abajo si se encuentra un color específico.
        $this->options['color_id'] = null; // Resetea la opción de color antes de buscar una nueva
        $this->options['color'] = null; // Resetea el nombre del color
        $this->qty = 1; // Resetea qty a su valor por defecto

        if (!empty($value) && !empty($this->size_id)) {
            $selectedSize = Size::find($this->size_id);


            if ($selectedSize) {
                /** @var \App\Models\Color|null $colorOnSize */
                $colorOnSize = $selectedSize->colors()->find($value); // Busca el Color model con ID=$value dentro de los colores de la talla.

                if ($colorOnSize) {
                    if ($colorOnSize->pivot && isset($colorOnSize->pivot->quantity)) {
                        // Usamos qty_available para obtener el stock específico de la combinación talla-color.
                        // Asegúrate que tu función qty_available($productId, $colorId, $sizeId) funciona como esperas.
                        // Alternativamente, podrías usar directamente $colorOnSize->pivot->quantity si es más simple.
                        $this->quantity = qty_available($this->product->id, $colorOnSize->id, $selectedSize->id);
                        $this->options['color_id'] = $colorOnSize->id;
                        $this->options['color'] = $colorOnSize->name; // Guardamos el nombre del color
                    }
                } else {
                    // Si el color no se encuentra o no es válido para la talla,
                    // volvemos a mostrar el stock total de la talla seleccionada.
                    $this->quantity = $selectedSize->stock;
                }
            }
        } elseif (empty($value) && !empty($this->size_id)) {
            // Si se deselecciona el color (value está vacío) pero hay una talla seleccionada,
            // mostramos el stock total de la talla.
            $selectedSize = Size::find($this->size_id);
            if ($selectedSize) {
                $this->quantity = $selectedSize->stock;
            }
        }
    }

    /**
     * Añade el producto configurado (con talla y color) al carrito de compras.
     * Actualiza el stock disponible y resetea las selecciones.
     */
    public function addItem()
    {
        $this->validate();
        
        // Llama al método de la clase padre para añadir al carrito y emitir eventos
        $this->addToCart();

        // Actualizamos la cantidad disponible para el usuario, considerando lo que ya está en el carrito.
        $this->quantity = qty_available($this->product->id, $this->color_id, $this->size_id);

        // Reseteamos las selecciones del usuario. Esto disparará los hooks 'updated...'
        // que se encargarán de limpiar los selects dependientes y la cantidad.
        $this->reset('qty', 'color_id', 'size_id');
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        // Cargamos las colecciones aquí para evitar problemas de estado en Livewire.
        // La vista recibirá las variables $sizes y $colors, por lo que no necesita cambios.
        $sizes = $this->product->sizes;
        $colors = collect(); // Por defecto, una colección vacía

        if ($this->size_id) {
            // Reutilizamos la colección ya cargada para ser más eficientes
            $selectedSize = $sizes->find($this->size_id);
            if ($selectedSize) {
                $colors = $selectedSize->colors;
            }
        }

        return view('livewire.add-cart-item-size', compact('sizes', 'colors'));
    }
}
