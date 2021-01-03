<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Amount;
use App\Models\LineFriend;

class AmountController extends Controller
{
    public function index(Event $event, Request $request)
    {
        $liff = config('line.liff');

        $participants = $event->line_friends;
        return view('add', compact('event', 'liff', 'participants'));
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

        //$ratio = $event->line_friends

        return view('amounts', compact('amount_lists', 'event', 'each_calc_amount'));
    }
}
