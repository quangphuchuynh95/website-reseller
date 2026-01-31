<?php

namespace QuangPhuc\WebsiteReseller\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use QuangPhuc\WebsiteReseller\Models\SourceCode;

class SourceCodeService
{
    public function __construct(public string $basePath)
    {
    }



    public function publishSourceCode(UploadedFile $file, SourceCode $sourceCode): void
    {
        // Create target directory path based on slug
        $targetPath = $this->basePath . '/' . $sourceCode->slug;

        // Clean existing directory to avoid orphaned files from previous versions
        if (File::exists($targetPath)) {
            File::cleanDirectory($targetPath);
        } else {
            File::makeDirectory($targetPath, 0755, true);
        }

        // Save the uploaded zip file temporarily
        $tempZipPath = storage_path('app/temp/' . uniqid('source_code_') . '.zip');
        File::ensureDirectoryExists(dirname($tempZipPath));
        $file->move(dirname($tempZipPath), basename($tempZipPath));

        // Unzip the file using the unzip command
        $result = Process::run("unzip -o {$tempZipPath} -d {$targetPath}");

        // Clean up the temporary zip file
        File::delete($tempZipPath);

        // Check if unzip was successful
        if ($result->failed()) {
            throw new \RuntimeException('Failed to unzip source code: ' . $result->errorOutput());
        }
    }

    public function deleteSourceCode(SourceCode $sourceCode): void
    {
        $targetPath = $this->basePath . '/' . $sourceCode->slug;
        $result = Process::run(["rm", "-rf", $targetPath]);

        // Check if unzip was successful
        if ($result->failed()) {
            throw new \RuntimeException('Failed to delete source code: ' . $result->errorOutput());
        }
    }
}
