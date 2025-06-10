<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Models\Item;
use App\Http\Requests\PurchaseRequest;


class StripePaymentController extends Controller
{
    public function checkout(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => [$request->payment_method === 'convenience' ? 'konbini' : 'card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => $item->price,
                    'product_data' => ['name' => $item->name],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('items.index'),
            'cancel_url' => route('purchase.cancel', ['item_id' => $item->id]),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => $request->user()->id,
                'buyer_id' => auth()->id(),
            ],
        ]);

        return redirect($session->url);
    }

    public function cancel(Request $request, $item_id)
    {
        return redirect()->route('purchase.show', $item_id)
            ->with('error', '決済がキャンセルされました');
    }
}
