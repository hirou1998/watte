<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LineFriend;

class LineFriendController extends Controller
{
    public function isRegistered(Request $request, LineFriend $friend)
    {
        $id = $request->id;
        $user = $friend->where('line_id', $id)->get()->first();
        return $user;
    }
}