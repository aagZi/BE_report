<?php

namespace App\Http\Controllers;

use App\Models\Atrindon\AtrindonUser;
use App\Models\Atrindon\Jadwal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * GET /api/jadwal — jadwal user dari DB atrindon_laravel, di-link lewat email sama dengan user login report.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'month' => 'sometimes|integer|min:1|max:12',
            'year' => 'sometimes|integer|min:1970|max:2100',
        ]);

        $reportUser = $request->user();
        $emailNormalized = strtolower(trim((string) $reportUser->email));

        $laravelUser = AtrindonUser::query()
            ->whereRaw('LOWER(TRIM(email)) = ?', [$emailNormalized])
            ->first();

        if ($laravelUser === null) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada user terkait di sistem jadwal (email tidak ditemukan).',
                'data' => [],
            ]);
        }

        $query = Jadwal::query()
            ->where('id_team', $laravelUser->id)
            ->with(['atrindonUser:id,name_user']);

        if ($request->filled('month')) {
            $query->where('month', (int) $request->input('month'));
        }
        if ($request->filled('year')) {
            $query->where('years', (int) $request->input('year'));
        }

        $rows = $query
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diambil',
            'data' => $rows,
        ]);
    }
}
