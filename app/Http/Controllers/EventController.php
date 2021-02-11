<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Group;
use App\Models\LineFriend;
use App\Services\Line\Event\AskJoinService;
use Illuminate\Support\Facades\Storage;
use Image;

class EventController extends Controller
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

    /**
     * 割り勘開始ページ
     */
    public function index(Request $request)
    {
        return view('index', [
            'liff' => $this->liff,
            'deploy_url' => $this->deploy_url
        ]);
    }

    /**
     * イベント情報編集ページ
     */
    public function editpage(Event $event)
    {
        return view('event', [
            'liff' => $this->liff,
            'deploy_url' => $this->deploy_url,
            'event_id' => $event->id
        ]);
    }

    /**
     * イベントを作成
     * @param Event $event
     * @param Request $request
     */
    public function create(Event $event, Request $request)
    {
        logger('craete!');
        $event_name = $request->event_name;
        $group_id = $request->group_id;
        $creator_id = $request->creator_id;
        $notification = $request->notification;

        $group = Group::where('group_id', $group_id)->get()->first();

        if($request->file){
            $image_original_name = $request->file->getClientOriginalName();
            $image_name = time() . '_' . $image_original_name . '.jpg';
            $path = 'public/' . $image_name;

            $img = Image::make($request->file);
            Storage::disk('local')->put($path, $img->encode());

            $new_event = $group->events()->create([
                'event_name' => $event_name,
                'creator_id' => $creator_id,
                'notification' => $notification,
                'file_name' => $image_name,
                'file_path' => 'storage/' . $image_name
            ]);
        }else{
            $new_event = $group->events()->create([
                'event_name' => $event_name,
                'creator_id' => $creator_id,
                'notification' => $notification,
            ]);
        }

        return $new_event;
    }

    /**
     * イベント情報編集(PUT)
     */
    public function update(Event $event, Request $request)
    {
        if(!$request){
            return false;
        }
        if($request->file){
            $current_image_name = $event->file_name;
            //元の画像を削除
            Storage::disk('local')->delete('public/'. $current_image_name);

            //新しい画像を保存
            $image_original_name = $request->file->getClientOriginalName();
            $image_name = time() . '_' . $image_original_name . '.jpg';
            $path = 'public/' . $image_name;

            $img = Image::make($request->file);
            Storage::disk('local')->put($path, $img->encode());

            $event->update([
                'event_name' => $request->event_name,
                'notification' => $request->notification,
                'file_name' => $image_name,
                'file_path' => 'storage/' . $image_name
            ]);
        }else{
            $event->update([
                'event_name' => $request->event_name,
                'notification' => $request->notification,
            ]);
        }
        return $event;
    }

    /**
     * イベント参加確認(GET)
     */
    public function confirm(Request $request, Event $event)
    {
        $id = $request->input('id');
        $join = $request->input('join');
        $item = $event->where('id', $id)->get()->first();

        return view('confirm', ['item' => $item, 'join' => $join, 'liff' => $this->liff, 'deploy_url' => $this->deploy_url]);
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
        $join_object = $action_user->events->where('id', $event->id)->first();

        if($join === 'yes'){ //参加の場合
            if($join_object){
                $returnObj = [
                    'status' => 'fail',
                    'message' => 'イベント: ' . $event->event_name . 'にすでに参加しています。'
                ];
            }else{
                $action_user->events()->attach($event->id);
                $returnObj = [
                    'status' => 'success',
                    'message' => 'イベント: ' . $event->event_name . 'に参加しました。'
                ];
            }
            return $returnObj;
        }else{ //参加しない場合
            if($join_object){
                $action_user->events()->detach($event->id);
            }
            $returnObj = [
                'status' => 'success',
                'message' => 'イベント: ' . $event->event_name . 'に参加しません。'
            ];
            return $returnObj;
        }
    }
}
