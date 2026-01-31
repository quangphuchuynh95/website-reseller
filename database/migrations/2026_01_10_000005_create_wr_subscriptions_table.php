<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('wr_subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('package_id')->nullable()->constrained('wr_packages')->nullOnDelete();
            $table->foreignId('package_price_id')->nullable()->constrained('wr_package_prices')->nullOnDelete();
            $table->string('name', 255);
            $table->decimal('commit_price', 15, 2)->default(0);
            $table->string('payment_interval', 50)->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('next_expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wr_subscriptions');
    }
};
