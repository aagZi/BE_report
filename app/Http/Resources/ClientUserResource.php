<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_client' => $this->id_client,
            'cabang' => $this->id_cabang,
            'gender' => $this->gender,
            'name_user' => $this->name_user,
            'email' => $this->email,
            'user_phone' => $this->user_phone,
            'alamat_client' => $this->alamat_client,
            'date_created' => $this->date_created,
        ];
    }
}
