<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Amount;

class AmountController extends Controller
{
    public function index(Event $event, Request $request)
    {
        return view('add');
    }
}
