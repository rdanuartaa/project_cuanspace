<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

     public function chatsAsUser()
    {
        return $this->hasMany(Chat::class, 'user_id');
    }

    public function chatsAsSeller()
    {
        return $this->hasMany(Chat::class, 'seller_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'seller_id');
    }

     public function isAdmin(): bool
    {
        return $this->id === 1; // Asumsi admin memiliki id = 1
    }
}
