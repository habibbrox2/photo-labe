<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\User;

class QuotePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    public function view(User $user, Quote $quote): bool
    {
        if ($user->isAdmin() || $user->isEditor()) {
            return true;
        }

        return $user->id === $quote->user_id;
    }

    public function update(User $user, Quote $quote): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    public function delete(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
