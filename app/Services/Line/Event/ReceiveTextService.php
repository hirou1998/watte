<?php

namespace App\Services\Line\Event;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\TextMessage;

class ReceiveTextService
{
    /**
     * @var LineBot
     */
    private $bot;

    /**
     * Follow constructor.
     * @param LineBot $bot
     */
    public function __construct(LineBot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * 登録
     * @param TextMessage $event
     * @return string
     */
    public function execute(TextMessage $event)
    {
        $message = $event->getText();
        $user_id = $event->getUserId();
        $is_sent_from_group = $event->isGroupEvent();
        $reply = $is_sent_from_group
            ? config('LINEBotMessage.group_reply')
            : config('LINEBotMessage.user_reply');

        return $reply;
    }
}