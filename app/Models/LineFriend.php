<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineFriend extends Model
{
    use HasFactory;

    protected $fillable = ['line_id', 'display_name', 'picture_url', 'is_blocked'];
    protected $primaryKey = 'line_id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function events()
    {
        return $this->belongsToMany(
            Event::class, 
            'event_line_friend',
            'line_friend_id',
            'event_id'
        );
    }

    public function amounts()
    {
        return $this->hasMany(
            Amount::class
        );
    }

    public static function isFollowed($line_id)
    {
        $friend = static::getFriend($line_id);
        return $friend ? true : false;
    }

    public static function block($line_id)
    {
        $friend = static::getFriend($line_id);
        if($friend === null){
            return false;
        }else{
            $friend->update(['is_blocked' => 1]);
            return true;
        }
    }

    public static function unblock($line_id)
    {
        $friend = static::getFriend($line_id);
        if($friend === null){
            return false;
        }else{
            $friend->update(['is_blocked' => 0]);
            return true;
        }
    }

    public static function getFriend($line_id)
    {
        $friend = static::where('line_id', $line_id)->get()->first();
        return $friend ?: null;
    }
}
