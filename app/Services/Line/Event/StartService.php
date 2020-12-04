<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;

class StartService
{
    /**
     * @var $bot
     */
    private $event_name;
    private $event_id;
    private $imageUrl;
    private $title;

    /**
     * constructor
     * @param $event_name
     * @param $event_id
     */
    public function __construct($event_name, $event_id)
    {
        $this->event_name = $event_name;
        $this->event_id = $event_id;
        $this->title = 'watte';
        $this->imageUrl = null;
    }

    /**
     * 割り勘をスタートする
     */
    public function execute()
    {
        $columns = [];
        //$text = '[' . $this->event_name . '] の割り勘に参加しますか？';
        $start_button = new PostBackTemplateActionBuilder('開始', 'action=start');
        $detail_button = new PostBackTemplateActionBuilder('使い方', 'action=detail');

        //新規イベントスタート
        $start_event_button = $this->generateColumn('新規割り勘スタート', '新しく割り勘を記録するイベントを開始する', $this->imageUrl, [$start_button, $detail_button]);

        // $dummy1 = (object) array('name' => 'okinawa', 'id' => 'okinawa');
        // $dummy2 = (object) array('name' => 'hokkaido', 'id' => 'hokkaido');
        $events = [];

        foreach($events as $event){
            $add_button = new PostBackTemplateActionBuilder('支払いを追加', 'action=add&id=' . $event->id);
            $check_button = new PostBackTemplateActionBuilder('割り勘状況を確認', 'action=check&id=' . $event->id);
            $column = $this->generateColumn($event->name, '支払いを追加/確認', $this->imageUrl, [$add_button, $check_button]);
            array_push($columns, $column);
        }
        $join_button = new PostBackTemplateActionBuilder('参加', 'action=join&id=' . $this->event_id);
        $ignore_button = new PostBackTemplateActionBuilder('不参加', 'action=ignore&id=' . $this->event_id);

        array_push($columns, $start_event_button);

        $carousel_template = new CarouselTemplateBuilder($columns);
        $message = new TemplateMessageBuilder($this->title, $carousel_template);
        logger('oi');
        return $message;
    }

    public function generateColumn($title, $text, $url, $actions)
    {
        $column = new CarouselColumnTemplateBuilder($title, $text, $url, $actions);

        return $column;
    }
}