@component('mail::message')
# 購入完了のお知らせ

「{{ $item->name }}」の購入が完了しました。

購入者：{{ $buyer->name }}  
出品者：{{ $seller->name }}

ありがとうございました。

@endcomponent
