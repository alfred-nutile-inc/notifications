<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 2/19/15
 * Time: 7:57 AM
 */

namespace AlfredNutileInc\CoreApp\Notifications;

use AlfredNutileInc\CoreApp\BaseModel;

class NotificationMessage extends BaseModel {

    public $incrementing = false;

    protected $fillable = [
        'id',
        'message'
    ];

    protected $hidden = ['id', 'created_at', 'updated_at'];


}