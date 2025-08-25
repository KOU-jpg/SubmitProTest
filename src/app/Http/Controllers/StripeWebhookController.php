<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCompleteMail;


class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $event = \Stripe\Webhook::constructEvent(
            $request->getContent(),
            $request->header('Stripe-Signature'),
            config('services.stripe.webhook_secret')
        );

        // カード・コンビニ共通
        if ($event->type === 'checkout.session.completed') {
            $this->handleSessionCompleted($event->data->object);
        }

        // コンビニ支払い成功
        if ($event->type === 'checkout.session.async_payment_succeeded') {
            $this->handleAsyncPaymentSucceeded($event->data->object);
        }

        // 支払い期限切れ
        if ($event->type === 'checkout.session.expired') {
            $this->handleSessionExpired($event->data->object);
        }

        return response()->json(['status' => 'handled']);
    }

    protected function handleSessionCompleted($session)
    {
        $itemId = $session->metadata['item_id'] ?? null;
        $buyerId = $session->metadata['buyer_id'] ?? null;

        if (!$itemId || !$buyerId) return;

        $item = Item::find($itemId);
        if (!$item) return;

        $item->update(['buyer_id' => $buyerId]);

        $paymentMethod = $session->payment_method_types[0] ?? 'card';

        if ($paymentMethod === 'card') {
            // カード払いはこの時点でpaid
            $this->finalizePayment($item);
        } elseif ($paymentMethod === 'konbini') {
            // コンビニはpendingで待機
            $this->setKonbiniPending($item);
        }
    }

    protected function handleAsyncPaymentSucceeded($session)
    {
        $itemId = $session->metadata['item_id'] ?? null;
        if (!$itemId) return;

        $item = Item::find($itemId);
        if (!$item) return;

        // コンビニ支払いが完了したらpaidにする
        $this->finalizePayment($item);
    }

    protected function handleSessionExpired($session)
    {
        $itemId = $session->metadata['item_id'] ?? null;
        if (!$itemId) return;

        $item = Item::find($itemId);
        if (!$item) return;

        // 支払い期限切れ
        $item->update([
            'payment_status' => 'expired',
            'payment_expiry' => null,
            'status' => 'expired',
        ]);
    }

    protected function finalizePayment(Item $item)
    {
        // ステータス更新
        $item->update([
            'sold_at' => now(),
            'payment_status' => 'paid',
            'payment_expiry' => null,
            'status' => 'trading',
        ]);

        // 取引相手（購入者）情報取得
        $buyer = $item->buyer;
        if (!$buyer) {
            // 購入者がまだセットされていなければ処理中断
            return;
        }

        // 出品者情報取得
        $seller = $item->user;

        // 取引完了メール送信（出品者宛、または必要に応じて購入者宛も検討）
        Mail::to($seller->email)->send(new TransactionCompleteMail($item, $buyer, $seller));
    }

    protected function setKonbiniPending(Item $item)
    {
        $item->update([
            'payment_status' => 'pending',
            'payment_expiry' => now()->addDays(3),
            'status' => 'trading',
        ]);
    }
}
