<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Event;

class EventGroupController extends Controller
{
    public function index(Request $request)
    {
        $group_id = $request->groupId;
        $event_id = $request->eventId;
        $line_id = $request->lineId;

        logger($group_id . '/' . $event_id . '/' . $line_id);

        $result = $this->check($group_id, $event_id);

        if($result){
            session()->put('line_id', $line_id);
            session()->put('group_id', $group_id);
            return true;
        }else{
            abort(401, 'Unauthorized');
        }
    }

    public function check($group_id, $event_id)
    {
        $target = Event::where('id', $event_id)->where('group_id', $group_id)->get()->first();

        return $target ? true : false;
    }
}
