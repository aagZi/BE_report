<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BranchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BranchController extends Controller
{
    public function __construct(private BranchService $branchService) {}

    public function index()
    {
        $branch = $this->branchService->getAllBranch();

        return response()->json([
            'success' => true,
            'message' => 'Branch berhasil diambil',
            'data' => $branch
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required|exists:users,gender',
            'cabang' => 'required|exists:cabang,id_ca',
            'phone' => 'required',
            'email' => 'required|email',
            'password' => 'required|password'
        ]);

        $branch = User::create([
            'name' => $request->name_user,
            'gender' => $request->gender,
            'cabang' => $request->cabang,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Branch berrhasil ditambah',
            'data' => $branch,
        ]);
    }
}
