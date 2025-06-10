<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemImageTableSeeder extends Seeder
{
    public function run()
    {
        // 保存先ディレクトリ（storage/app/public/images/item_images）
        $storageDir = 'images/item_images';
        $storageDisk = Storage::disk('public');

        // ディレクトリがなければ作成
        if (!$storageDisk->exists($storageDir)) {
            $storageDisk->makeDirectory($storageDir);
        }

        // 既存ファイル（中身だけ）を全削除
        $files = $storageDisk->files($storageDir);
        $storageDisk->delete($files);

        // サンプル画像の元ディレクトリ
        $sourceDir = base_path('/public/images/sample/item_image_samples');

        // ファイル名と商品名のマッピング
        $fileItemMapping = [
            'Armani+Mens+Clock.jpg'        => '腕時計',
            'HDD+Hard+Disk.jpg'            => 'HDD',
            'iLoveIMG+d.jpg'               => '玉ねぎ3束',
            'Leather+Shoes+Product+Photo.jpg' => '革靴',
            'Living+Room+Laptop.jpg'       => 'ノートPC',
            'Music+Mic+4632231.jpg'        => 'マイク',
            'Purse+fashion+pocket.jpg'     => 'ショルダーバッグ',
            'Tumbler+souvenir.jpg'         => 'タンブラー',
            'Waitress+with+Coffee+Grinder.jpg' => 'コーヒーミル',
            '外出メイクアップセット.jpg'      => 'メイクセット',
        ];

        // itemsテーブルから商品名でIDを引く
        $items = DB::table('items')->get()->keyBy('name');
        $images = [];

        foreach ($fileItemMapping as $fileName => $itemName) {
            if (isset($items[$itemName])) {
                $srcPath = $sourceDir . DIRECTORY_SEPARATOR . $fileName;
                if (file_exists($srcPath)) {
                    // 画像をstorage/app/public/images/item_images/にコピー
                    $storageDisk->putFileAs(
                        $storageDir,
                        new \Illuminate\Http\File($srcPath),
                        $fileName
                    );
                    // DBには images/item_images/ファイル名 で保存
                    $images[] = [
                        'item_id'    => $items[$itemName]->id,
                        'path'       => $storageDir . '/' . $fileName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // バルクインサート
        DB::table('item_images')->insert($images);
    }
}
