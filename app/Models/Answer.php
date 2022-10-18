<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
        'answerable_id',
        'answerable_type',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y.m.d H:i:s',
        'updated_at' => 'datetime:Y.m.d H:i:s',
    ];

    public function answerable()
    {
        return $this->morphTo();
    }

    public function scopeMorphFilter(Builder $builder, Request $request)
    {
        if ($request->has('questions_id')) {
            $id = $request->get('questions_id');

            return $builder->whereHasMorph(
                'answerable',
                [Question::class],
                function (Builder $query) use ($id) {
                    $query->where('id', $id);
                }
            );
        }

        if ($request->has('reviews_id')) {
            $id = $request->get('reviews_id');

            return $builder->whereHasMorph(
                'answerable',
                [Review::class],
                function (Builder $query) use ($id) {
                    $query->where('id', $id);
                }
            );
        }
    }
}
