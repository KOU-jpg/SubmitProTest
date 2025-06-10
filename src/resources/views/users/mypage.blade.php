<!-- プロフィール画面 -->
@extends('layouts.app')

@section('title')
    mypage
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/mypage.css') }}">
@endsection

@section('content')
    <section class="profile-header">
        <div class="profile-avatar">
            @if($user->profile && $user->profile->image_path)
                <img src="{{ asset('storage/' . $user->profile->image_path) }}" alt="プロフィール画像" class="profile-image">
            @else
                <div class="profile-image-placeholder"></div>
            @endif
        </div>
        <div class="profile-info">
            <div class="profile-username">{{ $user->name }}</div>
            <button class="profile-edit-btn" onclick="location.href='{{ route('mypage.profile.edit') }}'">
                プロフィールを編集
            </button>
        </div>
    </section>

    <nav class="pages">
        <a href="{{ route('mypage', ['page' => 'buy']) }}"
            class="page {{ ($page ?? 'buy') === 'buy' ? 'active' : '' }}">購入した商品</a>
        <a href="{{ route('mypage', ['page' => 'sell']) }}"
            class="page {{ ($page ?? '') === 'sell' ? 'active' : '' }}">出品した商品</a>
    </nav>
    <hr>

    <section class="item-list">
        @forelse($items as $item)
            <a href="{{ route('items.detail', $item->id) }}">
                <div class="item-card">
                    <div class="item-image">
                        @if(in_array($item->payment_status, ['pending', 'paid']))
                            <div class="sold-label">SOLD</div>
                        @endif
                        @if($item->images->count())
                            <img src="{{ asset('storage/' . $item->images->first()->path) }}" alt="{{ $item->name }}">
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
@endsection