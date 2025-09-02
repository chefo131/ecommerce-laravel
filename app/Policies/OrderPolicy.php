<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Un usuario puede ver una orden si el user_id de la orden
        // coincide con el id del usuario autenticado.

        return $user->id === $order->user_id;
   }

    /**
     * Determine whether the user can pay the model.
     */
    public function payment(User $user, Order $order): bool
    {
        // Un usuario puede pagar una orden si:
        // 1. Es el propietario de la orden.
        // 2. El estado de la orden es PENDIENTE.
        return $user->id === $order->user_id && $order->status == \App\Models\Order::PENDIENTE;
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Permitimos actualizar si el usuario es el dueño y la orden está pendiente.
        // Esto es crucial para que el DummyPaymentController pueda cambiar el estado.
        return $user->id === $order->user_id && $order->status == \App\Models\Order::PENDIENTE;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return false;
    }
}
