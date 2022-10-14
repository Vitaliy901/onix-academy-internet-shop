<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderItems\CreateRequest;
use App\Http\Requests\Api\OrderItems\IndexRequest;
use App\Http\Resources\OrderItemResource;
use App\Http\Traits\HttpResponse;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    use HttpResponse;
    /**
     * Show all products of the order.
     * 
     * @param  \App\Http\Requests\Api\OrderItems\IndexRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request, Order $order)
    {
        $this->authorize('show', $order);

        $orderItems = $order->orderItems()
            ->price($request->sort_by)
            ->paginate($request->per_page ?? 5);

        return OrderItemResource::collection($orderItems);
    }

    /**
     * Add an item to an existing order.
     *
     * @param  \App\Http\Requests\Api\OrderItems\CreateRequest $request
     * @param  \App\Services\OrderService $orderService
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, Order $order, OrderService $orderService)
    {
        $this->authorize('store', [OrderItem::class, $order]);

        $product = Product::findOrFail($request->product_id);

        if ($product->in_stock != 0) {

            if (!$order->orderItems->contains('product_id', $request->product_id)) {

                $orderService->createItem($request, $order, $product);
            }
            return $this->success(null, 201, 'Item added successfully!');
        }

        return response()->noContent();
    }

    /**
     * Show the product of the order.
     *
     * @param  \App\Models\Order $order
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order, $id)
    {
        $this->authorize('show', [OrderItem::class, $order]);

        if ($order->orderItems->contains('product_id', $id)) {

            $orderItem = $order->orderItems->firstWhere('product_id', $id);

            return new OrderItemResource($orderItem);
        }

        return response()->noContent();
    }

    /**
     * Update the product of order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order $order
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order, $id)
    {
        $this->authorize('update', [OrderItem::class, $order]);

        if ($order->orderItems->contains('product_id', $id)) {

            $validated = $request->validate([
                'quantity' => ['bail', 'required', 'integer', 'min:1']
            ]);

            $orderItem = $order->orderItems->firstWhere('product_id', $id);

            DB::transaction(function () use ($orderItem, $validated) {
                $orderItem->update($validated);
            }, 2);

            return new OrderItemResource($orderItem);
        }

        return response()->noContent();
    }

    /**
     * Remove the product from the order.
     *
     * @param  \App\Models\Order $order
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order, $id)
    {
        $this->authorize('delete', [OrderItem::class, $order]);

        if ($order->orderItems->contains('product_id', $id)) {

            $orderItem = $order->orderItems->firstWhere('product_id', $id);

            DB::transaction(function () use ($orderItem) {
                $orderItem->delete();
            }, 2);

            return $this->success(null, 200, 'Item deleted successfully!');
        }

        return response()->noContent();
    }
}
