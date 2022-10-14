<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $with = ['images'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'in_stock',
        'price',
        'category_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'float',
        'in_stock' => 'integer',
        'price' => 'integer',
        'category_id' => 'integer',
        'created_at' => 'datetime:Y.m.d H:i:s',
        'updated_at' => 'datetime:Y.m.d H:i:s',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function scopeCategories(Builder $builder, $category_ids)
    {
        $categories = array_filter(explode(',', $category_ids));

        return $builder->when($category_ids, function ($query) use ($categories) {
            $query->whereHas('category', function ($query) use ($categories) {
                $query->whereIn('id', $categories);
            });
        });
    }

    public function scopeRating(Builder $builder, $rating)
    {
        return $builder->when($rating, function ($query) {
            $query->orderByDesc('rating');
        });
    }


    public function scopeInStock(Builder $builder, $stock)
    {
        return $builder->when($stock, function ($query) {
            $query->where('in_stock', '>', 0);
        });
    }

    public function scopeSortByPrice(Builder $builder, $price)
    {
        if ($price === 'cheap') {
            return $builder->orderBy('price', 'ASC');
        }
        if ($price === 'expensive') {
            return $builder->orderByDesc('price');
        }
    }
}
