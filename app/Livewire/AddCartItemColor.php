<?php

namespace App\Livewire;

/**
 * @mixin \Livewire\Component
 */
class AddCartItemColor extends AddCartItem
{
    /** @var int|string */
    public $color_id = ''; // ID del color seleccionado

    /** @var array */
    public $options = [
        'size_id' => null,
        'color_id' => null, // Asegurarse de que color_id esté en options
        'image' => null,
        'color' => null,
    ];


    /**
     * Se ejecuta cuando el componente es inicializado.
     * Carga los colores asociados al producto.
     */
    public function mount()
    {   
        parent::mount(); // Llama al mount de la clase padre para cargar la imagen
        // Inicializamos las opciones específicas de este componente
        $this->options['color_id'] = null;
        $this->options['color'] = null;
    }

    /**
     * Reglas de validación para el componente.
     */
    protected $rules = [
        'color_id' => 'required',
    ];
    protected $messages = [
        'color_id.required' => 'Por favor, selecciona un color.',
    ];

    /**
     * Añade el producto configurado al carrito de compras.
     *
     * @return void
     */
    public function addItem()
    {
        $this->validate();
        
        // Llama al método de la clase padre para añadir al carrito y emitir eventos
        $this->addToCart();
        
        $this->quantity = qty_available($this->product->id, $this->color_id);
        $this->reset('qty', 'color_id');
    }
    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        // Cargamos los colores aquí para evitar problemas de estado de Livewire.
        // La vista recibirá una variable $colors, por lo que no necesita cambios.
        $colors = $this->product->colors;

        return view('livewire.add-cart-item-color', compact('colors'));
    }

    /**
     * Se ejecuta cuando la propiedad $color_id se actualiza.
     * Busca el stock del color seleccionado y actualiza la propiedad $quantity.
     * Resetea la cantidad de items ($qty) a 1.
     *
     * @param int|string $value El nuevo ID del color seleccionado.
     */
    public function updatedColorId($value)
    {
        // Reseteamos el estado relacionado con el color al inicio para un estado limpio
        $this->quantity = 0;
        $this->options['color_id'] = null;
        $this->options['color'] = null;

        if ($value) {
            try {
                $selectedColor = $this->product->colors()->find($value);

                if ($selectedColor && isset($selectedColor->pivot->quantity)) {
                    $this->quantity = qty_available($this->product->id, $selectedColor->id);
                    $this->options['color_id'] = $selectedColor->id;
                    $this->options['color'] = $selectedColor->name;
                } else {
                    // El stock ya es 0, no hace falta hacer nada más
                }
            } catch (\Exception $e) {
                // En un entorno de producción, podrías loguear el error:
                // Log::error("Error en updatedColorId: " . $e->getMessage());
                // El stock ya es 0, no hace falta hacer nada más
            }
        } else {
            // Si se deselecciona, el stock ya es 0
        }
        $this->qty = 1; // Resetear qty a 1 cuando el color cambia o se deselecciona
    }
}
