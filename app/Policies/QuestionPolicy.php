<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
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

    public function update(User $user, Question $question)
    {
        return $user->id == $question->user_id && !$user->isAdmin();
    }

    public function delete(User $user, Question $question)
    {
        return $user->id == $question->user_id || $user->isAdmin();
    }
}
