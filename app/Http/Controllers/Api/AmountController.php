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
        
        $userToken = $request->bearerToken();

        if($userToken && $userToken == session()->get('_token')){
            $amount_lists = $event
                ->amounts()
                ->with('line_friend')
                ->orderBy('archive_flg', 'asc')
                ->orderBy('created_at', 'desc')
                ->with('deals')
                ->get();
            return $amount_lists;
        }
    }
}
