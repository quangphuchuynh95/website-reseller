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
        Schema::create('wr_subscription_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('interval_value')->comment('ISO 8601 duration format: P1D, P1W, P1M, P3M, P1Y');
            $table->integer('sequence')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wr_subscription_periods');
    }
};
