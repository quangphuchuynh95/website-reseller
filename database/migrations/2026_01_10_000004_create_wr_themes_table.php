<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('wr_themes', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->string('image')->nullable();
            $table->string('preview_url')->nullable();
            $table->string('database_file')->nullable();
            $table->foreignId('source_code_id')->nullable()->constrained('wr_source_codes')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wr_themes');
    }
};
