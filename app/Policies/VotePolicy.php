<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Auth\Access\HandlesAuthorization;

class VotePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Vote $vote)
    {
        return $user->id == $vote->user_id;
    }

    public function delete(User $user, Vote $vote)
    {
        return $user->id == $vote->user_id;
    }
}
