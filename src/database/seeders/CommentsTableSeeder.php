<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CommentsTableSeeder extends Seeder
{
    public function run()
    {
        $baseTime = Carbon::now();  // ここで定義するのが必須です

        DB::table('comments')->insert([
            [
                'item_id' => 1,
                'user_id' => 2,
                'comment' => 'こんにちは！この商品はまだ購入可能ですか？',
                'created_at' => $baseTime->copy()->subMinutes(6),
                'updated_at' => $baseTime->copy()->subMinutes(6),
            ],
            [
                'item_id' => 1,
                'user_id' => 1,
                'comment' => 'はい、まだ購入できますよ！',
                'created_at' => $baseTime->copy()->subMinutes(5),
                'updated_at' => $baseTime->copy()->subMinutes(5),
            ],
            [
                'item_id' => 1,
                'user_id' => 2,
                'comment' => '発送までどれくらいかかりますか？',
                'created_at' => $baseTime->copy()->subMinutes(4),
                'updated_at' => $baseTime->copy()->subMinutes(4),
            ],
            [
                'item_id' => 1,
                'user_id' => 2,
                'comment' => 'できれば早めに発送してもらえると助かります。',
                'created_at' => $baseTime->copy()->subMinutes(3),
                'updated_at' => $baseTime->copy()->subMinutes(3),
            ],
            [
                'item_id' => 1,
                'user_id' => 1,
                'comment' => 'ご希望に添えるようにします！',
                'created_at' => $baseTime->copy()->subMinutes(2),
                'updated_at' => $baseTime->copy()->subMinutes(2),
            ],
            [
                'item_id' => 1,
                'user_id' => 2,
                'comment' => 'ありがとうございます。検討します！',
                'created_at' => $baseTime->copy()->subMinutes(1),
                'updated_at' => $baseTime->copy()->subMinutes(1),
            ],
            [
                'item_id' => 4,
                'user_id' => 3,
                'comment' => 'こんにちは、発送にはどこくらいの日数がかかりますか？',
                'created_at' => $baseTime->copy()->subMinutes(6),
                'updated_at' => $baseTime->copy()->subMinutes(6),
            ],
            [
                'item_id' => 4,
                'user_id' => 1,
                'comment' => 'ありがとうございます。3日くらいです',
                'created_at' => $baseTime->copy()->subMinutes(5),
                'updated_at' => $baseTime->copy()->subMinutes(5),
            ],
            [
                'item_id' => 4,
                'user_id' => 2,
                'comment' => 'こんにちは、割引は可能ですか',
                'created_at' => $baseTime->copy()->subMinutes(4),
                'updated_at' => $baseTime->copy()->subMinutes(4),
            ],
            [
                'item_id' => 4,
                'user_id' => 1,
                'comment' => '検討します！',
                'created_at' => $baseTime->copy()->subMinutes(3),
                'updated_at' => $baseTime->copy()->subMinutes(3),
            ],
            [
                'item_id' => 4,
                'user_id' => 2,
                'comment' => 'お願いします',
                'created_at' => $baseTime->copy()->subMinutes(2),
                'updated_at' => $baseTime->copy()->subMinutes(2),
            ],
            [
                'item_id' => 4,
                'user_id' => 3,
                'comment' => 'わかりました',
                'created_at' => $baseTime->copy()->subMinutes(1),
                'updated_at' => $baseTime->copy()->subMinutes(1),
            ],
        ]);
    }
}