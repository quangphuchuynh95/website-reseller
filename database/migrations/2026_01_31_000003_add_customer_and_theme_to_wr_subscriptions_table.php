<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('wr_subscriptions', function (Blueprint $table): void {
            $table->foreignId('customer_id')->nullable()->after('id')->constrained('wr_customers')->nullOnDelete();
            $table->foreignId('theme_id')->nullable()->after('customer_id')->constrained('wr_themes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('wr_subscriptions', function (Blueprint $table): void {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['theme_id']);
            $table->dropColumn(['customer_id', 'theme_id']);
        });
    }
};
