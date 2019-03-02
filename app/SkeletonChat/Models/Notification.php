<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
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

    public static function createAcceptedNotification($by_id, $to_id)
    {
        return static::create([
            'type' => static::TYPE_ACCEPTED,
            'by_id' => $by_id,
            'to_id' => $to_id
        ]);
    }

    public static function createRequestedNotification($by_id, $to_id)
    {
        return static::create([
            'type' => static::TYPE_REQUESTED,
            'by_id' => $by_id,
            'to_id' => $to_id
        ]);
    }
}
