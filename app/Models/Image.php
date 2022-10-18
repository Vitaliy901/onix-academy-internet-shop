<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Image extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'filename',
    ];

    protected $hidden = [
        'product_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y.m.d H:i:s',
        'updated_at' => 'datetime:Y.m.d H:i:s',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function prunable()
    {
        return static::whereNull('product_id')
            ->where('created_at', '<', now()->subDay());
    }

    protected function pruning()
    {
        $slice = Str::of($this->filename)->afterLast('/');

        Storage::delete('images/' . $slice);
    }
}
