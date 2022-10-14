<?php

namespace App\Policies;

use App\Enums\Status;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
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

    public function update(User $user, Order $order)
    {
        return $user->isAdmin() &&
            $order->status == Status::OPEN;
    }

    public function show(User $user, Order $order)
    {
        return $user->id === $order->user_id || $user->isAdmin();
    }

    public function softDelete(User $user, Order $order)
    {
        return $user->id === $order->user_id &&
            !$user->isAdmin() &&
            $order->status == Status::CONFIRMED;
    }

    public function forceDelete(User $user, Order $order)
    {
        return $user->isAdmin() &&
            $order->status == Status::CANCELED ||
            ($order->status == Status::CONFIRMED &&
                $order->trashed());
    }

    public function forceDeleteAll(User $user)
    {
        return $user->isAdmin();
    }
}
