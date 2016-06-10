<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 2/19/15
 * Time: 9:29 AM
 */

namespace AlfredNutileInc\Notifications;

use Illuminate\Support\Facades\Facade;

/**
 * Class NotificationFacade
 * @package AlfredNutileInc\Notifications
 * @see NotificationService
 */

class NotificationFacade extends Facade {
    protected static function getFacadeAccessor() { return 'AlfredNutileInc\Notifications\NotificationInterface'; }

}