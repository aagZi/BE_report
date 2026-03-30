<?php

namespace App\Models\Atrindon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User di database atrindon_laravel (bukan model login report).
 */
class AtrindonUser extends Model
{
    protected $connection = 'atrindon_laravel';

    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'id_cabang',
        'name_user',
        'alias',
        'gender',
        'email',
        'user_phone',
        'photo',
        'p_type',
        'password',
        'level',
        'leader',
        'job',
        'bm',
        'last_login_ip',
        'date_created',
        'status',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_created' => 'datetime',
    ];

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_team', 'id');
    }
}
