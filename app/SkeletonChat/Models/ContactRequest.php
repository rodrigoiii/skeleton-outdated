<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    protected $fillable = ["type", "by_id", "to_id", "is_read_by", "is_read_to"];

    const TYPE_ACCEPTED = "accepted";
    const TYPE_REQUESTED = "requested";
    const IS_READ = 1;
    const IS_NOT_YET_READ = 0;

    public function scopeRequested($query)
    {
        return $query->where('type', static::TYPE_REQUESTED);
    }

    public function scopeAccepted($query)
    {
        return $query->where('type', static::TYPE_ACCEPTED);
    }

    public function scopeIsNotReadBy($query)
    {
        return $query->where('is_read_by', static::IS_NOT_YET_READ);
    }

    public function scopeIsNotReadTo($query)
    {
        return $query->where('is_read_to', static::IS_NOT_YET_READ);
    }

    // public static function createAcceptedNotification($by_id, $to_id)
    // {
    //     return static::create([
    //         'type' => static::TYPE_ACCEPTED,
    //         'by_id' => $by_id,
    //         'to_id' => $to_id
    //     ]);
    // }

    public static function createRequested($by_id, $to_id)
    {
        return static::create([
            'type' => static::TYPE_REQUESTED,
            'by_id' => $by_id,
            'to_id' => $to_id
        ]);
    }
}
