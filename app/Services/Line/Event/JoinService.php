<?php

namespace App\Services\Line\Event;

use App\Models\Group;
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

        $is_already_registered = Group::alreadyRegistered($group_id);

        if($is_already_registered){

            return config('LINEBotMessage.group_reply');

        }else{

            $new_group = Group::create([
                'group_id' => $group_id
            ]);

            if($new_group){
                return config('LINEBotMessage.group_reply');
            }else{
                return '予期せぬエラーが発生しました。';
            }
        }
    }
}