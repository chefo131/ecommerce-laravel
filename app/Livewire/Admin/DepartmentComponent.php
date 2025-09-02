<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;

class DepartmentComponent extends Component
{
    use WithPagination;

    // --- Propiedades del componente ---
    public $search = '';
    public $showEditModal = false;

    // --- Propiedades del formulario ---
    public ?Department $editing = null;
    public $department_id = null;
    public $name = '';

    // --- Reglas de validación ---
    protected function rules()
    {
        return [
            // El nombre debe ser único, ignorando el ID del departamento que se edita.
            'name' => [
                'required',
                'min:3',
                Rule::unique('departments')->ignore($this->department_id),
            ],
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre del departamento es obligatorio.',
        'name.min' => 'El nombre debe tener al menos 3 caracteres.',
        'name.unique' => 'Ya existe un departamento con este nombre.',
    ];

    // --- Métodos de reseteo ---
    public function resetForm()
    {
        $this->reset(['editing', 'department_id', 'name']);
        $this->resetValidation();
    }

    // --- Acciones del CRUD ---
    public function create()
    {
        $this->resetForm();
        $this->showEditModal = true;
    }

    public function edit(Department $department)
    {
        $this->resetForm();
        $this->editing = $department;
        $this->department_id = $department->id;
        $this->name = $department->name;
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editing) {
            // Actualizar departamento existente
            $this->editing->update(['name' => $this->name]);
        } else {
            // Crear nuevo departamento
            Department::create(['name' => $this->name]);
        }

        $this->showEditModal = false;
        $this->resetForm();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => $this->department_id ? 'Departamento actualizado correctamente.' : 'Departamento creado correctamente.',
        ]);
    }

    public function confirmDelete($departmentId)
    {
        // Con onDelete('cascade') en la migración de 'cities', la base de datos
        // se encargará de eliminar las ciudades y distritos asociados automáticamente.
        // Simplemente, mejoramos el mensaje para que el usuario esté advertido.
        $this->dispatch('swal:confirm', [
            'icon' => 'warning',
            'title' => '¿Estás seguro?',
            'text' => "¡No podrás revertir esto! Se eliminará el departamento y todas sus ciudades y distritos asociados. Esta acción es irreversible.",
            'confirmButtonText' => '¡Sí, bórralo!',
            'method' => 'destroyDepartment',
            'params' => $departmentId,
        ]);
    }

    #[On('destroyDepartment')]
    public function destroyDepartment($id)
    {
        Department::find($id)->delete();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Eliminado!',
            'text' => 'El departamento ha sido eliminado.',
        ]);
    }

    public function render()
    {
        $departments = Department::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.department-component', compact('departments'))
            ->layout('components.layouts.app.admin');
    }
}