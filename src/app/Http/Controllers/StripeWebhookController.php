<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\Item;
use Illuminate\Support\Facades\Log;


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
        $buyerId = $session->metadata['buyer_id'] ?? null; // ←追加

        if (!$itemId || !$buyerId) return;

        $item = Item::find($itemId);
        if (!$item) return;

        // 購入者IDを設定（ここが抜けていた！）
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
        ]);
    }

    protected function finalizePayment(Item $item)
    {
        $item->update([
            'sold_at' => now(),
            'payment_status' => 'paid',
            'payment_expiry' => null,
        ]);
    }

    protected function setKonbiniPending(Item $item)
    {
        $item->update([
            'payment_status' => 'pending',
            'payment_expiry' => now()->addDays(3),
        ]);
    }
}
