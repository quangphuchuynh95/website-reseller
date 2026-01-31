<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('wr_theme_category', function (Blueprint $table): void {
            $table->foreignId('theme_id')->constrained('wr_themes')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('wr_categories')->cascadeOnDelete();
            $table->primary(['theme_id', 'category_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wr_theme_category');
    }
};
