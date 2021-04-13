<?php

namespace App\Services\Line\Event;

use App\Models\LineFriend;
use App\Models\Event as EventModel;
use App\Models\LineFriend as LineFriendModel;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;

class EventsListService
{
    /**
     * @var $bot
     */
    private $imageUrl;
    private $title;
    private $line_id;
    private $deploy_url;

    /**
     * constructor
     * @param $event_name
     * @param $event_id
     * @param $group_id
     */
    public function __construct($line_id)
    {
        $this->title = 'watte';
        $this->imageUrl = '/images/logo.png';
        $this->line_id = $line_id;
        $this->deploy_url = config('app.deploy_url');
    }

    /**
     * 自分が参加しているイベント一覧を返す
     */
    public function execute()
    {
        $columms = [];

        $events = LineFriendModel::where('line_id', $this->line_id)->get()->first()->events->where('is_archived', false);
        $text = '';
        foreach($events as $event){
            $text = $text . $event->event_name;
        }
        return $text;
    }

    public function generateColumn($title, $text, $url, $actions)
    {
        $column = new CarouselColumnTemplateBuilder($title, $text, $url, $actions);

        return $column;
    }
}