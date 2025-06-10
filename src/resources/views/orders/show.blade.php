<!-- 商品購入画面 -->
@extends('layouts.app')

@section('title')
    order_show
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/show.css') }}">
@endsection


@section('content')
    {{-- エラーメッセージの表示 --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form method="POST" action="{{ route('purchase.checkout', ['item_id' => $item->id]) }}" novalidate>
        @csrf
        <div class="purchase-container">
            <!-- 左カラム -->
            <section class="purchase-left">
                <div class="product-summary">
                    <div class="product-image-box">
                        @if($item->images->count())
                            <img src="{{ asset('storage/' . $item->images->first()->path) }}" alt="{{ $item->name }}">
                        @else
                            <span>画像なし</span>
                        @endif
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $item->name }}</div>
                        <div class="product-price">¥{{ number_format($item->price) }}</div>
                    </div>
                </div>
                <hr>
                <div class="payment-section">
                    <div class="title-row">
                        <div class="section-title">支払い方法</div>
                        @error('payment_method')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <select class="payment-select" name="payment_method" id="payment-select" required>
                        <option value="">選択してください</option>
                        <option value="convenience">コンビニ払い</option>
                        <option value="card">カード払い</option>
                    </select>
                </div>
                <hr>
                <div class="address-section">
                    <div class="title-row">
                        <div class="section-title">配送先</div>
                        <a href="{{ route('address.form', ['item_id' => $item->id]) }}" class="address-change">変更する</a>
                        @error('address_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="address-info">
                        〒 {{ $address->postal_code ?? 'XXX-YYYY' }}<br>
                        {{ $address->address ?? '住所を登録してください' }}<br>
                        {{ $address->building ?? '' }}
                        <input type="hidden" name="address_id" value="{{ $address->id ?? '' }}">
                    </div>
                    <hr>
                </div>
            </section>
            <!-- 右カラム -->
            <section class="purchase-right">
                <div class="order-summary">
                    <div class="order-row">
                        <span class="order-label">商品代金</span>
                        <span class="order-value">¥{{ number_format($item->price) }}</span>
                    </div>
                    <div class="order-row">
                        <span class="order-label">支払い方法</span>
                        <span class="order-value" id="summary-payment-method">選択してください</span>
                    </div>
                </div>
                @if(in_array($item->payment_status, ['pending', 'paid']))
                    <button class="purchase-btn soldout" type="button" disabled>既に購入されています</button>
                @else
                    <button class="purchase-btn" type="submit">購入する</button>
                @endif
            </section>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('payment-select');
        const summary = document.getElementById('summary-payment-method');

        function updateSummary() {
            summary.textContent = select.options[select.selectedIndex].text;
        }

        select.addEventListener('change', updateSummary);

        // ページ読込時にも反映
        updateSummary();
    });
    </script>
@endsection