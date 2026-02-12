<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_cli' => $this->id_cli,
            'cabang' => $this->cabang,
            'nama_client' => $this->nama_client,
            'client_irl' => $this->client_url,
            'logo' => $this->logo
        ];
    }
}
