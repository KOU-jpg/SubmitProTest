<!-- ログイン画面 -->
@extends('layouts.app')

@section('title')
    login
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/login.css') }}">
@endsection

@section('content')
@include('components.header')
<main>
    <h2>ログイン</h2>
    <form action="{{ route('login') }}" method="post" novalidate>
        @csrf
        <label for="email">
            メールアドレス
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
            @error('auth.failed')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </label>
        <input type="email" id="email" name="email" value="{{ old('email') }}">
        <label for="password">
            パスワード
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </label>
        <input type="password" id="password" name="password">
        <button type="submit">ログインする</button>
    </form>
    <a class="register" href="{{ route('register')}}">会員登録はこちら</a>
</main>
@endsection