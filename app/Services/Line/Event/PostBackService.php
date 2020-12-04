<?php

namespace App\Services\Line\Event;

use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;

class PostBackService
{

    /**
     * @param $bot
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

    public function execute($event)
    {
        $post_back_params = [];
        $post_back = $event->getPostbackData();
        $post_back = explode('&', $post_back);
        foreach($post_back as $post){
            $split_post = explode('=', $post);
            $post_back_params[$split_post[0]] = $split_post[1];
        }
        
        $user = $event->getUserId();
        $group = $event->getGroupId();

    }
}