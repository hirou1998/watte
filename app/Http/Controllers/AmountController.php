<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Amount;
use App\Models\LineFriend;
use App\Models\Deal;

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

    public function store(Event $event, Deal $deal, Request $request)
    {
        $isPrivate = $request->private;

        $new_amount = $event->amounts()->create([
            'friend_id' => $request->userId,
            'amount' => $request->amount,
            'note' => $request->note,
            'private' => $isPrivate
        ]);

        if($isPrivate){
            foreach ($request->partner as $item){
                $partner_line_id = $item['user']['userId'];
                $new_amount->deals()->create([
                    'payer' => $request->userId,
                    'partner' => $partner_line_id,
                    'amount' => $request->amount,
                    'event_id' => $event->id
                ]);
            }
        }

        return $new_amount;
    }

    public function show(Event $event, Request $request)
    {
        $participants = $event->line_friends;

        $amount_lists = $event
                            ->amounts()
                            ->with('line_friend')
                            ->orderBy('created_at', 'desc')
                            ->with('deals')
                            ->get();

        $eachUserData = Amount::getEachUserAmount($event->id);

        return view(
            'amounts', 
            [
                'amount_lists' => $amount_lists, 
                'event' => $event, 
                'each' => json_encode($eachUserData),
                'participants' => $participants, 
                'liff' => $this->liff, 
                'deploy_url' => $this->deploy_url
            ]
        );
    }

    public function test(Event $event)
    {
        $data = Amount::getEachUserAmount($event->id);

        return $data;
    }
}
