<?php

namespace RyanChandler\FilamentNavigation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $handle
 * @property array $items
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Navigation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'items' => 'json',
    ];

    public static function fromHandle(string $handle, $isTenant = false): ?static
    {
        $rows = static::query();
        if ($isTenant) {
            $rows = $rows->where('team_id', getTeamID());
        }

        return $rows->firstWhere('handle', $handle);
    }

    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}
