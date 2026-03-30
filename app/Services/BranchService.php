<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class BranchService
{
    public function getAllBranch()
    {
        return DB::table('users as u')
            ->join('cabang as ca', 'ca.id_ca', '=', 'u.id_cab_teknisi')
            ->where('u.level', 4)
            ->select(
                'u.name_user',
                'u.email',
                'u.user_phone',
                'u.level',
                'ca.nama_cabang',
            )
            ->orderBy('u.name_user', 'desc')
            ->get();
    }
}
