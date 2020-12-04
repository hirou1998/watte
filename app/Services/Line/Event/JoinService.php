<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use LINE\LINEBot;
use LINE\LINEBot\Event\JoinEvent;

class JoinService{

    /**
     * @var $bot
     */
    private $bot;

    /**
     * @param LINEBot $bot
     */
    public function __construct(LINEBot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * グループ参加
     * @param JoinEvent $event
     * @return text
     */
    public function execute(JoinEvent $event)
    {
        $group_id = $event->getGroupId();

        return config('LINEBotMessage.group_reply');
    }
}