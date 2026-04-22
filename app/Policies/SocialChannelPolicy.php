<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SocialChannel;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SocialChannelPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SocialChannel');
    }

    public function view(AuthUser $authUser, SocialChannel $socialChannel): bool
    {
        return $authUser->can('View:SocialChannel');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SocialChannel');
    }

    public function update(AuthUser $authUser, SocialChannel $socialChannel): bool
    {
        return $authUser->can('Update:SocialChannel');
    }

    public function delete(AuthUser $authUser, SocialChannel $socialChannel): bool
    {
        return $authUser->can('Delete:SocialChannel');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SocialChannel');
    }

    public function restore(AuthUser $authUser, SocialChannel $socialChannel): bool
    {
        return $authUser->can('Restore:SocialChannel');
    }

    public function forceDelete(AuthUser $authUser, SocialChannel $socialChannel): bool
    {
        return $authUser->can('ForceDelete:SocialChannel');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SocialChannel');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SocialChannel');
    }

    public function replicate(AuthUser $authUser, SocialChannel $socialChannel): bool
    {
        return $authUser->can('Replicate:SocialChannel');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SocialChannel');
    }
}
