<?php

namespace RyanChandler\FilamentNavigation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'items' => 'json',
    ];
}
