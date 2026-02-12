<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditGroupUser;
use App\Models\GroupMembers;
use App\Models\Groups;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class GroupUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'group_id' => 'required|exists:groups,id_group',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name_user' => $request->name,
                'group_id' => $request->group_id,
                'gender' => $request->gender,
                'email' => $request->email,
                'photo' => $request->photo ?? 'default',
                'p_type' => $request->p_type ?? '.png',
                'user_phone' => $request->telephone,
                'password' => Hash::make($request->password),
                'level' => 5
            ]);

            GroupMembers::create([
                'client_id' => $user->id,
                'group_id' => $request->group_id,
                'add_by' => $request->user()->id ?? null
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil menambahkan ke group'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        // DB::beginTransaction();

        // try {
        //     $group = Groups::findOrFail($id);

        //     // cek apakah ada user di grup ini
        //     $user = User::where('group_id', $id)->count();

        //     if ($user > 0) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => "Group tidak bisa dihapus, masih ada $user user digroup ini"
        //         ]);
        //     }

        //     $group->delete();

        //     DB::commit();

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Group berhasil di hapus'
        //     ]);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'success' => false,
        //         'message' => $e->getMessage()
        //     ]);
        // }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'gender'    => 'required|in:male,female',
            'group_id'  => 'required|exists:groups,id_group',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password'  => 'nullable|min:6',
            'photo'     => 'nullable|string',
            'p_type'    => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            // Update data user
            $updateData = [
                'name_user'  => $request->name,
                'group_id'   => $request->group_id,
                'gender'     => $request->gender,
                'user_phone' => $request->telephone,
            ];

            // Jika email diisi → update
            if ($request->filled('email')) {
                $updateData['email'] = $request->email;
            }

            // Jika password diisi → hash
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Jika photo diisi
            if ($request->filled('photo')) {
                $updateData['photo'] = $request->photo;
            }

            if ($request->filled('p_type')) {
                $updateData['p_type'] = $request->p_type;
            }

            $user->update($updateData);

            // Update group member
            GroupMembers::updateOrCreate(
                ['client_id' => $user->id],
                [
                    'group_id' => $request->group_id,
                    'add_by'   => $request->user()->id ?? null
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diupdate',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
