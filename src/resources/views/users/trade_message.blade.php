<!-- 取引チャット画面 -->
@extends('layouts.app')

@section('title')
    取引チャット
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/trade_message.css') }}">
@endsection

@section('content')
@include('components.header_transaction')
<main>
    <div class="trade-chat-container">
        <div class="trade-list">
            <div class="trade-list-title">その他の取引</div>
            <div class="trade-list-items">
                @forelse($items as $item)
                    <div class="trade-list-item">
                        <a href="{{ route('trade', ['item_id' => $item->id]) }}" class="trade-link">
                            {{ $item->name }}</a>

                        @if($item->unread_count > 0)
                            <div class="notification-badge">
                                @if($item->unread_count > 99)
                                    99+
                                @else
                                    {{ $item->unread_count }}
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="trade-list-item">該当する取引はありません</div>
                @endforelse
            </div>
        </div>


        <div class="b">
            <div class="trade-header">
                <div class="buyer-profile">
                    <span class="message-user-icon" style="width: 48px !important; height: 48px !important;">
                        @if(!empty($otherUser->profile->image_path))
                            <img src="{{ asset('storage/' . $otherUser->profile->image_path) }}" alt="icon" class="profile-image">
                        @else
                            <span class="no-icon-placeholder"></span>
                        @endif
                    </span>
                    <span class="trade-partner-name">「{{ $otherUser->name }}」さんとの取引画面</span>
                </div>
@if(auth()->check())
    {{-- 購入者の場合 --}}
    @if($detailItem->buyer_id === auth()->id())
        @if($detailItem->status === 'trading')
            {{-- 取引中のときのみ購入者が評価フォーム表示 --}}
            <div>
                <label for="modalToggle" class="rating-button" style="cursor:pointer;">
                    取引を完了する
                </label>
            </div>
            <input type="checkbox" id="modalToggle" style="display:none;">
            <div class="modal">
                <label for="modalToggle" class="modal-bg"></label>
                <div class="modal-box">
                    <h2>取引が完了しました。</h2>
                    <hr>
                    <p>今回の取引相手はどうでしたか？</p>
                    <form method="POST" action="{{ route('items.rating', $detailItem->id) }}">
                        @csrf
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars">&#9733;</label>
                            <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">&#9733;</label>
                            <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">&#9733;</label>
                            <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">&#9733;</label>
                            <input type="radio" id="star1" name="rating" value="1" checked><label for="star1" title="1 star">&#9733;</label>
                        </div>
                        <hr>
                        <div class="modal-sendform">
                            <button type="submit" class="send-btn">送信する</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    {{-- 出品者の場合 --}}
    @elseif($detailItem->user->id === auth()->id())
        @php
            // 購入者がこの商品に対して評価済みか判定
            $buyerHasRated = \App\Models\Rating::where('item_id', $detailItem->id)
                ->where('rater_id', $detailItem->buyer_id)
                ->exists();

            // 出品者（ログインユーザー）がこの商品に対して既に評価済みか判定
            $sellerHasRated = \App\Models\Rating::where('item_id', $detailItem->id)
                ->where('rater_id', auth()->id())
                ->exists();
        @endphp

        {{-- 購入者が評価済みかつ、出品者はまだ評価していない場合のみフォーム表示 --}}
        @if($buyerHasRated && !$sellerHasRated)
            <div>
                <label for="modalToggle" class="rating-button" style="cursor:pointer;">
                    取引を完了する
                </label>
            </div>
            <input type="checkbox" id="modalToggle" style="display:none;">
            <div class="modal">
                <label for="modalToggle" class="modal-bg"></label>
                <div class="modal-box">
                    <h2>取引が完了しました。</h2>
                    <hr>
                    <p>今回の取引相手はどうでしたか？</p>
                    <form method="POST" action="{{ route('items.rating', $detailItem->id) }}">
                        @csrf
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars">&#9733;</label>
                            <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">&#9733;</label>
                            <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">&#9733;</label>
                            <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">&#9733;</label>
                            <input type="radio" id="star1" name="rating" value="1" checked><label for="star1" title="1 star">&#9733;</label>
                        </div>
                        <hr>
                        <div class="modal-sendform">
                            <button type="submit" class="send-btn">送信する</button>
                        </div>
                    </form>
                </div>
            </div>
        @elseif($sellerHasRated)
            <p>あなたは既に評価を投稿済みです。</p>
        @endif
    @endif

@endif


            </div>

            <div class="d">
                <div class="product-image-box">
                    @if($detailItem->images->count())
                        <img src="{{ asset('storage/' . $detailItem->images->first()->path) }}" alt="{{ $detailItem->name }}">
                    @else
                        <span>商品画像なし</span>
                    @endif
                </div>
                <div class="product-main-info">
                    <div class="item-title">{{ $detailItem->name }}</div>
                    <div class="item-price">¥{{ number_format($detailItem->price) }}</div>
                </div>
            </div>
            <div class="e">
                <div class="message-list">
                @foreach($transactionMessages as $message)
                @php
                    $isMine = auth()->check() && $message->user_id === auth()->id();
                    $editId = "modalToggle-edit-{$message->id}";
                    $deleteId = "modalToggle-delete-{$message->id}";
                @endphp

                <div class="message-item {{ $isMine ? 'my-message' : 'other-message' }}">
                    <div class="message-header">
                        @if($isMine)
                            <span class="message-user-name">{{ $message->user->name }}</span>
                            <span class="message-user-icon">
                                @if(optional($message->user->profile)->image_path)
                                    <img src="{{ asset('storage/' . $message->user->profile->image_path) }}" class="profile-image">
                                @else
                                    <span class="no-icon-placeholder"></span>
                                @endif
                            </span>
                        @else
                            <span class="message-user-icon">
                                @if(optional($message->user->profile)->image_path)
                                    <img src="{{ asset('storage/' . $message->user->profile->image_path) }}" class="profile-image">
                                @else
                                    <span class="no-icon-placeholder"></span>
                                @endif
                            </span>
                            <span class="message-user-name">{{ $message->user->name }}</span>
                        @endif
                    </div>

                    <div class="message-bubble">{{ $message->message }}</div>

                    @if ($message->image_path)
                        <div class="message-image">
                            <img src="{{ asset('storage/' . $message->image_path) }}" alt="画像" style="max-width: 200px; height: auto;">
                        </div>
                    @endif

                    <div class="message-meta">
                        @if($isMine)
                            <!-- 編集トリガー -->
                            <label for="{{ $editId }}" style="margin-right: 10px;">
                                編集
                            </label>

                            <!-- 削除トリガー -->
                            <label for="{{ $deleteId }}" >
                                削除
                            </label>

                            <!-- 編集モーダル用チェックボックス -->
                            <input type="checkbox" id="{{ $editId }}" style="display:none;">

                            <!-- 削除モーダル用チェックボックス -->
                            <input type="checkbox" id="{{ $deleteId }}" style="display:none;">

                            <!-- 編集モーダル -->
                            <div class="modal modal-edit">
                                <label for="{{ $editId }}" class="modal-bg"></label>
                                <div class="modal-box">
                                    <h2>メッセージ編集</h2>
                                    <form method="POST" action="{{ route('transaction_messages.update', ['item' => $item->id, 'message' => $message->id]) }}">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="message" rows="6" style="width: 100%; resize: none; padding: 6px 12px;">{{ $message->message }}</textarea>
                                        <div class="modal-sendform" style="margin-top: 16px;">
                                            <button type="submit" class="send-btn">保存</button>
                                            <label for="{{ $editId }}" class="close-modal-btn" style="cursor: pointer; margin-left: 12px;">キャンセル</label>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- 削除モーダル -->
                            <div class="modal modal-delete">
                                <label for="{{ $deleteId }}" class="modal-bg"></label>
                                <div class="modal-box">
                                    <h2>メッセージを削除しますか？</h2>
                                    <form method="POST" action="{{ route('transaction_messages.destroy', ['item' => $item->id, 'message' => $message->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-actions">
                                            <button type="submit" class="send-btn">はい</button>
                                            <label for="{{ $deleteId }}" class="close-modal-btn">いいえ</label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach


                </div>
                <div class="g">
                    <div class="error">
                        @error('message')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <form method="POST" action="{{ route('transaction_messages.store') }}" class="comment-form-section" novalidate enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $detailItem->id }}">
                        <div class="form-row">
                            <input
                                type="text"
                                id="message"
                                name="message"
                                class="comment-input"
                                placeholder="取引メッセージを記入してください"
                                value="{{ old('message') }}"
                                autocomplete="off"
                            >
                            <input type="file" name="image" id="imageInput" accept="image/*" style="display:none;">
                            <button type="button" class="image-add-btn" onclick="document.getElementById('imageInput').click();">画像を追加</button>
                            <button type="submit" class="send-msg">
                                <!-- 紙飛行機アイコン -->
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                                    <path d="M2 25L26 14L2 3V11L18 14L2 17V25Z" stroke="#c2c2c2" stroke-width="2" fill="none"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection