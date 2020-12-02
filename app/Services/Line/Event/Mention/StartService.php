<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
//use LINE\LINEBot\Event\UnfollowEvent;

class StartService{
    /**
     * @var $bot
     */
    private $bot;

    /**
     * constructor
     * @param LINEBot $bot
     */
    public function __construct(LINEBot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * 割り勘をスタートする
     */
    public function execute($event_name, $event_id)
    {
        $text = '[' . $event_name . '] の割り勘に参加しますか？';
        $event_id = $event_id ?: 'igorajoajpa';
        $actions = [
            [
                'type' => 'postback',
                'label' => '参加',
                'data' => 'action=join&id=' . $event_id,
                'text' => '参加'
            ],
            [
                'type' => 'postback',
                'label' => '不参加',
                'data' => 'action=ignore&id=' . $event_id,
                'text' => '不参加'
            ],
        ];
        $button_template = new ButtonTemplateBuilder($text, $actions);
        $message = $button_template->buildTemplate();
        return $message;
    }
}