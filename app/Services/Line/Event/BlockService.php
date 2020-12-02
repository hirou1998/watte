<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use LINE\LINEBot;
use LINE\LINEBot\Event\UnfollowEvent;

class BlockService
{
    /**
     * @var $bot
     */
    private $bot;

    /**
     * block constructor
     * @param LINEBot $bot
     */
    public function __construct(LINEBot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * ブロック状態にする
     * 
     */
    public function execute($event)
    {
        $line_id = $event->getUserId();

        $result = LineFriend::block($line_id);

        if(!$result){
            logger()->warning('an error occured while blocking process');
        }
    }
}