<?php

namespace App\Policies;

use App\Models\SalePoint;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalePointPolicy
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
    public function view(User $user, SalePoint $salePoint): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasAnyRole('manager|Super Admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SalePoint $salePoint): bool
    {
        if ($user->hasAnyRole('manager|Super Admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SalePoint $salePoint): bool
    {
        if ($user->hasAnyRole('manager|Super Admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SalePoint $salePoint): bool
    {
        if ($user->hasAnyRole('manager|Super Admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SalePoint $salePoint): bool
    {
        if ($user->hasAnyRole('manager|Super Admin')) {
            return true;
        }

        return false;
    }
}
