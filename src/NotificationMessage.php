<?php

namespace AlfredNutileInc\Notifications;
use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'message',
        'translatable',
        'trans_function',
        'trans_params',
    ];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'trans_params' => 'array',
    ];

    public function getMessageAttribute($value)
    {
        if ($this->translatable) {
            $func = $this->trans_function;

            if (is_callable($func)) {
                return $func($value, $this->trans_params);
            }
        }

        return $value;
    }
}
