<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Endpoint dokumentasi API untuk frontend.
 * GET /api/docs - Mengembalikan daftar semua API yang tersedia beserta method, path, dan params.
 */
class ApiDocController extends Controller
{
    /**
     * GET /api/docs - List semua API dan parameternya untuk frontend
     */
    public function index()
    {
        $docs = [
            'base_url' => url('/api'),
            'auth' => 'Bearer token (Header: Authorization)',
            'endpoints' => [
                // ========== Auth ==========
                [
                    'method' => 'POST',
                    'path' => '/login',
                    'description' => 'Login user',
                    'auth_required' => false,
                    'params' => [
                        'body' => [
                            ['name' => 'email', 'type' => 'string', 'required' => true],
                            ['name' => 'password', 'type' => 'string', 'required' => true],
                        ],
                    ],
                ],
                [
                    'method' => 'POST',
                    'path' => '/logout',
                    'description' => 'Logout user',
                    'auth_required' => true,
                    'params' => [],
                ],

                // ========== Settings (Mobile) ==========
                [
                    'method' => 'GET',
                    'path' => '/settings',
                    'description' => 'Get all public settings (key-value for mobile app)',
                    'auth_required' => false,
                    'params' => [],
                ],
                [
                    'method' => 'POST',
                    'path' => '/admin/settings',
                    'description' => 'Create or update setting by key',
                    'auth_required' => true,
                    'params' => [
                        'body' => [
                            ['name' => 'key', 'type' => 'string', 'required' => true],
                            ['name' => 'value', 'type' => 'mixed', 'required' => false],
                            ['name' => 'type', 'type' => 'string', 'required' => true, 'description' => 'string|image|json|boolean'],
                            ['name' => 'group', 'type' => 'string', 'required' => false, 'description' => 'branding|ui|system'],
                            ['name' => 'is_public', 'type' => 'boolean', 'required' => false],
                        ],
                    ],
                ],
                [
                    'method' => 'POST',
                    'path' => '/admin/settings/upload',
                    'description' => 'Upload image and save as setting value',
                    'auth_required' => true,
                    'params' => [
                        'body' => [
                            ['name' => 'key', 'type' => 'string', 'required' => true],
                            ['name' => 'image', 'type' => 'file', 'required' => true, 'description' => 'image file'],
                            ['name' => 'group', 'type' => 'string', 'required' => false],
                            ['name' => 'is_public', 'type' => 'boolean', 'required' => false],
                        ],
                    ],
                ],

                // ========== History ==========
                [
                    'method' => 'GET',
                    'path' => '/history',
                    'description' => 'List history task (hanya status=1, tugas=4). Mengembalikan user_id, client_id, dan data task beserta relasi user & client.',
                    'auth_required' => true,
                    'params' => [],
                ],
                [
                    'method' => 'GET',
                    'path' => '/jadwal',
                    'description' => 'List jadwal dari DB atrindon_laravel untuk user login (id_team = users.id di DB tersebut, di-match lewat email sama). Relasi atrindonUser berisi id dan name_user.',
                    'auth_required' => true,
                    'params' => [
                        'query' => [
                            ['name' => 'month', 'type' => 'integer', 'required' => false, 'description' => 'Filter kolom month (1-12)'],
                            ['name' => 'year', 'type' => 'integer', 'required' => false, 'description' => 'Filter kolom years'],
                        ],
                    ],
                ],

                // ========== List Client (CRUD) ==========
                [
                    'method' => 'GET',
                    'path' => '/list/client',
                    'description' => 'List semua client',
                    'auth_required' => true,
                    'params' => [],
                ],
                [
                    'method' => 'GET',
                    'path' => '/list/client/{id}',
                    'description' => 'Detail client by id',
                    'auth_required' => true,
                    'params' => [
                        'path' => [
                            ['name' => 'id', 'type' => 'integer', 'required' => true, 'description' => 'id_cli'],
                        ],
                    ],
                ],
                [
                    'method' => 'POST',
                    'path' => '/list/client',
                    'description' => 'Tambah client baru',
                    'auth_required' => true,
                    'params' => [
                        'body' => [
                            ['name' => 'cabang', 'type' => 'integer', 'required' => true, 'description' => 'id_ca (cabang)'],
                            ['name' => 'nama_client', 'type' => 'string', 'required' => true],
                            ['name' => 'client_url', 'type' => 'string', 'required' => false],
                            ['name' => 'logo', 'type' => 'string', 'required' => false],
                        ],
                    ],
                ],
                [
                    'method' => 'PUT',
                    'path' => '/list/client/{id}',
                    'description' => 'Update client',
                    'auth_required' => true,
                    'params' => [
                        'path' => [
                            ['name' => 'id', 'type' => 'integer', 'required' => true, 'description' => 'id_cli'],
                        ],
                        'body' => [
                            ['name' => 'cabang', 'type' => 'integer', 'required' => false],
                            ['name' => 'nama_client', 'type' => 'string', 'required' => false],
                            ['name' => 'client_url', 'type' => 'string', 'required' => false],
                            ['name' => 'logo', 'type' => 'string', 'required' => false],
                        ],
                    ],
                ],
                [
                    'method' => 'DELETE',
                    'path' => '/list/client/{id}',
                    'description' => 'Hapus client',
                    'auth_required' => true,
                    'params' => [
                        'path' => [
                            ['name' => 'id', 'type' => 'integer', 'required' => true, 'description' => 'id_cli'],
                        ],
                    ],
                ],

                // ========== List Group (CRUD) ==========
                [
                    'method' => 'GET',
                    'path' => '/list/group',
                    'description' => 'List semua group',
                    'auth_required' => true,
                    'params' => [],
                ],
                [
                    'method' => 'GET',
                    'path' => '/list/group/{id}',
                    'description' => 'Detail group by id',
                    'auth_required' => true,
                    'params' => [
                        'path' => [
                            ['name' => 'id', 'type' => 'integer', 'required' => true, 'description' => 'id_group'],
                        ],
                    ],
                ],
                [
                    'method' => 'POST',
                    'path' => '/list/group',
                    'description' => 'Tambah group baru',
                    'auth_required' => true,
                    'params' => [
                        'body' => [
                            ['name' => 'nama_group', 'type' => 'string', 'required' => true],
                            ['name' => 'name_url', 'type' => 'string', 'required' => false],
                            ['name' => 'description', 'type' => 'string', 'required' => false],
                            ['name' => 'created_by', 'type' => 'integer', 'required' => false],
                        ],
                    ],
                ],
                [
                    'method' => 'PUT',
                    'path' => '/list/group/{id}',
                    'description' => 'Update group',
                    'auth_required' => true,
                    'params' => [
                        'path' => [
                            ['name' => 'id', 'type' => 'integer', 'required' => true, 'description' => 'id_group'],
                        ],
                        'body' => [
                            ['name' => 'nama_group', 'type' => 'string', 'required' => false],
                            ['name' => 'name_url', 'type' => 'string', 'required' => false],
                            ['name' => 'description', 'type' => 'string', 'required' => false],
                            ['name' => 'created_by', 'type' => 'integer', 'required' => false],
                        ],
                    ],
                ],
                [
                    'method' => 'DELETE',
                    'path' => '/list/group/{id}',
                    'description' => 'Hapus group',
                    'auth_required' => true,
                    'params' => [
                        'path' => [
                            ['name' => 'id', 'type' => 'integer', 'required' => true, 'description' => 'id_group'],
                        ],
                    ],
                ],

                // ========== Lainnya ==========
                [
                    'method' => 'GET',
                    'path' => '/me/clients',
                    'description' => 'Client milik user yang login',
                    'auth_required' => true,
                    'params' => [],
                ],
                [
                    'method' => 'GET',
                    'path' => '/clients',
                    'description' => 'List clients (alternatif)',
                    'auth_required' => true,
                    'params' => [],
                ],
                [
                    'method' => 'GET',
                    'path' => '/users/{id}/clients',
                    'description' => 'Clients by user id',
                    'auth_required' => true,
                    'params' => [
                        'path' => [
                            ['name' => 'id', 'type' => 'integer', 'required' => true, 'description' => 'user id'],
                        ],
                    ],
                ],
                [
                    'method' => 'GET',
                    'path' => '/groups',
                    'description' => 'List groups (alternatif)',
                    'auth_required' => true,
                    'params' => [],
                ],
                [
                    'method' => 'POST',
                    'path' => '/clientUser/add',
                    'description' => 'Tambah user ke client',
                    'auth_required' => true,
                    'params' => ['body' => 'Lihat request body di controller'],
                ],
                [
                    'method' => 'POST',
                    'path' => '/clientUser/edit/{id}',
                    'description' => 'Edit user client',
                    'auth_required' => true,
                    'params' => ['path' => [['name' => 'id', 'type' => 'integer', 'required' => true]]],
                ],
                [
                    'method' => 'DELETE',
                    'path' => '/clientUser/delete/{id}',
                    'description' => 'Hapus user client',
                    'auth_required' => true,
                    'params' => ['path' => [['name' => 'id', 'type' => 'integer', 'required' => true]]],
                ],
                [
                    'method' => 'POST',
                    'path' => '/groupUser/add',
                    'description' => 'Tambah user ke group',
                    'auth_required' => true,
                    'params' => ['body' => 'Lihat request body di controller'],
                ],
                [
                    'method' => 'PUT',
                    'path' => '/groupUser/edit/{id}',
                    'description' => 'Edit group user',
                    'auth_required' => true,
                    'params' => ['path' => [['name' => 'id', 'type' => 'integer', 'required' => true]]],
                ],
                [
                    'method' => 'DELETE',
                    'path' => '/groupUser/delete/{id}',
                    'description' => 'Hapus group user',
                    'auth_required' => true,
                    'params' => ['path' => [['name' => 'id', 'type' => 'integer', 'required' => true]]],
                ],
            ],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Dokumentasi API',
            'data' => $docs,
        ]);
    }
}
