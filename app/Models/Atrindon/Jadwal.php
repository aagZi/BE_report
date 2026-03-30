<?php

namespace App\Models\Atrindon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tabel jadwal di database atrindon_laravel.
 */
class Jadwal extends Model
{
    protected $connection = 'atrindon_laravel';

    protected $table = 'jadwal';

    public $timestamps = false;

    protected $fillable = [
        'id_team',
        'id_cab',
        'title',
        'description',
        'color',
        'progres',
        'start_date',
        'end_date',
        'create_at',
        'modified_at',
        'start_time',
        'end_time',
        'month',
        'years',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'create_at' => 'datetime',
        'modified_at' => 'datetime',
    ];

    public function atrindonUser(): BelongsTo
    {
        return $this->belongsTo(AtrindonUser::class, 'id_team', 'id');
    }
}
