<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $withCount = ['answers'];

    protected $with = ['user'];

    protected $attributes = [
        'votes_up' => 0,
        'votes_down' => 0,
    ];

    protected $fillable = [
        'text',
        'product_id',
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

    public function scopeProduct(Builder $builder, $product_id)
    {
        return $builder->when($product_id, function ($query, $id) {
            $query->whereRelation('product', 'id', $id);
        });
    }
}
