<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserComponent extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $users = User::query()
            ->with('roles') // Carga eficiente de la relación con roles
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-component', compact('users'))
            ->layout('components.layouts.app.admin');
    }
}
