<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\ValidatedInput;

class ReviewService
{
    public function create(ValidatedInput $data, User $user)
    {
        $bought = $user->orders()
            ->whereRelation('orderItems', 'product_id', $data['product_id'])
            ->exists();

        $validated = $data->merge([
            'bought' => $bought,
        ])->all();

        return $user->reviews()->create($validated);
    }
}
