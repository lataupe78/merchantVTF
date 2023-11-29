<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {

        if ($user->hasAnyRole(['manager', 'seller',])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {

        if ($user->hasAnyRole(['manager', 'seller'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {


        if ($user->hasAnyRole(['manager', 'seller'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {

        if ($user->hasAnyRole(['manager', 'Super Admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Report $report): bool
    {
        if ($user->hasAnyRole('Super Admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Report $report): bool
    {
        if ($user->hasAnyRole('Super Admin')) {
            return true;
        }

        return false;
    }
}
