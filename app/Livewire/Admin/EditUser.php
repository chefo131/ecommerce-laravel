<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditUser extends Component
{
    public User $user;
    public $roles;
    public $selectedRole;

    public function mount(User $user)
    {
        $this->user = $user;
        // Excluimos el rol 'admin' para que no se pueda asignar desde la UI
        $this->roles = Role::where('name', '!=', 'admin')->get();
        // Obtenemos el ID del primer rol que tiene el usuario
        $this->selectedRole = $this->user->roles->first()->id ?? null;
    }

    public function updateUserRole()
    {
        $this->validate([
            'selectedRole' => 'required|exists:roles,id',
        ]);

        // No permitimos cambiar el rol del superadministrador (ID 1)
        if ($this->user->id === 1) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => '¡Acción no permitida!',
                'text' => 'No se puede cambiar el rol del superadministrador.',
            ]);
            return;
        }

        $role = Role::findById($this->selectedRole);
        // syncRoles es la forma más segura de asignar roles, ya que elimina los anteriores.
        $this->user->syncRoles([$role->name]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Rol actualizado!',
            'text' => "El rol del usuario {$this->user->name} ha sido actualizado a " . ucfirst($role->name) . ".",
        ]);

        // Opcional: si quieres que la página se recargue para confirmar visualmente el cambio.
        // return redirect()->route('admin.users.edit', $this->user);
    }

    public function render()
    {
        return view('livewire.admin.edit-user')
            ->layout('components.layouts.app.admin');
    }
}