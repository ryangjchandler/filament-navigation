<?php

namespace RyanChandler\FilamentNavigation\Models;

use Orbit\Concerns\Orbital;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    use Orbital;
    use HasFactory;

    public static $driver = 'json';

    public static function schema(Blueprint $table)
    {
        $table->id();
        $table->string('name');
        $table->string('handle')->unique();
        $table->longText('items')->nullable();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'handle',
        'items',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'items' => 'json',
    ];
}
