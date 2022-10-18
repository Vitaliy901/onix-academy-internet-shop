<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Answer $answer)
    {
        return $user->id == $answer->user_id;
    }

    public function delete(User $user, Answer $answer)
    {
        return $user->id == $answer->user_id || $user->isAdmin();
    }
}
