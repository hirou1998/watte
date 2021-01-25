<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\LineFriend;

class ParticipantController extends Controller
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

    public function index(Event $event)
    {        
        return view('participants', [
                'event' => $event, 
                'liff' => $this->liff, 
                'deploy_url' => $this->deploy_url
            ]);
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
