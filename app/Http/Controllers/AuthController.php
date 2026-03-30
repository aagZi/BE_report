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

        $email = trim($request->email);

        // Cari user: cek kolom email (trim, case-insensitive agar sesuai DB)
        $user = User::whereRaw('LOWER(TRIM(email)) = ?', [strtolower($email)])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Ambil hash password dari DB (pakai getAuthPassword agar konsisten)
        $storedPassword = $user->getAuthPassword();

        // Cek password: bcrypt (Laravel default) atau plain text legacy
        $passwordValid = false;
        if ($storedPassword !== null && $storedPassword !== '') {
            if (str_starts_with($storedPassword, '$2y$') || str_starts_with($storedPassword, '$2a$')) {
                $passwordValid = Hash::check($request->password, $storedPassword);
            } else {
                // Legacy: password disimpan plain text (untuk migrasi, sebaiknya diubah ke bcrypt)
                $passwordValid = hash_equals($storedPassword, $request->password);
            }
        }

        if (!$passwordValid) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // cek status user
        if (!$user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak aktif'
            ], 403);
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
