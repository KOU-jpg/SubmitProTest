<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionTempMessages extends Model
{
    protected $fillable = ['user_id', 'item_id', 'message'];
}
