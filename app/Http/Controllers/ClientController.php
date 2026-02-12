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

    public function index()
    {
        $clients = $this->clientService->getAllClient();

        return response()->json([
            'success' => true,
            'message' => 'List client berhasil diambil',
            'data' => $clients,
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

    // Edit Client
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
