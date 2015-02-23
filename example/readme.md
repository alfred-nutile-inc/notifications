In this example the Notifications are listening to an event

~~~
    public function boot()
    {
        $this->app['events']->listen('diff_tool.file_uploads_ready_to_compare', function($event){
            $this->filesUploadReadyToCompareNotice($event);
        });
    }

~~~

When that happens it will

  1 Make the notifications and messages
  2 Make a new event for Listeners
  
Then is when the Pusher listener or say Email listener do their things.

