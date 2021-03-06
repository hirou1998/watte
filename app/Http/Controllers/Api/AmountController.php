<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Amount;

class AmountController extends Controller
{
    public function lists(Event $event, Amount $amount, Request $request)
    {
        $userToken = $request->bearerToken();
        $sessionToken = session()->get('line_id');

        if($userToken && $userToken == $sessionToken){
            $amount_lists = $event
                ->amounts()
                ->with('line_friend')
                ->orderBy('archive_flg', 'asc')
                ->orderBy('created_at', 'desc')
                ->with('deals')
                ->get();
            $eachUserData = $amount->getEachUserAmount($event->id);
            return [
                'amount_lists' => $amount_lists,
                'each' => $eachUserData
            ];
        }else{
            abort(401, 'Unauthorized');
        }
    }
}
