<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class OrderService
{
    public function checkUser(Request $request)
    {
        if ($request->user()->can('index', Order::class)) {
            return Order::query()->users($request->user_ids);
        }
        return $request->user()->orders();
    }

    public function checkAddress(Request $request, int $total_cost): array
    {
        $validatedInput = $request->safe();

        if (!$request->has('address')) {

            $validatedInput = $validatedInput->merge([
                'address' => $request->user()->address,
            ]);
        }

        return $validatedInput->merge([
            'total_cost' => $total_cost,
        ])->all();
    }

    public function createItem(Request $request, Order $order, $product): void
    {
        DB::transaction(function () use ($request, $order, $product) {
            $order->products()->attach($request->product_id, [
                'quantity' => $request->quantity,
                'price' => $product->price,
                'total_price' => $product->price * $request->quantity,
                'product_name' => $product->name,
                'product_image' => $product->images
                    ->pluck('filename')
                    ->firstOrFail(),
            ]);
        }, 2);
    }

    public function optionDelete(Request $request, Order $order)
    {
        if ($request->user()->can('softDelete', $order)) {
            return $order->delete();
        }
        if ($request->user()->can('forceDelete', $order)) {
            return $order->forceDelete();
        }

        throw new AccessDeniedHttpException('This action is unauthorized.');
    }
}
