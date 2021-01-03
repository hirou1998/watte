<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\LineFriend;

class ParticipantController extends Controller
{
    public function index(Event $event)
    {
        $participants = $event->line_friends()->orderBy('created_at', 'asc')->get();
        $liff = config('line.liff');
        
        return view('participants', ['participants' => $participants, 'event' => $event, 'liff' => $liff]);
    }

    public function update(LineFriend $friend, Request $request)
    {
        $event_id = $request->event;
        $line_id = $friend->line_id;

        //$targetUser = $friend->where('line_id', $line_id)->get()->first();
        $friend->update([
            'display_name' => $request->display_name,
            'picture_url' => $request->picture_url
        ]);
    }
}
