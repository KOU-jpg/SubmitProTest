<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Rating;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
// マイページを表示
public function show(Request $request)
{
    $user = auth()->user();
    $page = $request->query('page', 'buy'); // デフォルトは「購入した商品」

    // 取引関連の商品を全部取得（売り手・買い手）
    $sellingItems = Item::where('user_id', $user->id)
        ->with('images', 'transactionMessages')
        ->latest()
        ->get();

    $buyingItems = Item::where('buyer_id', $user->id)
        ->with('images', 'transactionMessages')
        ->latest()
        ->get();

    // 売り手側・買い手側の全アイテムをマージ
    $allRelatedItems = $sellingItems->merge($buyingItems);

    // 取引中の商品だけ別取得（status = trading の商品）
    $sellingTrading = $sellingItems->filter(function ($item) {
        return in_array($item->status, ['trading', 'completed']);
    });
    $buyingTrading = $buyingItems->filter(function ($item) {
        return in_array($item->status, ['trading', 'completed']);
    });
    $tradingItems = $sellingTrading->merge($buyingTrading);

    // 未読メッセージ数計算（全部）
    foreach ($allRelatedItems as $item) {
        $lastAccess = $item->buyer_id === $user->id
            ? $item->last_buyer_access
            : $item->last_seller_access;

        if (is_null($lastAccess)) {
            $lastAccess = \Carbon\Carbon::create(2000, 1, 1, 0, 0, 0);
        } else {
            $lastAccess = \Carbon\Carbon::parse($lastAccess);
        }

        $item->unread_count = $item->transactionMessages
            ->filter(function ($msg) use ($lastAccess, $user) {
                return $msg->sent_at > $lastAccess && $msg->user_id !== $user->id;
            })
            ->count();
    }

    // 表示する商品をページごとに振り分け
    if ($page === 'buy') {
        // 買った商品だけ表示
        $items = $allRelatedItems->where('buyer_id', $user->id);
    } elseif ($page === 'sell') {
        // 売った商品だけ表示
        $items = $allRelatedItems->where('user_id', $user->id);
    } elseif ($page === 'trade') {
        // 取引中の商品表示
        $items = $tradingItems;
    } else {
        $items = $allRelatedItems->where('buyer_id', $user->id);
    }

    // 評価の平均値計算（ratee_id = $user->id の平均）
    $averageRating = Rating::where('ratee_id', $user->id)->avg('score');
    // 評価件数
    $ratingCount = Rating::where('ratee_id', $user->id)->count();
    $totalUnreadCount = $allRelatedItems->sum('unread_count');

    // Bladeテンプレートに渡す
    return view('users.mypage', [
        'user' => $user,
        'items' => $items,
        'page' => $page,
        'totalUnreadCount' => $totalUnreadCount,
        'averageRating' => $averageRating, 
        'ratingCount' => $ratingCount,
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
