<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use App\Models\Event as EventModel;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;

class StartService
{
    /**
     * @var $bot
     */
    private $imageUrl;
    private $title;
    private $group_id;
    private $deploy_url;

    /**
     * constructor
     * @param $event_name
     * @param $event_id
     * @param $group_id
     */
    public function __construct($group_id)
    {
        $this->title = 'watte';
        $this->imageUrl = '/images/logo.png';
        $this->group_id = $group_id;
        $this->deploy_url = config('app.deploy_url');
    }

    /**
     * 割り勘をスタートする
     */
    public function execute()
    {
        $columns = [];

        $start_button = new UriTemplateActionBuilder('新規割り勘スタート', 'https://liff.line.me/1655325455-B5Zjk37g');
        $detail_button = new UriTemplateActionBuilder('イベント一覧を確認する', 'https://liff.line.me/1655325455-B5Zjk37g/event/list');
        $privacy_button = new PostBackTemplateActionBuilder('watteについて', 'action=detail');

        //新規イベントスタート
        $start_event_button = $this->generateColumn('新しく割り勘を始める', '開始ボタンを押して割り勘をスタートしましょう', $this->deploy_url . $this->imageUrl, [$start_button, $detail_button, $privacy_button]);

        $events = EventModel::where('group_id', $this->group_id)->where('is_archived', false)->orderBy('created_at', 'desc')->limit(5)->get();

        foreach($events as $event){
            if($event->file_path){
                $image = $this->deploy_url . '/' . $event->file_path;
            }else{
                $image = $this->deploy_url . $this->imageUrl;
            }
            $add_button = new UriTemplateActionBuilder('支払いを追加', 'https://liff.line.me/1655325455-B5Zjk37g/amounts/add/' . $event->id);
            $check_button = new UriTemplateActionBuilder('割り勘状況を確認', 'https://liff.line.me/1655325455-B5Zjk37g/amounts/show/' . $event->id);
            $setting_button = new UriTemplateActionBuilder('設定/その他', 'https://liff.line.me/1655325455-B5Zjk37g/setting/' . $event->id);
            $column = $this->generateColumn($event->event_name, '各イベントの割り勘追加、確認ができます', $image, [$add_button, $check_button, $setting_button]);
            array_push($columns, $column);
        }

        array_push($columns, $start_event_button);

        $carousel_template = new CarouselTemplateBuilder($columns);
        $message = new TemplateMessageBuilder($this->title, $carousel_template);
        
        return $message;
    }

    public function generateColumn($title, $text, $url, $actions)
    {
        $column = new CarouselColumnTemplateBuilder($title, $text, $url, $actions);

        return $column;
    }
}