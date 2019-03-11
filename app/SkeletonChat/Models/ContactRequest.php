<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    protected $fillable = ["type", "by_id", "to_id", "is_read_by", "is_read_to", "is_accepted"];

    const IS_READ = 1;
    const IS_NOT_YET_READ = 0;

    const IS_ACCEPTED = 1;
    const IS_NOT_YET_ACCEPTED = 0;

    const TYPE_ACCEPTED = "accepted";
    const TYPE_REQUESTED = "requested";

    public function scopeIsNotReadBy($query)
    {
        return $query->where('is_read_by', static::IS_NOT_YET_READ);
    }

    public function scopeIsNotReadTo($query)
    {
        return $query->where('is_read_to', static::IS_NOT_YET_READ);
    }

    public function scopeAccepted($query)
    {
        return $query->where('is_accepted', static::IS_ACCEPTED);
    }

    public function scopeNotYetAccepted($query)
    {
        return $query->where('is_accepted', static::IS_NOT_YET_ACCEPTED);
    }

    public function markAsAccepted()
    {
        $this->is_accepted = static::IS_ACCEPTED;
        return $this->save();
    }

    public function markAsUnread()
    {
        $this->is_read_by = static::IS_NOT_YET_READ;
        $this->is_read_to = static::IS_NOT_YET_READ;
        return $this->save();
    }

    public static function send($by_id, $to_id)
    {
        // send contact request
        return static::create([
            'by_id' => $by_id,
            'to_id' => $to_id
        ]);
    }
}
