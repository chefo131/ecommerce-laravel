<?php

namespace App\Livewire\Admin;

use App\Models\City;
use App\Models\District;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class DistrictComponent extends Component
{
    use WithPagination;

    // --- Propiedades del componente ---
    public City $city;
    public $search = '';
    public $showEditModal = false;

    // --- Propiedades del formulario ---
    public ?District $editing = null;
    public $district_id = null;
    public $name = '';

    // El método mount se ejecuta al iniciar el componente.
    // Aquí recibimos la ciudad gracias al Route Model Binding.
    public function mount(City $city)
    {
        $this->city = $city;
    }

    // --- Reglas de validación ---
    protected function rules()
    {
        return [
            // El nombre debe ser único para la ciudad actual, ignorando el ID del distrito que se edita.
            'name' => [
                'required',
                'min:3',
                Rule::unique('districts')->where('city_id', $this->city->id)->ignore($this->district_id),
            ],
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre del distrito es obligatorio.',
        'name.min' => 'El nombre debe tener al menos 3 caracteres.',
        'name.unique' => 'Ya existe un distrito con este nombre en esta ciudad.',
    ];

    // --- Métodos de reseteo ---
    public function resetForm()
    {
        $this->reset(['editing', 'district_id', 'name']);
        $this->resetValidation();
    }

    // --- Acciones del CRUD ---
    public function create()
    {
        $this->resetForm();
        $this->showEditModal = true;
    }

    public function edit(District $district)
    {
        $this->resetForm();
        $this->editing = $district;
        $this->district_id = $district->id;
        $this->name = $district->name;
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editing) {
            // Actualizar distrito existente
            $this->editing->update(['name' => $this->name]);
        } else {
            // Crear nuevo distrito y asociarlo a la ciudad actual
            $this->city->districts()->create(['name' => $this->name]);
        }

        $this->showEditModal = false;
        $this->resetForm();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => $this->district_id ? 'Distrito actualizado correctamente.' : 'Distrito creado correctamente.',
        ]);
    }

    public function confirmDelete($districtId)
    {
        // Buscamos el distrito por su ID para poder hacer la comprobación
        $district = District::find($districtId);

        // Comprobamos si el distrito tiene órdenes asociadas
        if ($district->orders()->count() > 0) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => '¡Operación denegada!',
                'text' => 'No se puede eliminar el distrito porque tiene órdenes asociadas.',
            ]);
            return;
        }

        $this->dispatch('swal:confirm', [
            'icon' => 'warning',
            'title' => '¿Estás seguro?',
            'text' => "¡No podrás revertir esto!",
            'confirmButtonText' => '¡Sí, bórralo!',
            'method' => 'destroyDistrict',
            'params' => $districtId,
        ]);
    }

    #[On('destroyDistrict')]
    public function destroyDistrict($id)
    {
        District::find($id)->delete();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Eliminado!',
            'text' => 'El distrito ha sido eliminado.',
        ]);
    }

    public function render()
    {
        $districts = $this->city->districts()
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.district-component', compact('districts'))
            ->layout('components.layouts.app.admin');
    }
}