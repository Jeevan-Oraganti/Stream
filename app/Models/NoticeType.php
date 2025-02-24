<?php

namespace App\Models;

use App\Models\Notice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeType extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'color'];

    protected $casts = [
        'created_at' => 'date',
    ];
    public function notices()
    {
        return $this->hasMany(Notice::class, 'notice_type_id');
    }
}
