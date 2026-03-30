<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    public const CACHE_KEY = 'app_settings';

    public const CACHE_TTL_SECONDS = 3600;

    /**
     * Get all public settings as key-value array (for mobile API).
     * Uses cache; images get full URL.
     */
    public function getPublicSettings(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            $settings = Setting::public()->get();
            $result = [];

            foreach ($settings as $setting) {
                $value = $setting->value;

                if ($setting->type === 'image' && $value !== null) {
                    $path = is_array($value) ? ($value['path'] ?? $value[0] ?? $value) : $value;
                    $result[$setting->key] = $path ? Storage::disk('public')->url($path) : null;
                    continue;
                }

                if ($setting->type === 'json' && is_array($value)) {
                    $result[$setting->key] = $this->resolveJsonValueWithImageUrls($value);
                    continue;
                }

                $result[$setting->key] = $value;
            }

            return $result;
        });
    }

    /**
     * Recursively resolve image paths to full URLs in arrays (e.g. home_banners).
     */
    protected function resolveJsonValueWithImageUrls(array $data): array
    {
        $out = [];

        foreach ($data as $index => $item) {
            if (! is_array($item)) {
                $out[$index] = $item;
                continue;
            }

            $row = [];
            foreach ($item as $k => $v) {
                if ($k === 'image' && is_string($v) && $v !== '') {
                    $row[$k] = Storage::disk('public')->url('settings/' . ltrim($v, '/'));
                } else {
                    $row[$k] = is_array($v) ? $this->resolveJsonValueWithImageUrls($v) : $v;
                }
            }
            $out[$index] = $row;
        }

        return $out;
    }

    /**
     * Create or update a setting by key. Clears cache after save.
     */
    public function storeOrUpdateByKey(
        string $key,
        mixed $value,
        string $type,
        ?string $group = null,
        bool $is_public = true
    ): Setting {
        $normalized = $this->normalizeValueForStorage($value, $type);

        $setting = Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $normalized,
                'type' => $type,
                'group' => $group,
                'is_public' => $is_public,
            ]
        );

        Cache::forget(self::CACHE_KEY);

        return $setting;
    }

    /**
     * Normalize value for DB storage by type.
     */
    protected function normalizeValueForStorage(mixed $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => is_string($value) ? json_decode($value, true) : $value,
            default => $value,
        };
    }

    /**
     * Store uploaded image in storage/app/public/settings and return path relative to public disk.
     */
    public function uploadImage(UploadedFile $file): string
    {
        $path = $file->store('settings', 'public');

        return $path;
    }
}
