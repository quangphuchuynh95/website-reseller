<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('wr_websites', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('subscription_id')->nullable()->constrained('wr_subscriptions')->nullOnDelete();
            $table->foreignId('theme_id')->nullable()->constrained('wr_themes')->nullOnDelete();
            $table->foreignId('source_code_id')->nullable()->constrained('wr_source_codes')->nullOnDelete();
            $table->string('domain')->nullable();
            $table->string('status', 60)->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wr_websites');
    }
};
