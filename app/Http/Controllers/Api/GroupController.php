<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Event;

class GroupController extends Controller
{
    public function showEventList(Group $group, Request $request)
    {
        $userToken = $request->bearerToken();
        $sessionToken = session()->get('line_id');

        if($userToken && $userToken == $sessionToken){
            $group_id = $group->group_id;
            $events = Event::getEventListByGroupId($group_id);
            return $events;
        }else{
            abort(401, 'Unauthorized');
        }
    }
}
