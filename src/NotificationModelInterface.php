<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 2/19/15
 * Time: 12:53 PM
 */

namespace AlfredNutileInc\Notifications;


interface NotificationModelInterface {

    public function scopeFromId($query, $from_id);
    public function scopeToId($query, $to_id);
    public function scopeRead($query, $state);

    public function from();
    public function to();
}