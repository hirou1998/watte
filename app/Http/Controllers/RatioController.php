<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class RatioController extends Controller
{
    public function update(Event $event, Request $request)
    {
        foreach($request->value as $item){
            $event->line_friends()->updateExistingPivot($item['line_id'], ['ratio' => $item['ratio']]);
        }
        return $event->line_friends()->orderBy('created_at', 'asc')->get();
    }
}
