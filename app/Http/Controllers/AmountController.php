<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Amount;
use App\Models\LineFriend;

class AmountController extends Controller
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

    public function add(Event $event, Request $request)
    {
        $participants = $event->line_friends;
        return view('add', ['event' => $event, 'liff' => $this->liff, 'participants' => $participants, 'deploy_url' => $this->deploy_url]);
    }

    public function store(Event $event, Request $request)
    {
        $new_amount = $event->amounts()->create([
            'friend_id' => $request->userId,
            'amount' => $request->amount,
            'note' => $request->note
        ]);

        return $new_amount;
    }

    public function show(Event $event, Request $request)
    {
        $amount_lists = $event->amounts()->with('line_friend')->orderBy('created_at', 'desc')->get();

        $each_calc_amount = Amount::calcAmountEach($event->id);

        return view('amounts', ['amount_lists' => $amount_lists, 'event' => $event, 'each_calc_amount' => $each_calc_amount, 'liff' => $this->liff, 'deploy_url' => $this->deploy_url]);
    }
}
