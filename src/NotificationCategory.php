<?php

namespace AlfredNutileInc\Notifications;

use Illuminate\Database\Eloquent\Model;

class NotificationCategory extends Model
{
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

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
