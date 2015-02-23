<?php namespace AlfredNutileInc\DiffTool\Notifications;

use AlfredNutileInc\CoreApp\Helpers\UuidHelper;
use AlfredNutileInc\CoreApp\Notifications\NoticeCreateBuilder;
use AlfredNutileInc\CoreApp\Notifications\NotificationCategory;
use AlfredNutileInc\CoreApp\Notifications\NotificationMessage;
use AlfredNutileInc\DiffTool\DiffToolDTO;
use Approve\Projects\Project;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use AlfredNutileInc\CoreApp\Notifications\NotificationFacade as Notify;

class NotificationsListener extends ServiceProvider {


    use UuidHelper;

    /**
     * @var DiffToolDTO
     */
    protected $dto;
    protected $from;
    protected $to;
    protected $message;
    protected $to_whom = [];
    protected $event_name;
    protected $category;
    protected $message_id;
    protected $to_type;
    protected $from_type;
    protected $notifications_made = [];



    public function filesUploadReadyToCompareNotice($event)
    {
        $this->setEventName('diff_tool.file_uploads_ready_to_compare');
        $this->setDto($event);

        //1) Get the keys from incoming event to make the notification(s)
        //  1. Project ID from
        //  2. Request ID from
        $this->setFrom($this->getDto()->project_id);
        $this->setFromType('Approve\Projects\Project');

        //2) Set User IDs to (add this to the DTO for now on)
        $this->setToWhom();
        $this->setToType('Approve\Profile\User');

        if(count($this->getToWhom()) < 1)
            return false;

        //3) Make a title/category for this based on the Event name eg
        //  diff_tool.file_uploads_ready_to_compare
        //  if it does not exist make the notification_category
        $this->setCategory();

        //4) Make the Notification Message in the db
        $this->setMessage("File uploads ready to compare for Project");
        $this->createMessage();

        //5) Make the Notifications From To in the DB
        //    store each To id in an array to send them all at once
        //    after to pusher via an Event Listener
        $this->createNotificationsForEachToWhom();

        //6) Fire Notification using the above keys/ids for To
        //    and message in a NotificationsListenerDTO
        Event::fire('notifications.' . $this->getEventName(), [$this->getNotificationsMade()]);
    }

    protected function createMessage()
    {
        try
        {
            $this->setMessageId($this->getUuid());
            NotificationMessage::create(
                [
                    'id' => $this->getMessageId(),
                    'message' => $this->getMessage()
                ]
            );
            return $this->getMessageId();
        }
        catch(\Exception $e)
        {
            throw new \Exception(sprintf("Error making message %s", $e->getMessage()));
        }
    }

    protected function setMessageId($uuid)
    {
        $this->message_id = $uuid;
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }

    public function boot()
    {
        $this->app['events']->listen('diff_tool.file_uploads_ready_to_compare', function($event){
            $this->filesUploadReadyToCompareNotice($event);
        });
    }

    /**
     * @return mixed
     */
    public function getDto()
    {
        return $this->dto;
    }

    /**
     * @param mixed $dto
     */
    public function setDto($dto)
    {
        try
            {

                $dto = new DiffToolDTO($dto->project_id, $dto->request_id, $dto->stage);
            }
            catch(\Exception $e)
            {
                $message = sprintf("Error making dto from event %s and message %s", print_r($dto, 1), $e->getMessage());
                $this->throw_and_log_error($message);
            }


        $this->dto = $dto;
    }

    /**
     * @return array
     */
    public function getToWhom()
    {
        return $this->to_whom;
    }

    /**
     * @param array $to_whom
     */
    public function setToWhom($to_whom = [])
    {
        //Who is relate to this Project
        //Make array of their IDs
        //Set whom
        try {
        if(empty($to_whom)) {
            $project = Project::with('users')->find($this->getFrom());
            if($project)
            {
                $to_whom = $project->users->lists('id');
            }
        }
        $this->to_whom = $to_whom;
        return $this;
        }
        catch(\Exception $e)
        {
            throw new \Exception(sprintf("Error getting to whom %s", $e->getMessage()));
        }
    }

    private function createNotificationsForEachToWhom()
    {
        try {

            foreach($this->getToWhom() as $who)
            {
                $uuid = $this->generateNewId()->toString();
                $notify_builder = new NoticeCreateBuilder(
                    $to_id       = $who,
                    $to_type     = $this->getToType(),
                    $from_id     = $this->getFrom(),
                    $from_type   = $this->getFromType(),
                    $message_id  = $this->getMessageId(),
                    $category_id = $this->getCategory()
                );
                $notify_builder->setId($uuid);
                $notice_made = Notify::create($notify_builder);
                $this->setNotificationsMade($notice_made);
            }
        }
        catch(\Exception $e)
        {
            throw new \Exception(sprintf("Error making notice for each person %s", $e->getMessage()));
        }
    }

    public function setCategory()
    {
        try {
        //If Category exists get id
        $category = NotificationCategory::firstOrCreate([
            'id' => $this->getUuid(),
            'name' => $this->getEventName(),
            'description' => 'File Uploads Ready to Compare'
        ]);

        $this->category = $category->id;
        return $this;
        }
        catch(\Exception $e)
        {
            throw new \Exception(sprintf("Error making category %s", $e->getMessage()));
        }
    }

    /**
     * @return mixed
     */
    public function getEventName()
    {
        return $this->event_name;
    }

    /**
     * @param mixed $event_name
     */
    public function setEventName($event_name)
    {
        $this->event_name = $event_name;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getToType()
    {
        return $this->to_type;
    }

    /**
     * @param mixed $to_type
     */
    public function setToType($to_type)
    {
        $this->to_type = $to_type;
    }

    /**
     * @return mixed
     */
    public function getFromType()
    {
        return $this->from_type;
    }

    /**
     * @param mixed $from_type
     */
    public function setFromType($from_type)
    {
        $this->from_type = $from_type;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @return array
     */
    public function getNotificationsMade()
    {
        return $this->notifications_made;
    }

    /**
     * @param array $notifications_made
     */
    public function setNotificationsMade($notifications_made)
    {
        $this->notifications_made[] = $notifications_made;
    }


}