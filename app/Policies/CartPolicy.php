<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;


    public function index(User $user)
    {
        return !$user->isAdmin();
    }

    public function create(User $user)
    {
        return !$user->isAdmin();
    }

    public function show(User $user)
    {
        return !$user->isAdmin();
    }

    public function update(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id && !$user->isAdmin();
    }

    public function delete(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id && !$user->isAdmin();
    }

    public function deleteAll(User $user)
    {
        return !$user->isAdmin();
    }
}
