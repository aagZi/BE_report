<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'cabang';
    protected $primaryKey = 'id_ca';
    public $timestamps = false;

    protected $fillable = [
        'nama_cabang'
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'cabang', 'id_ca');
    }
}
