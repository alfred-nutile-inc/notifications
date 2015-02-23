<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 2/20/15
 * Time: 8:16 AM
 */

namespace AlfredNutileInc\CoreApp\Notifications;


class NoticeCreateBuilder {


    public $to_id;
    public $to_type;
    public $from_id;
    public $from_type;
    public $message_id;
    public $category_id;
    public $id;

    public function __construct($to_id, $to_type, $from_id, $from_type, $message_id, $category_id)
    {
        $this->to_id = $to_id;
        $this->to_type = $to_type;
        $this->from_id = $from_id;
        $this->from_type = $from_type;
        $this->message_id = $message_id;
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getToId()
    {
        return $this->to_id;
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
    public function getFromId()
    {
        return $this->from_id;
    }

    /**
     * @param mixed $from_id
     */
    public function setFromId($from_id)
    {
        $this->from_id = $from_id;
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
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @param mixed $to_id
     */
    public function setToId($to_id)
    {
        $this->to_id = $to_id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

}