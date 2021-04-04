<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends UuidModel
{
    use HasFactory;

    protected $fillable = ['event_name', 'creator_id', 'group_id', 'notification', 'file_name', 'file_path', 'is_archived'];

    public function line_friends()
    {
        return $this->belongsToMany(
            LineFriend::class,
            'event_line_friend',
            'event_id',
            'line_friend_id'
        )->withPivot('ratio')->orderby('pivot_line_friend_id', 'desc');
    }

    public function group()
    {
        return $this->belongsTo(
            Group::class,
        );
    }

    public function amounts()
    {
        return $this->hasMany(
            Amount::class,
        );
    }

    public function transactions()
    {
        return $this->hasMany(
            Transaction::class
        );
    }

    public static function getEventListByGroupId($group_id)
    {
        $events = static::where('group_id', $group_id)->with('amounts')->with('line_friends')->orderby('created_at', 'desc')->get();
        $event_list_with_sum = array();
        foreach($events as $event){
            $sum = static::getTotalExpenditure($event->amounts);
            $participantsLength = count($event->line_friends);
            $event->sum = $sum;
            $event->participants = $participantsLength;
            array_push($event_list_with_sum, $event);
        }
        return $event_list_with_sum;
    }

    public static function getTotalExpenditure($amount_list)
    {
        $total = 0;
        foreach($amount_list as $amount){
            $total += $amount->amount;
        }
        return $total;
    }
}
