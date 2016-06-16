<?php

namespace AlfredNutileInc\Notifications;

use AlfredNutileInc\BaseModel;

class NotificationCategory extends BaseModel
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany('AlfredNutileInc\Notifications\Notification');
    }
}
