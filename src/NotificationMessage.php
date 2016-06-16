<?php

namespace AlfredNutileInc\Notifications;

use AlfredNutileInc\BaseModel;

class NotificationMessage extends BaseModel
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'message',
    ];

    protected $hidden = ['id', 'created_at', 'updated_at'];
}
