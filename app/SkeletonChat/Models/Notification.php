<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ["type", "from_id", "to_id", "is_read"];

    const TYPE_ACCEPTED = "accepted";
    const TYPE_REQUESTED = "requested";
}
