<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    //ログアウト機能
    public function test_logout_user(){
        $user = User::find(1);
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('http://localhost/login');
        $this->assertGuest();
    }

    //ユーザ情報取得
    public function test_get_profile(){
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertSeeInOrder([
            'テストユーザー',
            '100-0001',
            '東京都千代田区千代田1-1',
            'テストビル101'
        ]);
    }

        //ユーザ情報変更
    public function test_change_profile(){
        $user = User::find(1);
        $response = $this->actingAs($user)->post('/mypage/profile', [
            'name' => "変更後ネーム",
            'postal_code' => "111-0032",
            'address' => "東京都台東区浅草2-3-1",
            'building' => "浅草寺",
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas(Profile::class, [
            'user_id' => 1,
            'postal_code' => "111-0032",
            'address' => "東京都台東区浅草2-3-1",
            'building' => "浅草寺",
        ]);
    }

    //出品情報登録
    public function test_item_listing_with_image()
    {
        // ユーザー取得（Seederで作成されていることが前提）
        $user = User::first();
        $this->assertNotNull($user, 'テストユーザーが存在すること');
        // メール認証済み設定（必要なら）
        $user->email_verified_at = now();
        $user->save();

        // ストレージ偽装（本物のファイル保存を避ける）
        Storage::fake('public');

        // ダミーの画像ファイル作成（jpeg形式を指定）
        $image = UploadedFile::fake()->create('test_item.jpg', 150);

        // 投稿フォーム送信（バリデーション項目に合わせてキーを設定）
        $response = $this->actingAs($user)->post('/sell', [
            'product_image' => $image,
            'product_name' => 'テストアイテム',
            'price' => 5000,
            'brand' => 'テストブランド',
            'description' => 'テスト説明テキスト',
            'category' => [1, 2],    // カテゴリIDの配列、DBに存在する必要あり
            'condition' => 3,        // 状態ID、DBに存在する必要あり
        ]);

        // 処理完了後はマイページにリダイレクトすることを期待
        $response->assertRedirect(route('mypage'));

        // DBに商品が登録されているか確認
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テストアイテム',
            'price' => 5000,
            'brand' => 'テストブランド',
            'description' => 'テスト説明テキスト',
            'condition_id' => 3,
        ]);

        // 画像がpublicストレージに保存されているか確認
        Storage::disk('public')->assertExists('images/item_images/' . $image->hashName());
    }


}