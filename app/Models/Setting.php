<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'is_public',
    ];

    protected $casts = [
        'value' => 'array',
        'is_public' => 'boolean',
    ];

    /**
     * Scope: only public settings (for mobile API).
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
