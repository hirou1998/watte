<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineFriend;

class LineFriendCotnroller extends Controller
{
    public function checkNotShowInfo(LineFriend $friend)
    {
        $friend->update([
            'private_notification_flg' => true
        ]);
        return $friend;
    }

    public function update(LineFriend $friend, Request $request)
    {
        logger([$friend]);

        if($friend){
            $friend->update([
                'display_name' => $request->display_name,
                'picture_url' => $request->picture_url
            ]);
            return $friend;
        }else{
            abort(404, 'Not Found');
        }
    }
}
