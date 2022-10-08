<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
    ];

    protected $hidden = [
        'product_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y.m.d i:m:s',
        'updated_at' => 'datetime:Y.m.d i:m:s',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
