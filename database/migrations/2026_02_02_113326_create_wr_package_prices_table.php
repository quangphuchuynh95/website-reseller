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
        Schema::create('wr_package_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('wr_packages')->cascadeOnDelete();
            $table->foreignId('subscription_period_id')->constrained('wr_subscription_periods')->cascadeOnDelete();
            $table->string('name');
            $table->integer('sequence')->default(0);
            $table->string('payment_interval');
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wr_package_prices');
    }
};
