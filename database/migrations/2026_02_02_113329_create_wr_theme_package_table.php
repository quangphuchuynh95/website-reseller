<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wr_theme_package', function (Blueprint $table) {
            $table->foreignId('theme_id')->constrained('wr_themes')->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('wr_packages')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['theme_id', 'package_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wr_theme_package');
    }
};
