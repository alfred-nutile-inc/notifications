<?php

namespace AlfredNutileInc\Notifications\Events;

use AlfredNutileInc\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use App\Events\Event as Event;

class NotificationSent extends Event
{
    use SerializesModels;

    /**
     * @var Notification
     */
    protected $notification;

    /**
     * Create a new event instance.
     * 
     * @param Notification $notification
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function handle()
    {

    }
    
    /**
     * @return Notification
     */
    public function getTarget()
    {
        return $this->notification;
    }
}
