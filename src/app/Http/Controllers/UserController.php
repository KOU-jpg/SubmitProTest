<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Profile;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
//マイページを表示
public function show(Request $request)
{
    $user = auth()->user();
    $page = $request->query('page', 'buy'); // デフォルトは「購入した商品」

    if ($page === 'buy') {
        // 購入した商品
        $items = Item::where('buyer_id', $user->id)->with('images')->latest()->get();
    } elseif ($page === 'sell') {
        // 出品した商品
        $items = Item::where('user_id', $user->id)->with('images')->latest()->get();
    } else {
        // 万が一、どちらでもなければデフォルトで「購入した商品」
        $items = Item::where('buyer_id', $user->id)->with('images')->latest()->get();
    }


        return view('users.mypage', [
            'user' => $user,
            'items' => $items,
            'page' => $page
        ]);
    }

//プロフィール変更画面を表示
    public function editProfile()
    {
        $user = auth()->user();
        return view('users.edit', compact('user'));
    }

//プロフィール変更処理
    public function updateProfile(Request $request)
    {
    $user = auth()->user();

    // ProfileRequestのバリデーションルールでチェック
    $request->validate((new ProfileRequest())->rules(), (new ProfileRequest())->messages());

    // AddressRequestのバリデーションルールでチェック
    $request->validate((new AddressRequest())->rules(), (new AddressRequest())->messages());

    DB::transaction(function () use ($request, $user) {
        $profileData = [
            'postal_code' => $request->input('postal_code'),
            'address' => $request->input('address'),
            'building' => $request->input('building'),
        ];

        // プロフィール画像の更新処理
        if ($request->hasFile('profile_image')) {
            // 既存プロフィール取得
            $profile = Profile::where('user_id', $user->id)->first();
            if ($profile && $profile->image_path) {
                // 画像ファイルが存在すれば削除
                \Storage::disk('public')->delete($profile->image_path);
            }
            $image = $request->file('profile_image');
            // 新しい画像を profile_images に保存
            $path = $image->store('images/profile_images', 'public');
            $profileData['image_path'] = $path;
        }
        // ユーザー名更新
        $user->name = $request->input('name');
        $user->save();

        // プロフィール情報更新（画像含む）
        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );
    });
    return redirect()->route('mypage');
    }
}
