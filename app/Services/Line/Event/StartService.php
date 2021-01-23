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

    /**
     * constructor
     * @param $event_name
     * @param $event_id
     * @param $group_id
     */
    public function __construct($group_id)
    {
        $this->title = 'watte';
        $this->imageUrl = null;
        $this->group_id = $group_id;
    }

    /**
     * 割り勘をスタートする
     */
    public function execute()
    {
        $columns = [];

        $start_button = new UriTemplateActionBuilder('開始', 'https://liff.line.me/1655325455-B5Zjk37g');
        $detail_button = new PostBackTemplateActionBuilder('使い方', 'action=detail');
        $privacy_button = new PostBackTemplateActionBuilder('プライバシーポリシー', 'action=detail');

        //新規イベントスタート
        $start_event_button = $this->generateColumn('新規割り勘スタート', '割り勘を', $this->imageUrl, [$start_button, $detail_button, $privacy_button]);

        $events = EventModel::where('group_id', $this->group_id)->orderBy('created_at', 'desc')->get();

        foreach($events as $event){
            $add_button = new UriTemplateActionBuilder('支払いを追加', 'https://liff.line.me/1655325455-B5Zjk37g/amounts/add/' . $event->id);
            $check_button = new UriTemplateActionBuilder('割り勘状況を確認', 'https://liff.line.me/1655325455-B5Zjk37g/amounts/show/' . $event->id);
            $setting_button = new UriTemplateActionBuilder('設定', 'https://liff.line.me/1655325455-B5Zjk37g/setting/' . $event->id);
            $column = $this->generateColumn($event->event_name, '支払いを追加/確認', $this->imageUrl, [$add_button, $check_button, $setting_button]);
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