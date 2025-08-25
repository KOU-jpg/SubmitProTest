<!-- プロフィール編集画面 -->
@extends('layouts.app')

@section('title')
    edit_profile
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/edit.css') }}">
@endsection

@section('content')
@include('components.header')
<main>
    <div class="profile-container">
        <h2>プロフィール設定</h2>
        <form method="POST" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data" novalidate>
            @csrf
            <!-- プロフィール画像 -->
            <div class="profile-image-area">
                <div class="profile-image-wrapper">
                    @if(optional($user->profile)->image_path)
                        <img src="{{ asset('storage/' . $user->profile->image_path) }}" alt="プロフィール画像" class="profile-image">
                    @else
                        <div class="profile-image-placeholder"></div>
                    @endif
                </div>
                <div class="select-image-wrapper">
                    <label class="select-image-btn">
                        画像を選択する
                        <input type="file" name="profile_image" accept="image/*" hidden>
                    </label>
                    <div class="error-message">
                        @error('profile_image')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 入力欄 -->
            <div class="form-group">
                <label for="name">
                    ユーザー名
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}">
                <label for="postal_code">
                    郵便番号
                    @error('postal_code')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" id="postal_code" name="postal_code"
                    value="{{ old('postal_code', $user->profile->postal_code ?? '') }}">
                <label for="address">
                    住所
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" id="address" name="address" value="{{ old('address', $user->profile->address ?? '') }}">
                <label for="building">
                    建物名
                    @error('building')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" id="building" name="building"
                    value="{{ old('building', $user->profile->building ?? '') }}">
            </div>
            <button type="submit" class="update-btn">更新する</button>
        </form>
    </div>
</main>
@endsection