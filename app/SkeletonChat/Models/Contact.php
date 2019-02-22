<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ["contact_id", "owner_id"];
}
