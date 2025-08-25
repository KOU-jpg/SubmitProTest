<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'message',
        'image_path', 
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
