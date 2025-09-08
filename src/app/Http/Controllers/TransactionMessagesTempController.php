<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionTempMessages;

class TransactionMessagesTempController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'message' => 'nullable|string',
        ]);

        $temp = TransactionTempMessages::updateOrCreate(
            ['user_id' => $request->user_id, 'item_id' => $request->item_id],
            ['message' => $request->message]
        );

        return response()->json(['success' => true, 'data' => $temp]);
    }
}
