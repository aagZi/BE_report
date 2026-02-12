<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ClientService
{
    public function getClientByUserId(int $userId)
    {
        return DB::table('users_pack as up')
            ->join('users as u', 'u.id', '=', 'up.id_user')
            ->join('client as c', 'c.id_cli', '=', 'up.id_clients')
            ->where('up.id_user', $userId)
            ->select([
                'c.id_cli',
                'c.cabang',
                'c.nama_client',
                'c.client_url',
                'c.logo'
            ])
            ->distinct()
            ->orderBy('c.nama_client', 'desc')
            ->get();
    }

    public function getAllClient()
    {
        return DB::table('client as c')
            ->join('users as u', 'c.id_cli', '=', 'u.id_client')
            ->join('cabang as ca', 'c.cabang', '=', 'ca.id_ca')
            ->select(
                'c.nama_client',
                'ca.nama_cabang',
                'u.name_user',
                'u.email',
                'u.alamat_client'
            )
            ->orderBy('u.name_user', 'desc')
            ->get();
    }
}
