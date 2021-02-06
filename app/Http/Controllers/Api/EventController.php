<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index(Event $event, Request $request)
    {
        return $event;
        // $userToken = $request->bearerToken();
        // $sessionToken = session()->get('line_id');
        // if($userToken && $userToken == $sessionToken){
        //     if($event){
        //         return $event;
        //     }else{
        //         abort(404, 'Not Found');
        //     }
        // }else{
        //     abort(401, 'Unauthorized');
        // }
    }
}
