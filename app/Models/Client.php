<?php

namespace App\Models;

use App\Models\Cabang;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';
    protected $primaryKey = 'id_cli';
    public $timestamps = false;

    protected $fillable = [
        'cabang',
        'nama_client',
        'client_url',
        'logo'
    ];

    // ============================= Relasi =============================

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang', 'id_ca');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_client', 'id_cli');
    }
}
