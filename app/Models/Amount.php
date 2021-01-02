<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Amount extends UuidModel
{
    use HasFactory;

    protected $fillable = ['friend_id', 'event_id', 'amount', 'note'];

    //protected $with = ['line_friend'];

    public function event()
    {
        return $this->belongsTo(
            Event::class
        );
    }

    public function line_friend()
    {
        return $this->belongsTo(
            LineFriend::class, 
            'friend_id',
            'line_id'
        );
    }

    public static function calcAmountEach($event_id)
    {
        $eachAmount = static::select(DB::raw(
            'friend_id, sum(amount) as amount_sum'
        ))
        ->where('event_id', $event_id)
        ->groupBy('friend_id')
        ->orderBy('amount_sum', 'desc')
        ->with(['line_friend', 'line_friend.events' => function($query) use($event_id){
            $query->where('event_id', $event_id);
        }])
        ->get();

        return $eachAmount;
    }
}
