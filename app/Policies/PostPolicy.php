<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Mag de gebruiker de lijst van posts zien?
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Mag de gebruiker één specifieke post bekijken?
     */
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Mag de gebruiker een nieuwe post aanmaken?
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Mag de gebruiker deze post bewerken?
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Mag de gebruiker deze post soft deleten?
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Mag de gebruiker deze post restoren?
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Mag de gebruiker deze post definitief verwijderen?
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Mag de gebruiker deze post publiceren?
     */
    public function publish(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Mag de gebruiker deze post depubliceren?
     */
    public function unpublish(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}
