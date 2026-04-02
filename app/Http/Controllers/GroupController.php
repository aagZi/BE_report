<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Services\groupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct(private groupService $service) {}

    /**
     * GET /groups — List group lengkap (sama seperti respons lama GET /list/group).
     */
    public function index()
    {
        $groups = Groups::with(['users:id,group_id,name_user,email,user_phone'])
            ->get()
            ->map(function ($group) {
                return [
                    'id_group' => $group->id_group,
                    'nama_group' => $group->nama_group,
                    'name_url' => $group->name_url,
                    'description' => $group->description,
                    'created_by' => $group->created_by,
                    'users' => $group->users->map(function ($u) {
                        return [
                            'name_user' => $u->name_user,
                            'email' => $u->email,
                            'phone' => $u->user_phone
                        ];
                    })
                ];
            });
        return response()->json([
            'success' => true,
            'message' => 'List group berhasil diambil',
            'data' => $groups
        ]);
    }

    /**
     * GET /list/group — Ringkas: id (id_group) dan nama_group per baris.
     */
    public function listGroupSummary()
    {
        $rows = Groups::query()
            ->orderBy('nama_group')
            ->get(['id_group', 'nama_group'])
            ->map(fn (Groups $g) => [
                'id' => $g->id_group,
                'nama_group' => $g->nama_group,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'List group berhasil diambil',
            'data' => $rows,
        ]);
    }

    /**
     * GET /list/group/{id} - Detail group by id
     */
    public function show($id)
    {
        $group = Groups::with(['users:id,group_id,name_user,email,user_phone'])->find($id);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group tidak ditemukan',
            ], 404);
        }

        $data = [
            'id_group' => $group->id_group,
            'nama_group' => $group->nama_group,
            'name_url' => $group->name_url,
            'description' => $group->description,
            'created_by' => $group->created_by,
            'users' => $group->users->map(function ($u) {
                return [
                    'name_user' => $u->name_user,
                    'email' => $u->email,
                    'phone' => $u->user_phone
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail group berhasil diambil',
            'data' => $data
        ]);
    }

    /**
     * POST /list/group - Tambah group baru
     * Body: nama_group, name_url?, description?, created_by?
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_group' => 'required|string|max:255',
            'name_url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'created_by' => 'nullable|integer',
        ]);

        $group = Groups::create([
            'nama_group' => $request->nama_group,
            'name_url' => $request->name_url ?? null,
            'description' => $request->description ?? null,
            'created_by' => $request->created_by ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Group berhasil ditambahkan',
            'data' => $group,
        ], 201);
    }

    /**
     * PUT /list/group/{id} - Update group
     * Body: nama_group?, name_url?, description?, created_by?
     */
    public function update(Request $request, $id)
    {
        $group = Groups::find($id);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group tidak ditemukan',
            ], 404);
        }

        $request->validate([
            'nama_group' => 'sometimes|string|max:255',
            'name_url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'created_by' => 'nullable|integer',
        ]);

        $group->update($request->only(['nama_group', 'name_url', 'description', 'created_by']));

        return response()->json([
            'success' => true,
            'message' => 'Group berhasil diperbarui',
            'data' => $group->fresh(),
        ]);
    }

    /**
     * DELETE /list/group/{id} - Hapus group
     */
    public function destroy($id)
    {
        $group = Groups::find($id);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group tidak ditemukan',
            ], 404);
        }

        $group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Group berhasil dihapus',
        ]);
    }
}
