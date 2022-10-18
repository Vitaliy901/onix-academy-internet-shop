<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Traits\HttpResponse;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use HttpResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', Cart::class);

        $validated = $request->validate([
            'per_page' => ['bail', 'sometimes', 'integer', 'between:2,10'],
        ]);

        $cart = Cart::where('user_id', auth()->id())->paginate($validated['per_page'] ?? 5);

        return CartResource::collection($cart);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Cart::class);

        $validated = $request->validate([
            'product_id' => ['bail', 'required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->in_stock != 0) {

            if ($request->user()->carts()->where('product_id', $product->id)->doesntExist()) {
                $request->user()->products()->attach([$product->id => [
                    'quantity' => 1,
                    'price' => $product->price,
                    'total_price' => $product->price,
                ]]);
            }
            return $this->success(null, 201, 'The product has been added to the cart successfully!');
        }

        return $this->error(null, 401, 'The product is out of stock!');
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Cart  $model
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        $this->authorize('show', $cart);

        return new CartResource($cart);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        $validated = $request->validate([
            'quantity' => ['bail', 'required', 'integer', 'min:1']
        ]);

        if ($cart->product->in_stock >= $request->quantity) {
            $validated['total_price'] = $cart->product->price * $request->quantity;

            $cart->update($validated);

            return $this->success(new CartResource($cart), 200, 'Quantity updated successfully!');
        }

        return $this->error(null, 401, 'The product is not available in this quantity!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $this->authorize('delete', $cart);

        $cart->delete();

        return $this->success(null, 200, 'The product has been removed from the cart successfully!');
    }
}
