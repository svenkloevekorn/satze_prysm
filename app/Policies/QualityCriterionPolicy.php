<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\QualityCriterion;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class QualityCriterionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QualityCriterion');
    }

    public function view(AuthUser $authUser, QualityCriterion $qualityCriterion): bool
    {
        return $authUser->can('View:QualityCriterion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QualityCriterion');
    }

    public function update(AuthUser $authUser, QualityCriterion $qualityCriterion): bool
    {
        return $authUser->can('Update:QualityCriterion');
    }

    public function delete(AuthUser $authUser, QualityCriterion $qualityCriterion): bool
    {
        return $authUser->can('Delete:QualityCriterion');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:QualityCriterion');
    }

    public function restore(AuthUser $authUser, QualityCriterion $qualityCriterion): bool
    {
        return $authUser->can('Restore:QualityCriterion');
    }

    public function forceDelete(AuthUser $authUser, QualityCriterion $qualityCriterion): bool
    {
        return $authUser->can('ForceDelete:QualityCriterion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:QualityCriterion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:QualityCriterion');
    }

    public function replicate(AuthUser $authUser, QualityCriterion $qualityCriterion): bool
    {
        return $authUser->can('Replicate:QualityCriterion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:QualityCriterion');
    }
}
