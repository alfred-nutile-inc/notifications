<?php  namespace  AlfredNutileInc\DiffTool\Notifications;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class NotificationsPusherListener extends ServiceProvider {


    protected function sendNotification($event)
    {
        Log::info("Pusher Notify");
        Log::info(print_r($event, 1));
    }

    public function register()
    {
        // TODO: Implement register() method.
    }


    public function boot()
    {
        $this->app['events']->listen('notifications.*', function($event){
            $this->sendNotification($event);
        });
    }


}