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
        Schema::create('wr_websites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('wr_customers')->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('wr_subscriptions')->nullOnDelete();
            $table->foreignId('theme_id')->nullable()->constrained('wr_themes')->nullOnDelete();
            $table->foreignId('source_code_id')->nullable()->constrained('wr_source_codes')->nullOnDelete();
            $table->string('domain')->unique();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wr_websites');
    }
};
