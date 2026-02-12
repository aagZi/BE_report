<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserLevel;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_client',
        'group_id',
        'id_cab_teknisi',
        'name_user',
        'slug_url',
        'gender',
        'alamat_client',
        'email',
        'user_phone',
        'photo',
        'p_type',
        'password',
        'nik',
        'leader',
        'job',
        'multiReport',
        'level',
        'data_created',
        'last_login_ip',
        'last_login_time',
        'status'
    ];

    // karena tidak ada kolom created_at/updated_at di database
    public $timestamps = false;

    protected $hidden = [
        'password',
        'nik'
    ];

    protected $casts = [
        'status' => UserStatus::class,
        'level' => UserLevel::class,
        'multiReport' => 'boolean',
        'last_login_time' => 'datetime',
        'date_created' => 'datetime'
    ];

    protected $appends = [
        'status_label',
        'level_label',
        'is_active'
    ];

    public function getAuthPassword()
    {
        return $this->password();
    }


    public function isActive(): bool
    {
        return $this->status === UserStatus::AKTIF;
    }

    public function isAdmin(): bool
    {
        return $this->level === UserLevel::CLIENT;
    }

    public function isTeknisi(): bool
    {
        return $this->level === UserLevel::TEKNISI;
    }

    /* ================= ACCESSOR ================= */

    public function getStatusLabelAttribute(): ?string
    {
        return $this->status?->label();
    }

    public function getLevelLabelAttribute(): ?string
    {
        return $this->level?->label();
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->isActive();
    }


    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_cli');
    }

    // relasi ke groups
    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id', 'id_group');
    }
}
