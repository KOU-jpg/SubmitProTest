@component('mail::message')
# 取引完了のお知らせ

「{{ $item->name }}」の取引が完了しました。

購入者：{{ $buyer->name }}  
出品者：{{ $seller->name }}

ありがとうございました。

@endcomponent
