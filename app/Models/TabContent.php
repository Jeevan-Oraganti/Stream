<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabContent extends Model
{
    use HasFactory;

    protected $table = 'tabs_content';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
