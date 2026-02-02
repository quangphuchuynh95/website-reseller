<?php

namespace QuangPhuc\WebsiteReseller\Services;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use QuangPhuc\WebsiteReseller\Models\Website;

class WebsiteService
{
    public function setupWebsite(Website $website): void
    {
        $website->loadMissing(['theme', 'sourceCode']);

        $databaseName = $this->slugifyDomain($website->domain);

        $this->createDatabase($databaseName);

        $this->importDatabaseFile($website, $databaseName);

        $this->runSetupCommand($website, $databaseName);

        $this->buildCaddyFile($website);

        app(CaddyService::class)->reloadCaddyService();
    }

    public function slugifyDomain(string $domain): string
    {
        return Str::slug($domain, '_');
    }

    protected function createDatabase(string $databaseName): void
    {
        Schema::connection('children_website')->createDatabase($databaseName);

        Log::info("Database created: {$databaseName}");
    }

    protected function importDatabaseFile(Website $website, string $databaseName): void
    {
        $databaseFile = $website->theme?->database_file;

        if (! $databaseFile || ! Storage::exists($databaseFile)) {
            return;
        }

        $filePath = Storage::path($databaseFile);

        $dbHost = config('database.connections.children_website.host');
        $dbPort = config('database.connections.children_website.port');
        $dbUser = config('database.connections.children_website.username');
        $dbPassword = config('database.connections.children_website.password');

        Process::run(sprintf(
            'mysql -h %s -P %s -u %s -p%s %s < %s',
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            escapeshellarg($dbPassword),
            escapeshellarg($databaseName),
            escapeshellarg($filePath)
        ))->throw();

        Log::info("Database imported for website {$website->domain}");
    }

    protected function runSetupCommand(Website $website, string $databaseName): void
    {
        $setupCommand = $website->sourceCode?->setup_command;

        if (! $setupCommand) {
            return;
        }

        Process::command($setupCommand)
            ->env([
                'DOMAIN' => $website->domain,
                'SLUGIFIED_DOMAIN' => $this->slugifyDomain($website->domain),
                'DB_HOST' => config('database.connections.children_website.host'),
                'DB_PORT' => config('database.connections.children_website.port'),
                'DB_DATABASE' => $databaseName,
                'DB_USERNAME' => config('database.connections.children_website.username'),
                'DB_PASSWORD' => config('database.connections.children_website.password'),
            ])
            ->run()
            ->throw();

        Log::info("Setup command executed for website {$website->domain}");
    }

    public function buildCaddyFile(Website $website): void
    {
        $caddyTemplate = $website->sourceCode?->caddy_template;

        if (! $caddyTemplate) {
            return;
        }

        $slugifiedDomain = $this->slugifyDomain($website->domain);

        $renderedCaddy = Blade::render($caddyTemplate, [
            'website' => $website,
            'database' => [
                ...config('database.connections.websites'),
                'database' => $slugifiedDomain,
            ],
        ]);

        app(CaddyService::class)->putWebsiteConfig($slugifiedDomain, $renderedCaddy);
    }
}
