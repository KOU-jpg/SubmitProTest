<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    // テーブル名は慣例通り 'ratings' を使用
    protected $table = 'ratings';

    // 書き込み可能なフィールド
    protected $fillable = [
        'rater_id',
        'ratee_id',
        'score',
        'item_id',
    ];

    // 評価したユーザー（評価者）とのリレーション
    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    // 評価されたユーザー（被評価者）とのリレーション
    public function ratee()
    {
        return $this->belongsTo(User::class, 'ratee_id');
    }

    // 関連するアイテム（評価対象の取引がある場合）
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
