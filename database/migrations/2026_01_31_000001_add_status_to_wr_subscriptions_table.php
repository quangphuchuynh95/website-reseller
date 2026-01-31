<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use QuangPhuc\WebsiteReseller\Enums\SubscriptionStatusEnum;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('wr_subscriptions', function (Blueprint $table): void {
            $table->string('status', 50)
                ->default(SubscriptionStatusEnum::PENDING);
        });
    }

    public function down(): void
    {
        Schema::table('wr_subscriptions', function (Blueprint $table): void {
            $table->dropColumn('status');
        });
    }
};
