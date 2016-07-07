<?php

namespace AlfredNutileInc\Notifications\Events;

use AlfredNutileInc\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

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

    /**
     * @return Notification
     */
    public function getTarget()
    {
        return $this->notification;
    }
}
