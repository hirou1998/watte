<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Line\Event\ReceiveTextService;
use App\Services\Line\Event\FollowService;
use App\Services\Line\Event\JoinService;
use App\Services\Line\Event\BlockService;
use App\Services\Line\Event\PostBackService;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot;

class LineBotController extends Controller
{
    public function callback(Request $request)
    {

        $bot = app('line-bot');

        $signature = $_SERVER['HTTP_'.LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
        if (!LINEBot\SignatureValidator::validateSignature($request->getContent(), env('LINE_CHANNEL_SECRET'), $signature)) {
            abort(400);
        }

        $events = $bot->parseEventRequest($request->getContent(), $signature);
        foreach($events as $event){

            logger([$event]);

            $reply_token = $event->getReplyToken();
            $reply_message = 'その操作はサポートしていません。.[' . get_class($event) . '][' . $event->getType() . ']';

            switch(true){
                case $event instanceof LINEBot\Event\FollowEvent: //友達追加
                    $service = new FollowService($bot);
                    $reply_message = $service->execute($event)
                        ? new TextMessageBuilder(config('LINEBotMessage.register_message'))
                        : new TextMessageBuilder(config('LINEBotMessage.register_failed_message'));
                    break;

                case $event instanceof LINEBot\Event\MessageEvent\TextMessage: //メッセージ
                    $service = new ReceiveTextService($bot);
                    $reply_message = $service->execute($event);
                    if($reply_message){
                        $bot->replyMessage($reply_token, $reply_message);
                    }
                    break;

                case $event instanceof LINEBot\Event\MessageEvent\LocationMessage: //位置情報
                    break;
                
                case $event instanceof LINEBot\Event\JoinEvent: //グループ参加
                    $service = new JoinService($bot);
                    $reply_message = $service->execute($event);
                    break;

                case $event instanceof LINEBot\Event\PostbackEvent: //選択肢
                    $service = new PostBackService($bot);
                    $service->execute($event);
                    break;
                
                case $event instanceof LINEBot\Event\UnfollowEvent: //ブロック
                    $service = new BlockService($bot);
                    $service->execute($event);
                    break;
                
                default:
                    $body = $event->getEventBody();
                    logger()->warning('Unknown event. ['. get_class($event) . ']', compact('body'));
                
            }
            //$response = $bot->replyMessage($reply_token, $reply_message);
        }
    }
}