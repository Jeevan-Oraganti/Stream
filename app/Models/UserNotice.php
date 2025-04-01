<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotice extends Model
{
    protected $fillable = ['user_id', 'notice_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function notice() {
        return $this->belongsTo(Notice::class);
    }

}
