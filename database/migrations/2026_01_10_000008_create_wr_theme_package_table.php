<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('wr_theme_package', function (Blueprint $table): void {
            $table->foreignId('theme_id')->constrained('wr_themes')->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('wr_packages')->cascadeOnDelete();
            $table->primary(['theme_id', 'package_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wr_theme_package');
    }
};
