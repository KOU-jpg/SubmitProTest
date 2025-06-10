<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfilesTableSeeder extends Seeder
{
    public function run()
    {
        // 保存先ディレクトリ
        $storageDir = 'images/profile_images';
        $storageDisk = Storage::disk('public');

        // ディレクトリがなければ作成
        if (!$storageDisk->exists($storageDir)) {
            $storageDisk->makeDirectory($storageDir);
        }

        // 既存ファイルを全削除
        $files = $storageDisk->files($storageDir);
        $storageDisk->delete($files);

        // サンプル画像の元ディレクトリ
        $sourceDir = base_path('public/images/sample/profile_image_samples');

        // プロフィールデータ
        $profiles = [
            [
                'user_id' => 1,
                'postal_code' => '100-0001',
                'address' => '東京都千代田区千代田1-1',
                'building' => 'テストビル101',
                'image_file' => 'にこにこ黄色.png',
            ],
            [
                'user_id' => 2,
                'postal_code' => '150-0001',
                'address' => '東京都渋谷区渋谷2-2-2',
                'building' => 'サンプルマンション202',
                'image_file' => 'にこにこ桜.jpg',
            ],
            [
                'user_id' => 3,
                'postal_code' => '160-0001',
                'address' => '東京都新宿区新宿3-3-3',
                'building' => 'デモタワー303',
                'image_file' => 'にこにこ紫.jpg',
            ],
        ];

        DB::table('profiles')->truncate();

        $insertProfiles = [];
        foreach ($profiles as $profile) {
            $srcPath = $sourceDir . DIRECTORY_SEPARATOR . $profile['image_file'];
            $destFileName = $profile['image_file'];
            // 画像をstorageにコピー
            if (file_exists($srcPath)) {
                $storageDisk->putFileAs(
                    $storageDir,
                    new \Illuminate\Http\File($srcPath),
                    $destFileName
                );
            }
            // DBにはstorage用のパスを登録
            $insertProfiles[] = [
                'user_id' => $profile['user_id'],
                'postal_code' => $profile['postal_code'],
                'address' => $profile['address'],
                'building' => $profile['building'],
                'image_path' => $storageDir . '/' . $destFileName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('profiles')->insert($insertProfiles);
    }
}
