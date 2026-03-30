<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;

class HistoryController extends Controller
{
    /**
     * GET /api/history - List history task (status=1, tugas=4)
     */
    public function index(): JsonResponse
    {
        $history = Task::history()
            ->with([
                'user:id,name_user',
                'client:id_cli,nama_client',
            ])
            ->orderBy('tgl', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'History berhasil diambil',
            'data' => $history,
        ]);
    }
}
