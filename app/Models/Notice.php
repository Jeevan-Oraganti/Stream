<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\NoticeType;
use Illuminate\Support\Facades\Auth;

class Notice extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'expiry_date' => 'datetime',
        'is_sticky' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'description',
        'is_sticky',
        'notice_type_id',
        'expiry_date',
        'scheduled_at',
        'is_active',
    ];

    public function noticeType()
    {
        return $this->belongsTo(NoticeType::class, 'notice_type_id');
    }
    public function hasRead()
    {
        return $this->hasMany(UserNotice::class, 'notice_id');
    }

    protected function asDateTimez($value)
    {
        if($value instanceof Carbon) {
            $value->setTimezone('Asia/Kolkata');
            return $value;
        }

        $value = new Carbon($value);

        $value->setTimezone('Asia/Kolkata');

        return $value;
    }
}
