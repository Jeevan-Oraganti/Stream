<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NotificationType;

class Notices extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'name',
        'description',
        'notification_type_id',
        'expiry_date',
    ];

    public function notificationType()
    {
        return $this->belongsTo(NotificationType::class);
    }
    public function reads()
    {
        return $this->hasMany(UserNotices::class, 'notice_id');
    }
}
