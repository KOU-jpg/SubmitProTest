<!-- 送付先住所変更画面 -->
@extends('layouts.app')

@section('title')
    edit_address
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/edit.css') }}">
@endsection

@section('content')
@include('components.header')
<main>
    <div class="profile-container">
        <h2>配送先住所の編集</h2>
        <form method="POST" action="{{ route('address.update', ['item_id' => $item->id]) }}"
            novalidate>
            @csrf
            <input type="hidden" name="name" value="{{ old('name', Auth::user()->name) }}">
            <input type="hidden" name="item_id" value="{{ request('item_id') }}">
            <div class="form-group">
                <label for="postal_code">
                    郵便番号
                    @error('postal_code')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" id="postal_code" name="postal_code"
                    value="{{ old('postal_code', $address->postal_code ?? '') }}">

                <label for="address">
                    住所
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" id="address" name="address" value="{{ old('address', $address->address ?? '') }}">

                <label for="building">
                    建物名
                    @error('building')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" id="building" name="building" value="{{ old('building', $address->building ?? '') }}">
            </div>
            <button type="submit" class="update-btn">更新する</button>
        </form>
    </div>
</main>
@endsection