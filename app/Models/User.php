<?php

namespace App\Models;

use App\Notifications\Auth\QueuedResetPassword;
use App\Notifications\Auth\QueuedVerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use ProtoneMedia\LaravelVerifyNewEmail\MustVerifyNewEmail;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, MustVerifyNewEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'device_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'is_admin',
        'deleted_at',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart')
            ->as('cart')
            ->withPivot('price', 'quantity', 'total_price')
            ->withTimestamps();
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeEmail(Builder $builder, $email)
    {
        return $builder->when($email, function ($query, $keywords) {
            $query->where('email', 'ILIKE', $keywords . '%');
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

    public function scopeSortByDesc(Builder $builder, $sortBy)
    {
        $builder->when($sortBy, function ($query) {
            $query->orderByDesc('created_at');
        });
    }

    public function scopeTrashed(Builder $builder, $deleted)
    {
        $builder->when($deleted, function ($query) {
            $query->onlyTrashed();
        });
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    protected static function booted()
    {
        static::created(function ($user) {
            event(new Registered($user));
        });
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new QueuedVerifyEmail);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new QueuedResetPassword($token));
    }
}
