<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CompetitorProduct;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompetitorProductPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CompetitorProduct');
    }

    public function view(AuthUser $authUser, CompetitorProduct $competitorProduct): bool
    {
        return $authUser->can('View:CompetitorProduct');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CompetitorProduct');
    }

    public function update(AuthUser $authUser, CompetitorProduct $competitorProduct): bool
    {
        return $authUser->can('Update:CompetitorProduct');
    }

    public function delete(AuthUser $authUser, CompetitorProduct $competitorProduct): bool
    {
        return $authUser->can('Delete:CompetitorProduct');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CompetitorProduct');
    }

    public function restore(AuthUser $authUser, CompetitorProduct $competitorProduct): bool
    {
        return $authUser->can('Restore:CompetitorProduct');
    }

    public function forceDelete(AuthUser $authUser, CompetitorProduct $competitorProduct): bool
    {
        return $authUser->can('ForceDelete:CompetitorProduct');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CompetitorProduct');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CompetitorProduct');
    }

    public function replicate(AuthUser $authUser, CompetitorProduct $competitorProduct): bool
    {
        return $authUser->can('Replicate:CompetitorProduct');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CompetitorProduct');
    }

}