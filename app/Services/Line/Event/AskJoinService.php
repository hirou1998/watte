<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
// use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
// use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
// use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
// use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
// use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
// use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;

class AskJoinService{
    /**
     * @param $bot
     */
    private $bot;
    private $event;
    private $message;

    /**
     * constructor
     * @param LINEBot $bot
     */
    public function __construct(LINEBot $bot)
    {
        $this->bot = $bot;
        $this->event = '';
        $this->message = '';
    }

    /**
     * 作ったイベントを報告し、参加を募る
     * @param $event_name
     */
    public function execute($event)
    {
        $this->event = $event;

        $this->message = $event ? $event . 'を作成しました。' : 'イベントの作成に失敗しました。もう一度登録してください。';

        $reply_message = new TextMessageBuilder($this->message);

        $bot->replyMessage($reply_token, $reply_message);
    }
}