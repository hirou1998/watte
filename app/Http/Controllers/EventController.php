<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Group;
use App\Models\LineFriend;
use App\Services\Line\Event\AskJoinService;

class EventController extends Controller
{
    public function index()
    {
        $liff = config('app.liff');

        return view('index', [
            'liff' => $liff
        ]);
    }

    /**
     * イベントを作成
     * @param Event $event
     * @param Request $request
     */
    public function create(Event $event, Request $request)
    {
        $event_name = $request->event_name;
        $group_id = $request->group_id;
        $creator_id = $request->creator_id;

        $group = Group::where('group_id', $group_id)->get()->first();

        $new_event = $group->events()->create([
            'event_name' => $event_name,
            'creator_id' => $creator_id
        ]);

        return $new_event;
    }

    /**
     * イベント参加確認(GET)
     */
    public function confirm(Request $request, Event $event)
    {
        $liff = config('app.liff');
        $id = $request->input('id');
        $join = $request->input('join');
        $item = $event->where('id', $id)->get()->first();

        if($join === 'yes'){
            return view('join', compact('item', 'join', 'liff'));
        }else{
            return view('nojoin', compact('item', 'join', 'liff'));
        }
    }
    /**
     * イベントの参加確認(POST)
     * @param Event $event
     * @param LineFriend $friend
     * @param Request $request
     */
    public function register_confirm(Event $event, LineFriend $friend, Request $request)
    {
        $line_id = $request->userId;
        $action_user = $friend->where('line_id', $line_id)->where('is_blocked', false)->get()->first();

        if(!$action_user){ //友達登録済みでないorブロックされている
            $returnObj = [
                'status' => 'fail',
                'message' => 'watteを友達登録もしくはブロック解除してからご利用ください。'
            ];
            return $returnObj;
        }

        $join = $request->join;
        if($join === 'yes'){ //参加の場合
            $action_user->events()->attach($event->id);
            $returnObj = [
                'status' => 'success',
                'message' => $event->event_name . 'に参加しました。'
            ];
            return $returnObj;
        }else{ //参加しない場合
            $returnObj = [
                'status' => 'success',
                'message' => $event->event_name . 'に参加しません。'
            ];
            return $returnObj;
        }
    }
}
