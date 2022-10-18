<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Review $review)
    {
        return $user->id == $review->user_id && !$user->isAdmin();
    }

    public function delete(User $user, Review $review)
    {
        return $user->id == $review->user_id || $user->isAdmin();
    }
}
