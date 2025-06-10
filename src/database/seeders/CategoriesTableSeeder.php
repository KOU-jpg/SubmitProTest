<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
    // カテゴリー追加（既存データ削除して再読み込み方法）
    //DB::table('categories')->delete();をここに書き、
    //php artisan db:seed --class=CategoriesTableSeederを実行
    DB::table('categories')->delete();
        $categories = [
            ['id' => 1,  'name' => 'ファッション'],
            ['id' => 2,  'name' => '家電'],
            ['id' => 3,  'name' => 'インテリア'],
            ['id' => 4,  'name' => 'レディース'],
            ['id' => 5,  'name' => 'メンズ'],
            ['id' => 6,  'name' => 'コスメ'],
            ['id' => 7,  'name' => 'グルメ'],
            ['id' => 8,  'name' => 'スポーツ'],
            ['id' => 9,  'name' => '本・マンガ'],
            ['id' => 10, 'name' => 'ハンドメイド'],
            ['id' => 11, 'name' => 'アクセサリー'],
            ['id' => 12, 'name' => 'おもちゃ'],
            ['id' => 13, 'name' => 'ベビー・キッズ'],
        ];

    DB::table('categories')->insert($categories);
}
}
