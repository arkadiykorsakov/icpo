<?php

namespace App\Policies;

use App\Models\Criminal;
use App\Models\User;

class CriminalPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Criminal $criminal): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Criminal $criminal): bool
    {
        return ! $criminal->trashed();
    }

    public function delete(User $user, Criminal $criminal): bool
    {
        return ! $criminal->trashed() && filled($criminal->death_date);
    }

    public function restore(User $user, Criminal $criminal): bool
    {
        return $criminal->trashed();
    }

    public function forceDelete(User $user, Criminal $criminal): bool
    {
        return $criminal->trashed();
    }
}
