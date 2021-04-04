<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Event;

class GroupController extends Controller
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

    /**
     * イベント一覧ページ
     */
    public function index()
    {
        return view('eventList', [
            'liff' => $this->liff,
            'deploy_url' => $this->deploy_url,
        ]);
    }
}
