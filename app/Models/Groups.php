<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $table = 'groups';
    protected $primaryKey = 'id_group';
    public $timestamps = false;

    protected $fillable = [
        'created_by',
        'nama_group',
        'name_url',
        'description'
    ];

    // relasi ke users
    public function users()
    {

        return $this->hasMany(User::class, 'group_id', 'id_group')
            ->where('level', 5);
    }

    // relasi ke group_members
    public function members()
    {
        return $this->hasMany(GroupMembers::class, 'group_id', 'id_group');
    }
}
