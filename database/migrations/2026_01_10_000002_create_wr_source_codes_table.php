<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('wr_source_codes', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->string('slug')->unique();
            $table->text('caddy_template')->nullable();
            $table->text('setup_command')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wr_source_codes');
    }
};
