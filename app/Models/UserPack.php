<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPack extends Model
{
    protected $table = 'user_pack';
    protected $primaryKey = 'id_pack';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_client',
    ];
}
