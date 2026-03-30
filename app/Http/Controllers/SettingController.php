<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrUpdateSettingRequest;
use App\Http\Requests\UploadSettingImageRequest;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct(
        private SettingService $settingService
    ) {}

    /**
     * GET /api/settings — public, returns all is_public settings as key-value.
     */
    public function index(): JsonResponse
    {
        $data = $this->settingService->getPublicSettings();

        return response()->json([
            'success' => true,
            'message' => 'Success get settings',
            'data' => $data,
        ]);
    }

    /**
     * POST /api/admin/settings — create or update setting by key.
     */
    public function storeOrUpdate(StoreOrUpdateSettingRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $setting = $this->settingService->storeOrUpdateByKey(
            key: $validated['key'],
            value: $validated['value'] ?? null,
            type: $validated['type'],
            group: $validated['group'] ?? null,
            is_public: $validated['is_public'] ?? true
        );

        return response()->json([
            'success' => true,
            'message' => 'Setting saved successfully',
            'data' => $setting->fresh(),
        ], 201);
    }

    /**
     * POST /api/admin/settings/upload — upload image and save path as setting value.
     */
    public function upload(UploadSettingImageRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $path = $this->settingService->uploadImage($request->file('image'));

        $setting = $this->settingService->storeOrUpdateByKey(
            key: $validated['key'],
            value: $path,
            type: 'image',
            group: $validated['group'] ?? null,
            is_public: $validated['is_public'] ?? true
        );

        $fullUrl = Storage::disk('public')->url($path);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded and setting saved successfully',
            'data' => [
                'setting' => $setting->fresh(),
                'url' => $fullUrl,
            ],
        ], 201);
    }
}
