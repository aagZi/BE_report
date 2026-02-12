<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMembers extends Model
{
    protected $table = 'group_members';
    protected $primaryKey = 'id_member';
    public $timestamps = false;

    protected $fillable = [
        'add_by',
        'client_id',
        'group_id'
    ];

    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id', 'id_group');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }
}
