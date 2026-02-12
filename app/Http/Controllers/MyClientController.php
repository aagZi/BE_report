<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClientService;
use App\Http\Resources\ClientResource;

class MyClientController extends Controller
{
    public function __construct(private ClientService $clientService) {}

    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $clients = $this->clientService->getClientByUserId($userId);

        return response()->json([
            'success' => true,
            'message' => 'List client berhasil diambil',
            'data' => ClientResource::collection($clients),
        ]);
    }
}
