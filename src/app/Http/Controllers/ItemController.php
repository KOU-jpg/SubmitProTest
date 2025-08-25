<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Comment;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{
//商品一覧ページ表示
    public function index(Request $request)
    {
        $userId = auth()->id();
        $keyword = $request->input('keyword');
        $page = $request->query('page', 'recommend');

        // 共通検索条件（テーブル名を明示）
        $applyConditions = function ($query) use ($userId, $keyword) {
            $query->where('items.user_id', '!=', $userId) // ← ここを修正
                ->when($keyword, function ($q) use ($keyword) {
                    $q->where(function ($subQ) use ($keyword) {
                        $subQ->where('items.name', 'like', "%{$keyword}%") // ← テーブル名追加
                            ->orWhereHas('categories', function ($categoryQ) use ($keyword) {
                                $categoryQ->where('name', 'like', "%{$keyword}%");
                            });
                    });
                });
        };

        // タブ切り替え処理
        if ($page === 'mylist' && auth()->check()) {
            $items = auth()->user()->favorites()
                ->with('images', 'categories')
                ->where($applyConditions)
                ->latest('items.created_at') // ← テーブル名追加
                ->get();
        } else {
            $items = Item::with('images', 'categories')
                ->where($applyConditions)
                ->latest()
                ->get();
        }

        return view('items.index', compact('items', 'keyword', 'page'));
    }

//商品詳細ページを表示

    public function detail(Item $item_id)
    {
        // 商品の関連データを事前にロード
        $item_id->load([
            'images',
            'categories',
            'condition',
            'user.profile',
        ]);

        // コメントと、そのユーザー＆ユーザーのプロフィールをまとめてロード
        $comments = $item_id->comments()
            ->with(['user.profile'])
            ->oldest()
            ->get();

        return view('items.detail', [
            'item' => $item_id,
            'comments' => $comments
        ]);
    }

//コメント投稿処理
    public function store_comment(CommentRequest $request)
    {

        // コメント保存
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $request['item_id'],
            'comment' => $request['comment']
        ]);

        // 元のページにリダイレクト
        return redirect()->route('items.detail', $request['item_id'])
                         ->with('success', 'コメントを投稿しました');
    }



//出品取り消し  
    public function cancel($item_id)
    {
        // 該当商品取得
        $item = Item::findOrFail($item_id);

        // 取引状態が「trading」以外のときのみキャンセル可能とする
        if ($item->status === 'trading') {
            return redirect()->back()->with('error', '取引中の出品は取り消せません。');
        }

        // 商品削除（物理削除）
        $item->delete();

        return redirect()->route('mypage');
    }  

// 評価フォーム表示
public function showRatingForm(Item $item)
{
    // 購入者でなければ403エラー
    if ($item->buyer_id !== auth()->id()) {
        abort(403);
    }

    return view('items.rate', compact('item'));
}

// 評価送信処理
    public function rateSeller(Request $request, Item $item)
    {
        $user = auth()->user();
        $ratingValue = $request->input('rating');

        // バリデーション（1～5の整数かどうか）
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // 取引相手ユーザーIDを判定
        if ($item->user_id === $user->id) {
            // ログインユーザーが出品者なら購入者が評価対象
            $rateeId = $item->buyer_id;
        } elseif ($item->buyer_id === $user->id) {
            // ログインユーザーが購入者なら出品者が評価対象
            $rateeId = $item->user_id;
        } else {
            // 取引当事者でない場合はエラー
            abort(403, '取引当事者ではありません。');
        }

        // 評価保存（評価者はログインユーザー、被評価者は取引相手）
        \App\Models\Rating::updateOrCreate(
            [
                'rater_id' => $user->id,
                'ratee_id' => $rateeId,
                'item_id' => $item->id,
            ],
            [
                'score' => $ratingValue,
            ]
        );

        // 取引を完了状態に更新
        $item->status = 'completed';
        $item->save();

        return redirect()->route('items.index');
    }

}






