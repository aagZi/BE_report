<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Services\groupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct(private groupService $service) {}

    public function index()
    {
        $groups = Groups::with(['users:id,group_id,name_user,email,user_phone'])
            ->get()
            ->map(function ($group) {
                return [
                    'id_group' => $group->id_group,
                    'nama_group' => $group->nama_group,
                    'description' => $group->description,
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
            'data' => $groups
        ]);
    }
}
