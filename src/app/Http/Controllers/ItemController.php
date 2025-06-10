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
}






