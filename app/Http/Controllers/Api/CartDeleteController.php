<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\HttpResponse;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartDeleteController extends Controller
{
    use HttpResponse;
    /**
     * Remove all products from the cart.
     * 
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->authorize('deleteAll', Cart::class);

        $request->user()->carts()->delete();

        return $this->success(null, 200, 'Cart cleaned successfully!');
    }
}
