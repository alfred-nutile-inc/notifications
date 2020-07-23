<?php

namespace AlfredNutileInc\Notifications;

use Illuminate\Database\Eloquent\Model;

class NotificationCategory extends Model
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
