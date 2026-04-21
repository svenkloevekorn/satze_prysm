<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\RatingDimension;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class RatingDimensionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RatingDimension');
    }

    public function view(AuthUser $authUser, RatingDimension $ratingDimension): bool
    {
        return $authUser->can('View:RatingDimension');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RatingDimension');
    }

    public function update(AuthUser $authUser, RatingDimension $ratingDimension): bool
    {
        return $authUser->can('Update:RatingDimension');
    }

    public function delete(AuthUser $authUser, RatingDimension $ratingDimension): bool
    {
        return $authUser->can('Delete:RatingDimension');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RatingDimension');
    }

    public function restore(AuthUser $authUser, RatingDimension $ratingDimension): bool
    {
        return $authUser->can('Restore:RatingDimension');
    }

    public function forceDelete(AuthUser $authUser, RatingDimension $ratingDimension): bool
    {
        return $authUser->can('ForceDelete:RatingDimension');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RatingDimension');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RatingDimension');
    }

    public function replicate(AuthUser $authUser, RatingDimension $ratingDimension): bool
    {
        return $authUser->can('Replicate:RatingDimension');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RatingDimension');
    }
}
