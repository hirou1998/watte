<?php

namespace App\Services\Line\Event;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use App\Services\Line\Event\StartService;
use App\Services\Line\Event\EventsListService;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

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

        if(strpos($message, '@watte') !== 0){ //@watteから始まらない時は何もしない
            return false;
        }

        $user_id = $event->getUserId();
        $is_sent_from_group = $event->isGroupEvent();

        if($is_sent_from_group){
            $group_id = $event->getGroupId();
            logger($group_id);
            $start = new StartService($group_id);
            $reply = $start->execute();
        }else{
            if($message == '@watte'){
                $reply = new TextMessageBuilder(config('LINEBotMessage.user_reply'));
            }else if($message == '@watte list'){
                $line_id = $event->getUserId();
                $user_event_obj = new EventsListService($line_id);
                $event_lists = $user_event_obj->execute();
                $reply = new TextMessageBuilder($event_lists);
            }
        }

        return $reply;
    }
}