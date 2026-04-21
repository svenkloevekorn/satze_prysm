<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DevelopmentItem;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DevelopmentItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DevelopmentItem');
    }

    public function view(AuthUser $authUser, DevelopmentItem $developmentItem): bool
    {
        return $authUser->can('View:DevelopmentItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DevelopmentItem');
    }

    public function update(AuthUser $authUser, DevelopmentItem $developmentItem): bool
    {
        return $authUser->can('Update:DevelopmentItem');
    }

    public function delete(AuthUser $authUser, DevelopmentItem $developmentItem): bool
    {
        return $authUser->can('Delete:DevelopmentItem');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DevelopmentItem');
    }

    public function restore(AuthUser $authUser, DevelopmentItem $developmentItem): bool
    {
        return $authUser->can('Restore:DevelopmentItem');
    }

    public function forceDelete(AuthUser $authUser, DevelopmentItem $developmentItem): bool
    {
        return $authUser->can('ForceDelete:DevelopmentItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DevelopmentItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DevelopmentItem');
    }

    public function replicate(AuthUser $authUser, DevelopmentItem $developmentItem): bool
    {
        return $authUser->can('Replicate:DevelopmentItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DevelopmentItem');
    }
}
