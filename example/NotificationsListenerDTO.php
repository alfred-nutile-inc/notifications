<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 2/20/15
 * Time: 8:04 AM
 */

namespace AlfredNutileInc\DiffTool\Notifications;


class NotificationsListenerDTO {


    public $to_ids  = [];
    public $message = [];
    public $from_id;

    public function __construct(array $instantiate)
    {
        $this->to_ids   = $instantiate['to_ids'];
        $this->message  = $instantiate['message'];
        $this->from_id  = $instantiate['from_id'];
    }

}