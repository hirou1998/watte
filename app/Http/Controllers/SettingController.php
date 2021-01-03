<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class SettingController extends Controller
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

    public function index(Event $event)
    {
        return view('setting', ['liff' => $this->liff, 'event' => $event, 'deploy_url' => $this->deploy_url]);
    }
}
