<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['group_id'];
    protected $primaryKey = 'group_id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function events()
    {
        return $this->hasMany(Event::class, 'group_id');
    }

    public static function alreadyRegistered($group_id)
    {
        $item = static::where('group_id', $group_id)->get()->first();
        return $item ?: false;
    }
}
