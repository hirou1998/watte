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
        $userToken = $request->bearerToken();
        $sessionToken = session()->get('line_id');

        if($userToken && $userToken == $sessionToken){
            $transactions = $transaction::where('event_id', $event->id)->where('approved', true)->get();

            return $transactions;
        }else{
            abort(401, 'Unauthorized');
        }
    }
}
