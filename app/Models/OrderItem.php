<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItem extends Pivot
{
    use HasFactory;

    protected $table = 'order_product';

    protected $fillable = [
        'price',
        'quantity',
        'total_price',
        'product_name',
        'product_image',
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopePrice(Builder $builder, $price)
    {
        $builder->when($price, function ($query) {
            $query->orderByDesc('total_price');
        });
    }

    public function scopeInterval(Builder $builder, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            $builder->whereBetween('created_at', [
                $startDate,
                $endDate,
            ]);
        }
    }
}
