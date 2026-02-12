<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientUserService
{
    public function create(array $data)
    {
        $user = User::create([
            'id_client' => $data['id_client'],
            'id_cabang' => $data['id_cabang'],
            'gender' => $data['gender'],
            'name_user' => $data['name_user'],
            'email' => $data['email'],
            'user_phone' => $data['user_phone'],
            'photo' => $data['photo'] ?? 'default',
            'p_type' => $data['p_type'] ?? 'png',
            'alamat_client' => $data['alamat_client'],
            'password' => Hash::make($data['password']),

            'level' => 2,
        ]);

        return $user;
    }

    public function update(int $id, array $data)
    {
        $user = User::findOrFail($id);

        $updateData = [
            'id_client'     => $data['id_client'],
            'id_cabang'     => $data['id_cabang'],
            'gender'        => $data['gender'],
            'name_user'     => $data['name_user'],
            'email'         => $data['email'],
            'user_phone'    => $data['user_phone'],
            'alamat_client' => $data['alamat_client'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return true;
    }
}
