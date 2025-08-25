<!-- プロフィール画面 -->
@extends('layouts.app')

@section('title')
    mypage
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/mypage.css') }}">
@endsection

@section('content')
@include('components.header')
<main>
    <section class="profile-header">
        <div class="profile-avatar">
            @if($user->profile && $user->profile->image_path)
                <img src="{{ asset('storage/' . $user->profile->image_path) }}" alt="プロフィール画像" class="profile-image">
            @else
                <div class="profile-image-placeholder"></div>
            @endif
        </div>
        <div class="profile-info">
            <div class="profile-box">
                <div class="profile-username">{{ $user->name }}</div>
                <div class="profile-rating" style="margin-top:8px;">
                    @if($ratingCount === 0)
                        <span>まだ評価がありません</span>
                    @else
                        @php
                            // 平均値を繰り上げ
                            $roundedRating = ceil($averageRating);
                        @endphp
                        {{-- 星（★）を5つ表示し、平均分だけ黄色・残りはグレー --}}
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $roundedRating)
                                <span style="color:gold;font-size: 32px;">&#9733;</span> {{-- ★ --}}
                            @else
                                <span style="color:#ccc;font-size: 32px;">&#9733;</span> {{-- ★（グレー） --}}
                            @endif
                        @endfor
                    @endif
                </div>
            </div>
            <button class="profile-edit-btn" onclick="location.href='{{ route('mypage.profile.edit') }}'">
                プロフィールを編集
            </button>
        </div>
    </section>

    <nav class="pages">
        <a href="{{ route('mypage', ['page' => 'buy']) }}"
            class="page {{ ($page ?? 'buy') === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>

        <a href="{{ route('mypage', ['page' => 'sell']) }}"
            class="page {{ ($page ?? '') === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>

        <a href="{{ route('mypage', ['page' => 'trade']) }}"
            class="page {{ ($page ?? '') === 'trade' ? 'active' : '' }}"
            style="position:relative;">
            取引中の商品

            @if(!empty($totalUnreadCount) && $totalUnreadCount > 0)
                <span class="nav-badge">
                    @if($totalUnreadCount > 99)
                        99+
                    @else
                        {{ $totalUnreadCount }}
                    @endif
                </span>
            @endif
        </a>
        
    </nav>

    <hr>

    <section class="item-list">
        @forelse($items as $item)
        @php
            if ($page === 'trade') {
                $link = route('trade', $item->id);
            } else {
                $link = route('items.detail', $item->id);
            }
        @endphp

        <a href="{{ $link }}">
            <div class="item-card">
                <div class="item-image">
                    @if(in_array($item->payment_status, ['pending', 'paid']))
                        <div class="sold-label">SOLD</div>
                    @endif
                    @if($item->images->count())
                        <img src="{{ asset('storage/' . $item->images->first()->path) }}" alt="{{ $item->name }}">
                            @if($item->unread_count > 0)
                                <div class="notification-badge">
                                    @if($item->unread_count > 99)
                                        99+
                                    @else
                                        {{ $item->unread_count }}
                                    @endif
                                </div>
                            @endif
                    @else
                        <img src="{{ asset('images/no-image.png') }}" alt="No Image">
                    @endif
                </div>
                <div class="item-name">{{ $item->name }}</div>
            </div>
        </a>
        @empty
            <div class="empty-message">
            </div>
        @endforelse
    </section>
</main>
@endsection