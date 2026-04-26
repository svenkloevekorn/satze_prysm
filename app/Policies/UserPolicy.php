<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

/**
 * User-Verwaltung ist auf super_admin beschraenkt.
 *
 * Wir nutzen NICHT das Filament-Shield-Permission-Schema fuer User,
 * weil "User darf andere User anlegen" fast immer = "User darf sich selbst
 * super_admin geben" bedeutet. Privilege-Escalation-Risiko.
 *
 * Gate::before in AppServiceProvider laesst super_admin durch alles durch,
 * sodass die Methoden hier explizit FALSE zurueckgeben koennen.
 */
class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return false;
    }

    public function view(AuthUser $authUser, User $user): bool
    {
        return false;
    }

    public function create(AuthUser $authUser): bool
    {
        return false;
    }

    public function update(AuthUser $authUser, User $user): bool
    {
        return false;
    }

    public function delete(AuthUser $authUser, User $user): bool
    {
        return false;
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return false;
    }

    public function restore(AuthUser $authUser, User $user): bool
    {
        return false;
    }

    public function forceDelete(AuthUser $authUser, User $user): bool
    {
        return false;
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return false;
    }

    public function replicate(AuthUser $authUser, User $user): bool
    {
        return false;
    }

    public function reorder(AuthUser $authUser): bool
    {
        return false;
    }
}
