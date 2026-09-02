<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;

class BlogPostPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    public function update(User $user, BlogPost $post): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isEditor() && $user->id === $post->author_id;
    }

    public function delete(User $user, BlogPost $post): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isEditor() && $user->id === $post->author_id;
    }
}
