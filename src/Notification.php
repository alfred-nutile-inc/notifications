<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 2/19/15
 * Time: 7:57 AM.
 */
namespace AlfredNutileInc\Notifications;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Carbon\Carbon;

class Notification extends BaseModel implements NotificationModelInterface
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'from_id',
        'from_type',
        'to_id',
        'to_type',
        'notification_category_id',
        'notification_message_id',
        'read',
    ];

    protected $hidden = ['updated_at'];

    public function notification_message()
    {
        return $this->belongsTo('AlfredNutileInc\Notifications\NotificationMessage');
    }

    public function from()
    {
        return $this->morphTo();
    }

    public function to()
    {
        return $this->morphTo();
    }

    public function scopeRead($query, $read)
    {
        if ($read == -1) {
            return $query;
        }

        return $query->where('read', $read);
    }

    public function scopeFromId($query, $from_id)
    {
        if ($from_id == -1 || $from_id == null) {
            return $query;
        }

        return $query->where('from_id', $from_id);
    }

    public function scopeToId($query, $to_id)
    {
        if ($to_id == -1 || $to_id == null) {
            return $query;
        }

        return $query->where('to_id', $to_id);
    }

    public function getDates()
    {
        return array('created_at');
    }

    public function getCreatedAtAttribute($attr)
    {
        return Carbon::parse($attr)->timestamp * 1000; //Change the format to whichever you desire
    }
}
