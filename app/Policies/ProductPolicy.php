<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Perform pre-authorization checks.
     * Los administradores pueden realizar cualquier acción.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     * Cualquiera puede ver la lista de productos.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Cualquiera puede ver un producto específico.
     */
    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     * Solo los administradores (gestionado por el método 'before').
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     * Solo los administradores (gestionado por el método 'before').
     */
    public function update(User $user, Product $product): bool
    {
        return false;
    }

}
