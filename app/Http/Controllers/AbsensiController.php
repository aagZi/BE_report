<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbsenStoreRequest;
use App\Models\Absensi;
use App\Models\AbsensiGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    // #region agent log
    private function debugLog(string $runId, string $hypothesisId, string $location, string $message, array $data = []): void
    {
        $payload = [
            'sessionId' => 'db331f',
            'runId' => $runId,
            'hypothesisId' => $hypothesisId,
            'location' => $location,
            'message' => $message,
            'data' => $data,
            'timestamp' => (int) round(microtime(true) * 1000),
        ];
        @file_put_contents(base_path('debug-db331f.log'), json_encode($payload) . PHP_EOL, FILE_APPEND);
    }
    // #endregion

    public function store(AbsenStoreRequest $request)
    {
        // #region agent log
        $this->debugLog(
            'pre-fix',
            'H4',
            'AbsensiController.php:store',
            'Controller reached after validation',
            ['auth_user_id' => optional($request->user())->id]
        );
        // #endregion
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $imageBase64 = $request->data;

        // memisahkan prefix & data base64
        $imageParts = explode(';base64,', $imageBase64);
        if (count($imageParts) < 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format image base64 tidak valid.',
            ], 422);
        }

        $image = base64_decode($imageParts[1], true);
        if ($image === false) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal decode image base64.',
            ], 422);
        }

        // nama file
        $fileName = Str::slug($user->name_user, '-') . '-' . date('Y-m-d') . '-' . uniqid() . '.png';

        // path sistem
        $path = public_path('images/absensi/' . $fileName);
        $dirPath = dirname($path);
        if (!File::exists($dirPath)) {
            File::makeDirectory($dirPath, 0755, true);
        }

        // simpan file
        file_put_contents($path, $image);

        $id = $request->di;

        // insert db absensi
        if (!$id) {
            $absensi = DB::transaction(function () use ($request, $user, $fileName) {
                $now = Carbon::now();
                $group = AbsensiGroup::query()
                    ->where('user_id', $user->id)
                    ->where('client_id', $request->client_id)
                    ->where('status', 'OPEN')
                    ->orderByDesc('created_at')
                    ->orderByDesc('id_group')
                    ->first();

                if (!$group) {
                    $group = AbsensiGroup::create([
                        'user_id' => $user->id,
                        'client_id' => $request->client_id,
                        'status' => 'OPEN',
                        'created_at' => $now->toDateTimeString(),
                        'closed_at' => null,
                    ]);
                }

                $absensi = Absensi::create([
                    'tgl' => $now->format('Y-m-d'),
                    'waktu' => $now->format('H:i:s'),
                    'info' => $request->info,
                    'user_id' => $user->id,
                    'client_id' => $request->client_id,
                    'group_id' => $group->id_group,
                    'photo' => $fileName,
                    'lati' => $request->lat,
                    'longi' => $request->long,
                ]);

                $groupAbsensi = Absensi::query()
                    ->where('group_id', $group->id_group)
                    ->where('user_id', $user->id)
                    ->where('client_id', $request->client_id);

                $hasIn = (clone $groupAbsensi)->where('info', 'IN')->exists();
                $hasOut = (clone $groupAbsensi)->where('info', 'OUT')->exists();

                $groupStatus = ($hasIn && $hasOut) ? 'CLOSED' : 'OPEN';

                $groupPayload = ['status' => $groupStatus];
                if ($groupStatus === 'CLOSED') {
                    $groupPayload['closed_at'] = $now->toDateTimeString();
                }

                AbsensiGroup::query()
                    ->where('id_group', $group->id_group)
                    ->where('user_id', $user->id)
                    ->where('client_id', $request->client_id)
                    ->update($groupPayload);

                return $absensi;
            });

            return response()->json([
                'status' => 'success',
                'id' => $absensi->id_absen,
                'waktu' => $absensi->waktu,
                'tgl' => $absensi->tgl,
            ]);
        }

        // update Absensi Lama
        $old = Absensi::find($id);

        if (!$old) {
            return response()->json(['status' => 'Error', 'message' => 'Data not found']);
        }

        // hapus foto lama
        $oldPath = public_path('images/absensi/' . $old->photo);
        if (File::exists($oldPath)) {
            File::delete($oldPath);
        }

        // update foto baru
        $old->update([
            'photo' => $fileName
        ]);

        return response()->json([
            'status' => 'success',
            'id' => $old->id_absen,
        ]);
    }
}
