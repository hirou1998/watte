<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class SettingController extends Controller
{
    public function index(Event $event)
    {
        $liff = config('app.liff');
        return view('setting', ['liff' => $liff, 'event' => $event]);
    }
}
