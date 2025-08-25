<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/** implements MustVerifyEmailを付け加えてメール認証機能を有効にする*/
class User extends Authenticatable implements MustVerifyEmail
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

    public function profile()    {
        return $this->hasOne(Profile::class);    }

    public function items()    {
        return $this->hasMany(Item::class);    }

    public function comments()    {
        return $this->hasMany(Comment::class);    }

    public function favorites()
    {
        return $this->belongsToMany(Item::class, 'favorites')->withTimestamps();
    }
    public function transactionMessages()
    {
        return $this->hasMany(TransactionMessage::class);
    }
    public function givenRatings()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }
    public function receivedRatings()
    {
        return $this->hasMany(Rating::class, 'ratee_id');
    }
}
