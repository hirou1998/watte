<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LineFriend;
use App\Models\Group;

class LineFriendGroupController extends Controller
{
    /**
     * @param
     */

    /**
     * construct
    */
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $line_id = $request->lineId;
        $group_id = $request->groupId;
        $user_result = $this->isUserRegistered($line_id);
        $group_result = $this->isGroupRegistered($group_id);

        if($user_result && $group_result){
            session()->regenerate();
            session()->put('line_id', $line_id);
            session()->put('group_id', $group_id);
            return true;
        }else{
            abort(401, 'Unauthorized');
        }
    }

    public function isUserRegistered($line_id)
    {
        $target = LineFriend::where('line_id', $line_id)->get()->first();

        return $target ? true : false;
    }

    public function isGroupRegistered($group_id)
    {
        $target = Group::where('group_id', $group_id)->get()->first();

        return $target ? true : false;
    }
}
