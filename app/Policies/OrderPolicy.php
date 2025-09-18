<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Permite a los administradores realizar cualquier acción, excepto pagar una orden.
     * Para el pago, se usarán las reglas del método 'payment'.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('admin') && $ability !== 'payment') {
            return true;
        }

        // Si no es admin o la habilidad es 'payment', no intervenimos y dejamos que la política específica decida.
        return null;
    }

    /**
     * Determina si el usuario puede ver sus propias órdenes.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede ver una orden específica.
     * Solo puede verla si es el dueño de la orden.
     */
    public function view(User $user, Order $order): bool
    {
        // Un admin puede ver cualquier orden, un usuario normal solo las suyas.
        // Esta lógica ya está cubierta por el método 'before', pero la dejamos por claridad.
        return $user->id === $order->user_id;
    }

    /**
     * Determina si un usuario puede pagar una orden.
     */
    public function payment(User $user, Order $order): bool
    {
        // REGLA DE ORO: La condición principal para que CUALQUIER usuario pueda pagar
        // es que la orden debe estar en estado 'PENDIENTE'. Si no, nadie puede.
        if ($order->status !== Order::PENDIENTE) {
            return false;
        }

        // Un administrador puede pagar la orden de cualquier cliente (siempre que esté pendiente).
        // Esta comprobación se ejecuta porque el método 'before' no interviene para la habilidad 'payment'.
        if ($user->hasRole('admin')) {
            return true;
        }

        // Un usuario normal solo puede pagar sus propias órdenes (siempre que estén pendientes).
        return $user->id === $order->user_id;
    }
}
