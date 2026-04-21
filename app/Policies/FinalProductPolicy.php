<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FinalProduct;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class FinalProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FinalProduct');
    }

    public function view(AuthUser $authUser, FinalProduct $finalProduct): bool
    {
        return $authUser->can('View:FinalProduct');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FinalProduct');
    }

    public function update(AuthUser $authUser, FinalProduct $finalProduct): bool
    {
        return $authUser->can('Update:FinalProduct');
    }

    public function delete(AuthUser $authUser, FinalProduct $finalProduct): bool
    {
        return $authUser->can('Delete:FinalProduct');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:FinalProduct');
    }

    public function restore(AuthUser $authUser, FinalProduct $finalProduct): bool
    {
        return $authUser->can('Restore:FinalProduct');
    }

    public function forceDelete(AuthUser $authUser, FinalProduct $finalProduct): bool
    {
        return $authUser->can('ForceDelete:FinalProduct');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FinalProduct');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FinalProduct');
    }

    public function replicate(AuthUser $authUser, FinalProduct $finalProduct): bool
    {
        return $authUser->can('Replicate:FinalProduct');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FinalProduct');
    }
}
