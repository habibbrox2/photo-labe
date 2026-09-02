<?php

namespace App\Policies;

use App\Models\PortfolioProject;
use App\Models\User;

class PortfolioPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, PortfolioProject $project): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, PortfolioProject $project): bool
    {
        return $user->isAdmin();
    }
}
