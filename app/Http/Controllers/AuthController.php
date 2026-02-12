<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function Symfony\Component\Clock\now;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ]);
        }

        // cek status user
        if (!$user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak aktif'
            ]);
        }

        // cek password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'password salah'
            ]);
        }

        // update last login
        $user->update([
            'last_login_ip' => $request->ip(),
            'last_login_time' => now(),
        ]);

        // generate sanctum token
        $token = $user->createToken('atrindon_report')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'login berhasil',
            'data' => [
                'id' => $user->id,
                'name' => $user->name_user,
                'email' => $user->email,
                'level' => $user->level->value,
                'level_label' => $user->level->label(),
                'status' => $user->status->value,
                'status_label' => $user->status->label(),
                'token' => $token,
            ],
        ]);
    }

    // Logout API
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }
}
