<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'id_task';
    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'user_id',
        'branch_id',
        'floor_id',
        'sub_floor_id',
        'area',
        'absen_in',
        'time_in',
        'date_in',
        'absen_out',
        'time_out',
        'date_out',
        'report_book',
        'pc',
        'rc',
        'termite',
        'sign_in',
        'stamp',
        'tgl',
        'tugas',
        'token',
        'status',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id_cli');
    }

    public function scopeHistory($query)
    {
        return $query->where('status', 1)->where('tugas', 4);
    }
}
