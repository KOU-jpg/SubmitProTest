<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'user_id',
        'buyer_id',
        'condition_id',
        'name',
        'description',
        'brand',
        'price',
        'sold_at',
        'payment_status',
        'payment_expiry',
        'favorite_count',
        'like_count',
        'status',
        'last_buyer_access',
        'last_seller_access',
    ];

    public function user()    {
        return $this->belongsTo(User::class);    }

    public function categories(){
    return $this->belongsToMany(Category::class, 'category_items');}

    public function condition()    {
        return $this->belongsTo(Condition::class);    }

    public function images()    {
        return $this->hasMany(ItemImage::class);    }

    public function comments(){
    return $this->hasMany(Comment::class);}

    public function favorites()    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
    public function transactionMessages()
    {
        return $this->hasMany(TransactionMessage::class);
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'item_id');
    }
    
}
