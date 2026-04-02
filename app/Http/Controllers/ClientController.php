<?php

namespace App\Http\Controllers;

// use GuzzleHttp\Client;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Services\ClientService;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ClientResource;


class ClientController extends Controller
{
    public function __construct(private ClientService $clientService) {}

    /**
     * GET /list/client - List semua client
     */
    public function index()
    {
        $clients = $this->clientService->getAllClient();

        return response()->json([
            'success' => true,
            'message' => 'List client berhasil diambil',
            'data' => $clients,
        ]);
    }

    /**
     * GET /list/client/{id} - Detail client by id
     */
    public function show($id)
    {
        $client = Client::with(['cabang'])->find($id);

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail client berhasil diambil',
            'data' => $client,
        ]);
    }

    /**
     * POST /list/client - Tambah client baru
     * Body: cabang, nama_client, uniqid, client_url?, logo?
     */
    public function store(Request $request)
    {
        $request->validate([
            'cabang' => 'required|exists:cabang,id_ca',
            'nama_client' => 'required|string|max:255',
            'client_url' => 'nullable|string|max:500',
            'logo' => 'nullable|string|max:500',
            'uniqid' => 'required|string|max:64|unique:client,uniqid',
        ]);

        $client = Client::create([
            'cabang' => $request->cabang,
            'nama_client' => $request->nama_client,
            'client_url' => $request->client_url ?? null,
            'logo' => $request->logo ?? null,
            'uniqid' => $request->uniqid,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Client berhasil ditambahkan',
            'data' => $client,
        ], 201);
    }

    /**
     * PUT /list/client/{id} - Update client
     * Body: cabang?, nama_client?, uniqid?, client_url?, logo?
     */
    public function update(Request $request, $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client tidak ditemukan',
            ], 404);
        }

        $request->validate([
            'cabang' => 'sometimes|exists:cabang,id_ca',
            'nama_client' => 'sometimes|string|max:255',
            'client_url' => 'nullable|string|max:500',
            'logo' => 'nullable|string|max:500',
            'uniqid' => 'sometimes|nullable|string|max:64|unique:client,uniqid,'.$id.',id_cli',
        ]);

        $client->update($request->only(['cabang', 'nama_client', 'client_url', 'logo', 'uniqid']));

        return response()->json([
            'success' => true,
            'message' => 'Client berhasil diperbarui',
            'data' => $client->fresh(),
        ]);
    }

    /**
     * DELETE /list/client/{id} - Hapus client
     */
    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client tidak ditemukan',
            ], 404);
        }

        $client->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client berhasil dihapus',
        ]);
    }

    public function byUserId($id)
    {
        $clients = $this->clientService->getClientByUserId($id);

        return response()->json([
            'success' => true,
            'message' => 'list client berhasil diambil',
            'data' => $clients
        ]);
    }

    // Edit Client (legacy - update client + user)
    public function editClient(Request $request, $id)
    {

        $request->validate([
            'client' => 'required|exists:client,id_cli',
            'cabang' => 'required|exists:cabang,id_ca',
            'name_user' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|password'
        ]);

        DB::beginTransaction();

        try {
            $client = Client::findOrfail($id);

            // update client
            $client->update([
                'nama_client' => $request->client,
                'cabang' => $request->cabang,
                'client_url' => $request->client_url,
                'logo' => $request->logo
            ]);

            // update user
            $user = $client->users()->first();

            if ($user) {
                $user->update([
                    'name_user' => $request->name_user,
                    'email' => $request->email,
                    'alamat_client' => $request->alamat_client
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Client berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
