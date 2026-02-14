<?php
// app/Policies/PurchasePolicy.php
namespace App\Policies;

use App\Models\User;

class PurchasePolicy
{
    public function create(User $user): bool
    {
        return $user->isOwner();
    }

    public function viewAny(User $user): bool
    {
        return $user->isOwner();
    }
}