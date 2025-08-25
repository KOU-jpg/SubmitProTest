<!-- 商品出品画面 -->
@extends('layouts.app')

@section('title')
    sell
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/create.css') }}">
@endsection


@section('content')
@include('components.header')
<main>
    <h1>商品の出品</h1>
    <form method="POST" action="{{ route('sell') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <section>
            <div class="form-group">
                <div class="form-header">
                    <label>商品画像</label>
                    <div class="error-message">
                        @error('product_image')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="image-upload-box">
                    <label for="product-image" class="custom-upload-btn">画像を選択する</label>
                    <input type="file" id="product-image" name="product_image" accept="image/*" hidden>
                </div>
            </div>
        </section>

        <section>
            <h2>商品の詳細</h2>
            <hr>
            <div class="form-group">
                <div class="form-header">
                    <label>カテゴリー</label>
                    <div class="error-message">
                        @error('category')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="category-tags">
                    @foreach($categories as $category)
                        <label class="category-checkbox-label">
                            <input type="checkbox" name="category[]" value="{{ $category->id }}" {{ is_array(old('category')) && in_array($category->id, old('category', [])) ? 'checked' : '' }}>
                            <span class="category-tag">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <div class="form-header">
                    <label for="condition">商品の状態</label>
                    <div class="error-message">
                        @error('condition')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <select id="condition" name="condition">
                    <option value="" {{ old('condition') == '' ? 'selected' : '' }}>選択してください</option>
                    <option value="1" {{ old('condition') == '1' ? 'selected' : '' }}>良好</option>
                    <option value="2" {{ old('condition') == '2' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="3" {{ old('condition') == '3' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="4" {{ old('condition') == '4' ? 'selected' : '' }}>状態が悪い</option>
                </select>
            </div>
            <h2>商品名と説明</h2>
            <hr>
            <div class="form-group">
                <div class="form-header">
                    <label for="product_name">商品名</label>
                    <div class="error-message">
                        @error('brand')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <input type="text" id="product_name" name="product_name" value="{{ old('product_name') }}" autocomplete="off">
            </div>
            <div class="form-group">
                <div class="form-header">
                    <label for="brand">ブランド名</label>
                    <div class="error-message">
                        @error('brand')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <input type="text" id="brand" name="brand" value="{{ old('brand') }}" autocomplete="off">
            </div>
            <div class="form-group">
                <div class="form-header">
                    <label for="description">商品の説明</label>
                    <div class="error-message">
                        @error('description')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <textarea id="description" name="description">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <div class="form-header">
                    <label for="price">販売価格</label>
                    <div class="error-message">
                        @error('price')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <input type="text" id="price" name="price" placeholder="¥" value="{{ old('price') }}" autocomplete="off">
            </div>
        </section>
        <button type="submit" class="submit-btn">出品する</button>
    </form>
</main>
@endsection