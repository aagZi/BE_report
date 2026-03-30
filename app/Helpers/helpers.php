<?php

use App\Services\SettingService;

if (! function_exists('setting')) {
    /**
     * Get a public setting value by key (uses cached app_settings).
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        $settings = app(SettingService::class)->getPublicSettings();

        return $settings[$key] ?? $default;
    }
}
