<?php

namespace AlfredNutileInc\Notifications;

interface NotificationModelInterface
{
    public function scopeFromId($query, $from_id);
    public function scopeToId($query, $to_id);
    public function scopeRead($query, $state);

    public function from();
    public function to();
}
