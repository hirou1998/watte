<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Event;

class TransactionController extends Controller
{
    public function create(Event $event, Request $request)
    {
        $new_transaction = $event->transactions()->create([
            'from_user' => $request->from_user,
            'to_user' => $request->to_user,
            'amount' => $request->amount
        ]);

        return $new_transaction;
    }
}
