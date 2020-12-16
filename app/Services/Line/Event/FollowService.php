<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use LINE\LINEBot;
use LINE\LINEBot\Event\FollowEvent;

class FollowService
{
    /**
     * @var LINEBot
     */
    private $bot;

    /**
     * Follow constructor.
     * @param LINEBot $bot
     */
    public function __construct(LINEBot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * 登録
     * @param FollowEvent $event
     * @return bool
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function execute(FollowEvent $event)
    {
        try{
            $line_id = $event->getUserId();

            $is_followed = LineFriend::isFollowed($line_id);

            if($is_followed){ //すでに友達の場合block解除
                $result = LineFriend::unblock($line_id);
                if(!$result){
                    logger()->warning('an error occured while unblocking process');
                }
            }else{ //新規の場合
                $rsp = $this->bot->getProfile($line_id);
                if(!$rsp->isSucceeded()){
                    logger()->info('failed to get profile. skip processing.');
                    return false;
                }

                $profile = $rsp->getJSONDecodedBody();
                $display_name = $profile['displayName'];

                LineFriend::create([
                    'line_id' => $line_id,
                    'display_name' => $display_name
                ]);

                //logger($display_name . '-' . $line_id);
            }

            return true;
        } catch (Exception $e){
            logger()->error($e);
            return false;
        }
    }
}