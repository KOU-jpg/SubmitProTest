<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
        [
        'user_id' => 1,
        'condition_id' => 1,
        'name' => '腕時計',
        'description' => 'スタイリッシュなデザインのメンズ腕時計',
        'brand' => 'Armani',
        'price' => 15000,
        'created_at' => now(),
        'updated_at' => now(),
        'categories' => [1, 3, 5, 13,], 
        ],
        [
        'user_id' => 1,
        'condition_id' => 2, // 目立った傷や汚れなし
        'name' => 'HDD',
        'description' => '高速で信頼性の高いハードディスク',
        'brand' => 'Western Digital', 
        'price' => 5000,
        'created_at' => now(),
        'updated_at' => now(),
        'categories' => [2],
        ],
        [
        'user_id' => 1,
        'condition_id' => 3, // やや傷や汚れあり
        'name' => '玉ねぎ3束',
        'description' => '新鮮な玉ねぎ3束のセット',
        'brand' => null,
        'price' => 300,
        'created_at' => now(),
        'updated_at' => now(),
        'categories' => [3],
        ],
        [
        'user_id' => 1,
        'condition_id' => 4, // 状態が悪い
        'name' => '革靴',
        'description' => 'クラシックなデザインの革靴',
        'brand' => 'REGAL', 
        'price' => 4000,
        'created_at' => now(),
        'updated_at' => now(),
        'categories' => [1], 
        ],
        [
        'user_id' => 1,
        'condition_id' => 1, // 良好
        'name' => 'ノートPC',
        'description' => '高性能なノートパソコン',
        'brand' => 'NEC', 
        'price' => 45000,
        'created_at' => now(),
        'updated_at' => now(),
        'categories' => [2], 
        ],
        [
        'user_id' => 2,
        'condition_id' => 2, // 目立った傷や汚れなし
        'name' => 'マイク',
        'description' => '高音質のレコーディング用マイク',
        'brand' => 'SHURE', 
        'price' => 8000,
        'created_at' => now(),
        'updated_at' => now(),
        'categories' => [2], 
        ],
        [
        'user_id' => 2,
        'condition_id' => 3, // やや傷や汚れあり
        'name' => 'ショルダーバッグ',
        'description' => 'おしゃれなショルダーバッグ',
        'brand' => null, 
        'price' => 3500,
        'created_at' => now(),
        'updated_at' => now(),
        'categories' => [1,7] 
        ],
        [
        'user_id' => 2,
        'condition_id' => 4, // 状態が悪い
        'name' => 'タンブラー',
        'description' => '使いやすいタンブラー',
        'brand' => null,
        'price' => 500,
        'created_at' => now(),
        'updated_at' => now(),
        'categories' => [4] 
        ],
        [
        'user_id' => 2,
        'condition_id' => 1, // 良好
        'name' => 'コーヒーミル',
        'description' => '手動のコーヒーミル',
        'brand' => null,
        'price' => 4000,
        'created_at' => now(),
        'updated_at' => now(),
        'categories' => [4]
        ],
        [
        'user_id' => 2,
        'condition_id' => 2, // 目立った傷や汚れなし
        'name' => 'メイクセット',
        'description' => '便利なメイクアップセット',
        'brand' => null,
        'price' => 2500,
        'created_at' => now(),
        'updated_at' => now(),
        'categories' => [1]
        ]
    ];

    foreach ($items as $itemData) {
        $categoryIds = $itemData['categories'];
        unset($itemData['categories']);

        $item = Item::create($itemData);
        $item->categories()->attach($categoryIds);
    }
    }
}