<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    { 
        // 外部キー制約を一時的に無効化
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 必要なテーブルをtruncate
        \DB::table('item_images')->truncate();
        \DB::table('comments')->truncate();
        \DB::table('category_items')->truncate();
        \DB::table('items')->truncate();
        \DB::table('profiles')->truncate();
        \DB::table('users')->truncate();
        // 他の関連テーブルもここでtruncate

        // 外部キー制約を有効化
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // その後、各Seederを呼び出す
        $this->call([
            UserSeeder::class,
            ProfilesTableSeeder::class,
            ConditionsTableSeeder::class,
            CategoriesTableSeeder::class,
            ItemTableSeeder::class,
            ItemImageTableSeeder::class,
            CommentsTableSeeder::class,
        ]);

        // 取引メッセージで送信された画像の削除
$directory = storage_path('app/public/transaction_images');
        if (File::exists($directory)) {
            File::deleteDirectory($directory);
        }
    }
}