<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('wr_package_prices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('package_id')->constrained('wr_packages')->cascadeOnDelete();
            $table->string('name', 255);
            $table->integer('sequence')->default(0);
            $table->string('payment_interval', 50)->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wr_package_prices');
    }
};
