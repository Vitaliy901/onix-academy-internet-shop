<?php

namespace App\Observers;

use App\Models\OrderItem;
use Exception;

class OrderItemObserver
{
    /**
     * Increase the total cost of the order and subtract the product quantity.
     *
     * @param  \App\Models\OrderItem  $orderItem
     * @return void
     */
    public function creating(OrderItem $orderItem)
    {
        $product = $orderItem->product;

        if ($orderItem->quantity > $product->in_stock) {
            throw new Exception('The product is out of stock!');
        }

        $product->decrement('in_stock', $orderItem->quantity);

        $orderItem->order->update([
            'total_cost' => $orderItem->order->total_cost + $orderItem->total_price,
        ]);
    }

    /**
     * Handle the OrderItem "updating" event.
     *
     * Complex calculations.
     * 
     * @param  \App\Models\OrderItem  $orderItem
     * @return void
     */
    public function updating(OrderItem $orderItem)
    {
        $product = $orderItem->product;

        $less = $orderItem->quantity - $orderItem->getOriginal('quantity');

        $greater = $orderItem->getOriginal('quantity') - $orderItem->quantity;

        $orderItem->total_price = $orderItem->price * $orderItem->quantity;

        if ($orderItem->quantity < $orderItem->getOriginal('quantity')) {
            $different =  $orderItem->getOriginal('total_price') - $orderItem->total_price;

            $orderItem->order->update(['total_cost' => $orderItem->order->total_cost - $different]);

            $product->increment('in_stock', $greater);
        } else if (
            $less <= $product->in_stock &&
            $orderItem->quantity > $orderItem->getOriginal('quantity')
        ) {
            $different =  $orderItem->total_price - $orderItem->getOriginal('total_price');

            $orderItem->order->update([
                'total_cost' => $orderItem->order->total_cost + $different
            ]);

            $product->decrement('in_stock', $less);
        } else {
            throw new Exception('The product is out of stock!');
        }
    }

    /**
     * Reduce the total cost of the order and return the quantity to the product.
     *
     * @param  \App\Models\OrderItem  $orderItem
     * @return void
     */
    public function deleting(OrderItem $orderItem)
    {
        $product = $orderItem->product;

        $orderItem->order->update([
            'total_cost' => $orderItem->order->total_cost - $orderItem->total_price
        ]);

        $product->increment('in_stock', $orderItem->quantity);
    }
}
