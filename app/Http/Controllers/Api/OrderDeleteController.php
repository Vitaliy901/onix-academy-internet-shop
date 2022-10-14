<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Traits\HttpResponse;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderDeleteController extends Controller
{
    use HttpResponse;
    /**
     * User can delete all his confirmed orders.
     * The administrator can only delete orders that have been deleted by softDeleted.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->cannot('forceDeleteAll', Order::class)) {

            $user->orders()->where('status', Status::CONFIRMED->value)->delete();
            return $this->success(null, 200, 'All orders have been cleared successfully!');
        }

        if ($user->can('forceDeleteAll', Order::class)) {

            Order::whereNotNull('deleted_at')->forceDelete();
            return $this->success(null, 200, 'All trashed orders have been cleared successfully!');
        };
    }
}
