<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 2/19/15
 * Time: 9:26 AM
 */

namespace AlfredNutileInc\CoreApp\Notifications;


use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider {


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('AlfredNutileInc\CoreApp\Notifications\NotificationInterface', function($app) {
           $model = new Notification();
           return new NotificationService($model);
        });
    }
}