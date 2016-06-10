<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 2/19/15
 * Time: 9:26 AM
 */

namespace AlfredNutileInc\Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishMigrations();
        $this->registerRoutes();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('AlfredNutileInc\Notifications\NotificationInterface', function($app) {
           $model = new Notification();
           return new NotificationService($model);
        });
    }

    /**
     * Registers a publisher for the migrations
     */
    protected function publishMigrations() {
        $this->publishes([
            __DIR__.'/../migrations' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Registers a publisher for assets
     */
    protected function publishAssets()
    {
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/notifications'),
        ], 'public');
    }

    /**
     * Registers routes
     */
    protected function registerRoutes()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routes.php';
        }
    }
}