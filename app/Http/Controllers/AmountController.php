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

        return view(
            'amounts', 
            [
                'event' => $event, 
                'participants' => $participants, 
                'liff' => $this->liff, 
                'deploy_url' => $this->deploy_url
            ]
        );
    }

    public function delete(Amount $amount)
    {
        $amount->deals->delete();
        $amount->delete();

        return response()->json([]);
    }

    public function update(Amount $amount, Request $request)
    {
        $amount->update([
            'amount' => $request->amount,
            'note' => $request->note
        ]);
        return $amount;
    }

    public function archive(Amount $amount)
    {
        $amount->update(['archive_flg' => true]);

        return $amount;
    }

    public function unarchive(Amount $amount)
    {
        $amount->update(['archive_flg' => false]);

        return $amount;
    }
}
