<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notices extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'name',
        'description',
        'notification_type_id',
        'expiry_date',
    ];
}
