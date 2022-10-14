<?php

namespace App\Observers;

use App\Enums\Status;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderCanceled;
use App\Notifications\OrderInvoice;
use App\Notifications\OrderStatus;
use Exception;

class OrderObserver
{

    /**
     * Check product in stock when order creating.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
        $products = $order->user->products;

        $products->each(function ($product) {

            if ($product->cart->quantity > $product->in_stock) {
                throw new Exception('The product is out of stock!');
            }

            $product->decrement('in_stock', $product->cart->quantity);
        });
    }

    /**
     * Creating orderitems for order.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $products = $order->user->products;

        $products->each(function ($product) {

            $this->orderItems[$product->id] = [
                'price' => $product->cart->price,
                'quantity' => $product->cart->quantity,
                'total_price' => $product->cart->total_price,
                'product_name' => $product->name,
                'product_image' => $product->images
                    ->pluck('filename')
                    ->firstOrFail(),
            ];
        });

        OrderItem::withoutEvents(function () use ($order) {
            $order->products()->attach($this->orderItems);
        });

        $order->user->carts()->delete();

        $order->user->notify(new OrderInvoice($order));
    }

    /**
     * Send notification when order confirmed.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if ($order->status == Status::CONFIRMED) {
            $order->user->notify(new OrderStatus($order));
        };

        if ($order->status == Status::CANCELED) {

            $order->orderItems->each(function ($orderItem) {
                $orderItem->product->increment('in_stock', $orderItem->quantity);
            });

            $order->delete();

            $order->user->notify(new OrderCanceled($order));
        };
    }
}
