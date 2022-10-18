<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $withCount = ['answers'];

    protected $with = ['user'];

    protected $fillable = [
        'text',
        'benefits',
        'disadvantages',
        'rating',
        'bought',
        'product_id',
    ];

    protected $casts = [
        'rating' => 'integer',
        'product_id' => 'integer',
        'created_at' => 'datetime:Y.m.d H:i:s',
        'updated_at' => 'datetime:Y.m.d H:i:s',
    ];

    public function answers()
    {
        return $this->morphMany(Answer::class, 'answerable');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDate(Builder $builder, $date)
    {
        return $builder->when($date, function ($query) {
            $query->orderByDesc('created_at');
        });
    }

    public function scopeWhoBought(Builder $builder, $bought)
    {
        return $builder->when($bought, function ($query) {
            $query->orderByDesc('bought');
        });
    }

    public function scopeProduct(Builder $builder, $product_id)
    {
        return $builder->when($product_id, function ($query, $id) {
            $query->whereRelation('product', 'id', $id);
        });
    }
}
