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
        Schema::create('wr_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('wr_customers')->cascadeOnDelete();
            $table->foreignId('theme_id')->nullable()->constrained('wr_themes')->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('wr_packages')->nullOnDelete();
            $table->foreignId('package_price_id')->nullable()->constrained('wr_package_prices')->nullOnDelete();
            $table->foreignId('subscription_period_id')->nullable()->constrained('wr_subscription_periods')->nullOnDelete();
            $table->string('name');
            $table->decimal('commit_price', 15, 2);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('next_expires_at')->nullable();
            $table->string('status');
            $table->string('charge_id')->nullable();
            $table->string('domain')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wr_subscriptions');
    }
};
