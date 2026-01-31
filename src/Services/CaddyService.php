<?php

namespace QuangPhuc\WebsiteReseller\Services;

use Illuminate\Support\Facades\Storage;

class CaddyService
{
    public function __construct(string $configPath)
    {
    }

    public function reloadCaddyService(): void
    {
    }

    public function putWebsiteConfig(string $slugifiedDomain, string $caddyFileContent): void
    {
        Storage::disk('local')->put("caddyfiles/{$slugifiedDomain}", $caddyFileContent);
    }
}
