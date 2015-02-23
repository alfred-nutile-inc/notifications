<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 2/19/15
 * Time: 9:29 AM
 */

namespace AlfredNutileInc\CoreApp\Notifications;

use Illuminate\Support\Facades\Facade;

/**
 * Class NotificationFacade
 * @package AlfredNutileInc\CoreApp\Notifications
 * @see NotificationService
 */

class NotificationFacade extends Facade {
    protected static function getFacadeAccessor() { return 'AlfredNutileInc\CoreApp\Notifications\NotificationInterface'; }

}