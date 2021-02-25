<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Event;

class TransactionController extends Controller
{
    /**
     * @param
     */
    private $liff;
    private $deploy_url;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->liff = config('line.liff');
        $this->deploy_url = config('app.deploy_url');
    }

    public function create(Event $event, Request $request)
    {
        if($request->type == 'request'){
            $sent = false;
        }else if($request->type == 'settle'){
            $sent = true;
        }else{
            abort(500, 'Server Error');
            return;
        }

        $new_transaction = $event->transactions()->create([
            'from_user' => $request->from_user,
            'to_user' => $request->to_user,
            'amount' => $request->amount,
            'type' => $request->type,
            'sent' => $sent,
            'approved' => false
        ]);

        return $new_transaction;
    }

    public function request(Transaction $transaction, Event $event, Request $request)
    {
        $participants = $event->where('id', $transaction->event->id)->get()->first()->line_friends;

        return view('request', [
            'liff' => $this->liff,
            'deploy_url' => $this->deploy_url,
            'transaction' => $transaction,
            'action_type' => $request->type,
            'participants' => $participants
        ]);
    }

    public function changeTransactionToSent(Transaction $transaction)
    {
        $transaction->update([
            'sent' => true
        ]);
        return $transaction;
    }

    public function payment(Transaction $transaction, Event $event, Request $request)
    {
        $participants = $event->where('id', $transaction->event->id)->get()->first()->line_friends;

        return view('payment', [
            'liff' => $this->liff,
            'deploy_url' => $this->deploy_url,
            'transaction' => $transaction,
            'action_type' => $request->type,
            'participants' => $participants
        ]);
    }

    public function changeTransactionToApproved(Transaction $transaction)
    {
        $transaction->update([
            'approved' => true
        ]);
        return $transaction;
    }

    public function delete(Transaction $transaction)
    {
        if($transaction){
            $transaction->delete();
            return response()->json([]);
        }else{
            abort(404, 'Not Found');
            return;
        }
    }
}
