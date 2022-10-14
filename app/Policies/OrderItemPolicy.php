<?php

namespace App\Policies;

use App\Enums\Status;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderItemPolicy
{
    use HandlesAuthorization;

    public function store(User $user, Order $order)
    {
        return $user->isAdmin() && $order->status == Status::OPEN;
    }

    public function show(User $user, Order $order)
    {
        return $user->id === $order->user_id || $user->isAdmin();
    }

    public function update(User $user, Order $order)
    {
        return $user->isAdmin() && $order->status == Status::OPEN;
    }

    public function delete(User $user, Order $order)
    {
        return $user->isAdmin() && $order->status == Status::OPEN;
    }
}
