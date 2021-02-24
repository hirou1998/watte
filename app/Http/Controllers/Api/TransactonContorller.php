<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;

class TransactonContorller extends Controller
{
    public function getApprovedTransactionByEventId(Event $event, Transaction $transaction, Request $request)
    {
        $transactions = $transaction::where('event_id', $event->id)->where('approved', true)->get();

        return $transactions;


        $userToken = $request->bearerToken();

        if($userToken && $userToken == session()->get('line_id')){
            $transactions = $transaction::where('event_id', $event->id)->where('approved', true)->get();

            return $transactions;
        }
    }
}
