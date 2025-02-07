<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NoticeType;

class Notice extends Model
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
        return $this->belongsTo(NoticeType::class);
    }
    public function hasRead()
    {
        return $this->hasMany(UserNotice::class, 'notice_id');
    }
}
