<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Orders\CreateRequest;
use App\Http\Requests\Api\Orders\IndexRequest;
use App\Http\Requests\Api\Orders\UpdateRequest;
use App\Http\Resources\OrderResource;
use App\Http\Traits\HttpResponse;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use HttpResponse;
    /**
     * Display a listing of the resource.
     * 
     * @param \App\Http\Requests\Api\Orders\IndexRequest $request
     * @param \App\Services\OrderService $orderService
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request, OrderService $orderService)
    {
        $orders = $orderService->checkUser($request)
            ->status($request->status)
            ->price($request->sort_by)
            ->interval($request->startDate, $request->endDate)
            ->trashed($request->getTrashed)
            ->paginate($request->per_page ?? 5);

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Api\Orders\CreateRequest $request
     * @param \App\Services\OrderService $orderService
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, OrderService $orderService)
    {
        $total_cost = $request->user()->carts()->sum('total_price');

        if ($total_cost) {

            $validated = $orderService->checkAddress($request, $total_cost);

            $order = DB::transaction(function () use ($request, $validated) {
                return $request->user()->orders()->create($validated);
            }, 2);

            return $this->success(
                new OrderResource($order),
                201,
                'Order created successfully!'
            );
        }

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     * 
     * @param \App\Models\Order $model
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $this->authorize('show', $order);

        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Api\Orders\UpdateRequest $request
     * @param \App\Models\Order $model
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        DB::transaction(function () use ($request, $order) {
            $order->update($request->validated());
        });

        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param \Illuminate\Http\Request $reqeust
     * @param \App\Models\Order  $model
     * @param \App\Services\OrderService $orderService
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Order $order, OrderService $orderService)
    {
        $orderService->optionDelete($request, $order);

        return $this->success(null, 200, 'Order deleted successfully!');
    }
}
