<?php

namespace QuangPhuc\WebsiteReseller\Services;

class CaddyService
{
    public function __construct(string $configPath)
    {
    }

    public function reloadCaddyService()
    {

    }

    public function putWebsiteConfig(string $domain, string $caddyFileContent): void
    {
        file_put_contents($domain, $caddyFileContent);
    }
}
