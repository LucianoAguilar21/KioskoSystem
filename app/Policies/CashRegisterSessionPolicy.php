<?php
// app/Policies/CashRegisterSessionPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\CashRegisterSession;

class CashRegisterSessionPolicy
{
    /**
     * Determine if the user can open a cash register session.
     */
    public function open(User $user): bool
    {
        return true; // Cualquier usuario autenticado puede abrir caja
    }

    /**
     * Determine if the user can close a cash register session.
     */
    public function close(User $user, CashRegisterSession $session): bool
    {
        // El dueño puede cerrar cualquier caja
        // El empleado solo puede cerrar su propia caja
        return $user->isOwner() || $user->id === $session->user_id;
    }

    /**
     * Determine if the user can view cash register reports.
     */
    public function viewReports(User $user, ?CashRegisterSession $session = null): bool
    {
        // El dueño puede ver todos los reportes
        if ($user->isOwner()) {
            return true;
        }

        // El empleado solo puede ver sus propias cajas
        if ($session) {
            return $user->id === $session->user_id;
        }

        return false;
    }

    /**
     * Determine if the user can view any cash register session.
     */
    public function viewAny(User $user): bool
    {
        return true; // Todos pueden ver el listado
    }

    /**
     * Determine if the user can view a specific cash register session.
     */
    public function view(User $user, CashRegisterSession $session): bool
    {
        // El dueño puede ver cualquier sesión
        if ($user->isOwner()) {
            return true;
        }

        // El empleado solo puede ver sus propias sesiones
        return $user->id === $session->user_id;
    }
}