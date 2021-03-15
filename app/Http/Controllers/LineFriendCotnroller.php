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
}
