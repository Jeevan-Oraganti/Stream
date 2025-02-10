<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notice;

class NoticeType extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'color'];

    public function notices()
    {
        return $this->hasMany(Notice::class, 'notification_type_id');
    }
}
