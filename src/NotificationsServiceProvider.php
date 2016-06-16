<?php

namespace AlfredNutileInc\Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider
{
    /**
     * Service provider boot sequence.
     */
    public function boot()
    {
        $this->publishMigrations();
        $this->publishAssets();
        $this->registerRoutes();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind('AlfredNutileInc\Notifications\NotificationInterface', function ($app) {
           $model = new Notification();

           return new NotificationService($model);
        });
    }

    /**
     * Registers a publisher for the migrations.
     */
    protected function publishMigrations()
    {
        $this->publishes([
            __DIR__.'/../migrations' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Registers a publisher for assets.
     */
    protected function publishAssets()
    {
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/notifications'),
        ], 'public');
    }

    /**
     * Registers routes.
     */
    protected function registerRoutes()
    {
        if (!$this->app->routesAreCached()) {
            require __DIR__.'/routes.php';
        }
    }
}
