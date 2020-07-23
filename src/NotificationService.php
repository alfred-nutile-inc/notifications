<?php

namespace AlfredNutileInc\Notifications;

use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class NotificationService implements NotificationInterface
{
    public $rulesUpdate = [];

    protected $limit = 10;
    protected $read = 0; //-1 will get all
    protected $order = 'asc';
    protected $to_id = false;
    protected $from_id = -1; //-1 will get all

    /**
     * @var Notification
     */
    private $notification_model;

    public function __construct($notification_model)
    {
        $this->notification_model = $notification_model;
    }

    /**
     * Generates a UUID object via Ramsey's UUID package.
     *
     * @return mixed
     */
    public function generateNewId()
    {
        return Uuid::uuid4();
    }

    public function create(NoticeCreateBuilder $notice_create)
    {
        $this->notification_model = new Notification();
        if ($notice_create->id) {
            $this->notification_model->id = $notice_create->id;
        } else {
            $uuid = $this->generateNewId()->toString();
            $this->notification_model->id = $uuid;
        }

        $this->notification_model->from_id = $notice_create->from_id;
        $this->notification_model->from_type = $notice_create->from_type;
        $this->notification_model->notification_message_id = $notice_create->message_id;
        $this->notification_model->to_id = $notice_create->to_id;
        $this->notification_model->to_type = $notice_create->to_type;
        $this->notification_model->notification_category_id = $notice_create->category_id;

        $this->notification_model->save();

        return $this->notification_model;
    }

    public function updateNoticesRead(array $notice_ids)
    {
        try {
            foreach ($notice_ids as $notice_id) {
                $results = $this->notification_model->findOrFail($notice_id);
                $results->read = 1;
                $results->save();
            }
        } catch (\Exception $e) {
            $message = sprintf('Error updating notices %s', $e->getMessage());
            $this->throw_and_log_error($message);
        }

        return true;
    }

    public function updateNotice($notice_id, $input)
    {
        $results = [];
        try {
            $results = $this->notification_model->findOrFail($notice_id);
            $results->update($input);
        } catch (\Exception $e) {
            $message = sprintf('Error updating notice %s', $e->getMessage());
            $this->throw_and_log_error($message);
        }

        return $results;
    }

    public function getOne($notice_id)
    {
        return $this->notification_model->where('id', $notice_id)->firstOrFail();
    }

    public function getAll()
    {
        $all = $this->notification_model
            ->with('notification_message', 'from')
            ->toId($this->getToId())
            ->read($this->getRead())
            ->take($this->getLimit())
            ->orderBy('created_at', $this->getOrder())
            ->get();

        return $all;
    }

    public function from($from_id)
    {
        return $this->notification_model
            ->with('notification_message')
            ->fromId($from_id)
            ->read($this->getRead())
            ->take($this->getLimit())
            ->orderBy('created_at', $this->getOrder())
            ->get();
    }

    public function to($to_id)
    {
        return $this->notification_model
            ->with('notification_message')
            ->toId($to_id)
            ->fromId($this->getFromId())
            ->read($this->getRead())
            ->take($this->getLimit())
            ->orderBy('created_at', $this->getOrder())->get();
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order
     */
    public function setOrder($order = 'asc')
    {
        $this->order = $order;
    }

    public function setLimit($limit = 20)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getRead()
    {
        return $this->read;
    }

    public function setRead($read = 0)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * @return int
     */
    public function getFromId()
    {
        return $this->from_id;
    }

    public function setFromId($from_id)
    {
        $this->from_id = $from_id;

        return $this;
    }

    public function initialize($input = [])
    {
        foreach ($input as $key => $value) {
            $method = ucfirst($key);
            if (method_exists($this, "set{$method}") && $value != null) {
                $this->{'set'.$method}($value);
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getToId()
    {
        if ($this->to_id == false) {
            $this->setToId();
        }

        return $this->to_id;
    }

    public function setToId($to_id = null)
    {
        if ($to_id == null) {
            $to_id = Auth::user()->id;
        }
        $this->to_id = $to_id;

        return $this;
    }
}
