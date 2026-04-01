<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id_absen';

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'client_id',
        'group_id',
        'tgl',
        'waktu',
        'info',
        'photo',
        'lati',
        'longi'
    ];
}
