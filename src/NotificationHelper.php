<?php

namespace AlfredNutileInc\Notifications\Helpers;

use AlfredNutileInc\Notifications\NoticeCreateBuilder;
use AlfredNutileInc\Notifications\Notification;
use AlfredNutileInc\Notifications\NotificationCategory;
use AlfredNutileInc\Notifications\NotificationMessage;
use AlfredNutileInc\Notifications\Events\NotificationSent;
use Ramsey\Uuid\Uuid;
use Event;

use AlfredNutileInc\Notifications\NotificationFacade as Notify;

class NotificationHelper
{
    use UuidHelper;

    /**
     * @var array
     */
    protected $from = [];

    /**
     * @var array
     */
    protected $recipients = [];

    /**
     * @var NotificationCategory
     */
    protected $categoryObj;

    /**
     * @var NotificationMessage
     */
    protected $messageObj;

    /**
     * Finds notifications sent to a type.
     *
     * @todo Pagination and Stuff.
     *
     * @param $type
     * @param $id
     *
     * @param array $params
     */
    public static function getNotificationsTo($type, $id, $params = [])
    {
        $defaults = [
            'unread'   => true,
            'category' => null,
            'per_page'  => 10,
        ];

        $params = array_merge($defaults, $params);

        $categoryObj = null;

        if (!empty($params['category'])) {
            $categoryObj = NotificationCategory::where('name', $params['category'])->first();
        }

        $notificationQuery = Notification::where('to_type', $type)
            ->where('to_id', $id);

        if (!empty($categoryObj)) {
            $notificationQuery->where('notification_category_id', $categoryObj->id);
        }

        if ($params['unread']) {
            $notificationQuery->where('read', 0);
        }

        $notificationQuery->with(['notification_message', 'notification_category'])
            ->orderBy('created_at', 'DESC');

        return $notificationQuery->paginate($params['per_page']);
    }

    public function setCategoryByName($name, $description)
    {
        $categoryObj = NotificationCategory::where('name', $name)->first();

        if (empty($categoryObj)) {
            $categoryObj = NotificationCategory::create([
                'id' => $this->getNewUuid(),
                'name' => $name,
                'description' => $description
            ]);
        }

        $this->categoryObj = $categoryObj;

        return $this;
    }

    /**
     * Sets the From parameter.
     *
     * Type can be any model, and the ID should be the primary key of that model.
     *
     * @param string $type
     * @param string $id
     *
     * @return $this
     */
    public function setFrom($type, $id)
    {
        $this->from = [
            'type' => $type,
            'id'   => $id,
        ];

        return $this;
    }

    /**
     * Sets the intended message (Possibly passes it through translation?)
     *
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->createMessage($message);

        return $this;
    }

    /**
     * Sets the message recipients based on type and ids.
     *
     * @param $type
     * @param $ids
     *
     * @return $this
     */
    public function setRecipients($type, $ids)
    {
        if (empty($this->recipients[$type])) {
            $this->recipients[$type] = [];
        }

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $this->recipients[$type] = array_merge($this->recipients[$type], $ids);

        return $this;
    }

    /**
     * Sends out the build notification to all queued recipients.
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function sendNotifications()
    {
        if (empty($this->from) || empty($this->recipients)) {
            throw new \Exception('From or Recipients are missing.');
        }

        $sent_notifications = [];

        foreach ($this->recipients as $type => $ids) {
            foreach ($ids as $id) {
                $sent_notifications[] = $this->sendNotificationTo($id, $type);
            }
        }

        return $this;
    }

    /**
     * Sends a notification to a specific user.
     *
     * @param $whom
     * @param $who_type
     * @return mixed
     */
    protected function sendNotificationTo($whom, $who_type)
    {
        $notify_builder = new NoticeCreateBuilder(
            $to_id       = $whom,
            $to_type     = $who_type,
            $from_id     = $this->from['id'],
            $from_type   = $this->from['type'],
            $message_id  = $this->messageObj->id,
            $category_id = $this->categoryObj->id
        );

        $notification = Notify::create($notify_builder);
        
        Event::fire(new NotificationSent($notification));

        return $notification;
    }

    /**
     * Creates the message
     *
     * @todo: Make sure it's properly translated based on recipient?
     *
     * @param $message
     *
     * @return NotificationMessage
     */
    protected function createMessage($message)
    {
        $this->messageObj = NotificationMessage::create(
            [
                'id'      => $this->getNewUuid(),
                'message' => $message,
            ]
        );

        return $this;
    }
    
    /**
     * Generates and returns a new UUID as a string
     */
    protected function getNewUuid()
    {
      return Uuid::uuid4()->toString();
    }
}