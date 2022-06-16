<?php

namespace RyanChandler\FilamentNavigation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property string $handle
 * @property array $items
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Navigation extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    protected $guarded = [];

    protected $casts = [
        'items' => 'json',
    ];
    
    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];
}
