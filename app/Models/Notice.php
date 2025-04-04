<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NoticeType;
use Illuminate\Support\Facades\Auth;

class Notice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'expiry_date' => 'datetime',
        'event_date' => 'datetime',
        'is_sticky' => 'boolean',
        'recurrence_days' => 'array',
    ];

    protected $fillable = [
        'name',
        'description',
        'is_sticky',
        'notice_type_id',
        'expiry_date',
        'event_date',
        'is_active',
        'recurrence',
        'recurrence_days'
    ];

    public function noticeType()
    {
        return $this->belongsTo(NoticeType::class, 'notice_type_id');
    }
    public function views()
    {
        return $this->hasMany(UserNotice::class, 'notice_id');
    }

    protected function asDateTimez($value)
    {
        if ($value instanceof Carbon) {
            $value->setTimezone('Asia/Kolkata');
            return $value;
        }

        $value = new Carbon($value);

        $value->setTimezone('Asia/Kolkata');

        return $value;
    }
}
