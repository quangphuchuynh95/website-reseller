<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('wr_package_prices', function (Blueprint $table): void {
            $table->foreignId('subscription_period_id')
                ->nullable()
                ->after('package_id')
                ->constrained('wr_subscription_periods')
                ->nullOnDelete();
        });

        Schema::table('wr_subscriptions', function (Blueprint $table): void {
            $table->foreignId('subscription_period_id')
                ->nullable()
                ->after('package_price_id')
                ->constrained('wr_subscription_periods')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('wr_package_prices', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('subscription_period_id');
        });

        Schema::table('wr_subscriptions', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('subscription_period_id');
        });
    }
};
