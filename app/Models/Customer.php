<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'phone',
    ];

    
    protected $casts = [
        //'email_verified_at' => 'datetime',
        //'password' => 'hashed',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
    

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

}
