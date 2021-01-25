<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class ParitipantController extends Controller
{
    public function index(Event $event, Request $request)
    {
        $userToken = $request->bearerToken();

        if($userToken && $userToken == session()->get('_token')){
            $participants = $event->line_friends()->orderBy('created_at', 'asc')->get();

            if($participants->isEmpty()){
                abort(404, 'Not Found');
            }else{
                return $participants;
            }
        }else{
            abort(401, 'Unauthorized');
        }
    }
}
