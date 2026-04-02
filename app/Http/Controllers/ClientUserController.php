<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditClientUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\ClientService;
use App\Services\ClientUserService;
use App\Http\Resources\ClientUserResource;
use App\Http\Requests\StoreClientUserRequest;

class ClientUserController extends Controller
{
    public function __construct(
        private ClientUserService $service,
        private ClientService $clientService,
    ) {}

    /**
     * GET /clientUser/list — List client seperti respons lama GET /list/client (nama_client, cabang, user, email, alamat).
     */
    public function index(): JsonResponse
    {
        $clients = $this->clientService->getAllClient();

        return response()->json([
            'success' => true,
            'message' => 'List client berhasil diambil',
            'data' => $clients,
        ]);
    }

    public function addUserClient(StoreClientUserRequest $request): JsonResponse
    {
        $user = $this->service->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Client User berhasil ditambahkan',
            'data' => new ClientUserResource($user),
        ]);
    }

    public function editUserClient(EditClientUser $request, $id)
    {
        $user = $this->service->update($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Berhasil update client',
            'data' => $user

        ]);
    }

    public function deleteUserClient($id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Client user berhasil dihapus'
        ]);
    }
}
