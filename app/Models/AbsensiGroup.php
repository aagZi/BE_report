<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiGroup extends Model
{
    protected $table = 'absensi_group';
    protected $primaryKey = 'id_group';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'client_id',
        'status',
        'created_at',
        'closed_at',
    ];
}
