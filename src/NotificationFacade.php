<?php

namespace AlfredNutileInc\Notifications;

use Illuminate\Support\Facades\Facade;

/**
 * Class NotificationFacade.
 *
 * @see NotificationService
 */
class NotificationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AlfredNutileInc\Notifications\NotificationInterface';
    }
}
