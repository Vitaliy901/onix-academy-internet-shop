<?php

namespace App\Models;

use App\Enums\Status;
use App\Models\Scopes\Orders\OrderScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $attributes = [
        'status' => Status::OPEN,
    ];

    protected $with = ['user', 'orderItems'];

    protected $fillable = [
        'comment',
        'address',
        'status',
        'total_cost',
    ];

    protected $casts = [
        'status' => Status::class,
        'created_at' => 'datetime:H:i: Y.m.d',
        'updated_at' => 'datetime:H:i: Y.m.d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->using(OrderItem::class)
            ->as('orderItem')
            ->withPivot('price', 'quantity', 'total_price')
            ->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeUsers(Builder $builder, $user_ids)
    {
        $users = array_filter(explode(',', $user_ids));

        return $builder->when($user_ids, function ($query) use ($users) {
            $query->whereHas('user', function ($query) use ($users) {
                $query->whereIn('id', $users);
            });
        });
    }

    public function scopeStatus(Builder $builder, $status)
    {
        return $builder->when($status, function ($query, $status) {
            $query->where('status', $status);
        });
    }

    public function scopePrice(Builder $builder, $price)
    {
        $builder->when($price, function ($query) {
            $query->orderByDesc('total_cost');
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

    public function scopeTrashed(Builder $builder, $deleted)
    {
        $builder->when($deleted, function ($query) {
            $query->onlyTrashed();
        });
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrderScope);
    }
}
