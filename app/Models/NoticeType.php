<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notice;

class NotificationType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function notices()
    {
        return $this->hasMany(Notice::class);
    }
}
