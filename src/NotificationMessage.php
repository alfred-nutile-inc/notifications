<?php

namespace AlfredNutileInc\Notifications;
use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'message',
    ];

    protected $hidden = ['id', 'created_at', 'updated_at'];
}
