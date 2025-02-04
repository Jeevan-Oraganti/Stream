<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notices;

class NotificationType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function notices()
    {
        return $this->hasMany(Notices::class);
    }
}
