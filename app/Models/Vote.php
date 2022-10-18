<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $with = ['question'];

    protected $fillable = [
        'question_id',
        'status',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    protected static function booted()
    {
        static::created(function ($vote) {
            match ($vote->status) {
                Status::UP => $vote->question()->increment('votes_up'),
                Status::DOWN => $vote->question()->increment('votes_down'),
            };
        });

        static::updating(function ($vote) {
            if ($vote->status == Status::UP && $vote->isDirty('status')) {
                $vote->question()->increment('votes_up');
                $vote->question()->decrement('votes_down');
            }

            if ($vote->status == Status::DOWN && $vote->isDirty('status')) {
                $vote->question()->increment('votes_down');
                $vote->question()->decrement('votes_up');
            }
        });

        static::deleting(function ($vote) {
            match ($vote->status) {
                Status::UP => $vote->question()->decrement('votes_up'),
                Status::DOWN => $vote->question()->decrement('votes_down'),
            };
        });
    }
}
