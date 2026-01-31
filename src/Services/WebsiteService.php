<?php

namespace QuangPhuc\WebsiteReseller\Services;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use QuangPhuc\WebsiteReseller\Models\Website;

class WebsiteService
{


    public function setupWebsite(Website $website)
    {
        Schema::connection('websites')->createDatabase($website->database_name);

        $this->buildCaddyFile($website);

        Process::command($website->sourceCode->setup_command)
            ->env([
                'DB_HOST' => config('database.connections.children_website.host'),
                'DB_PORT' => config('database.connections.children_website.port'),
                'DB_DATABASE' => config('database.connections.children_website.database'),
                'DB_USERNAME' => config('database.connections.children_website.username'),
                'DB_PASSWORD' => config('database.connections.children_website.password'),
            ])
            ->path($website)
            ->run()
            ->throw();

        app(CaddyService::class)->reloadCaddyService();


        // Create Caddy file
        // Run setup script
        //    - [ ] Installer script
        //        - [ ] InstallController (received lang)
        //        - [ ] EnvironmentController (received database)
        //        - [ ] AccountController (received superadmin information)

        // Reload caddy service
    }

    public function buildCaddyFile(Website $website): void
    {
        $renderedCaddy = Blade::render($website->sourceCode->caddy_template, [
            'website' => $website,
            'database' => [
                ...config('database.connections.children_website'),
                'database' => $website->database_name,
            ],
        ]);

        app(CaddyService::class)->putWebsiteConfig($website->domain, $renderedCaddy);
    }
}
