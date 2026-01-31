<?php

namespace QuangPhuc\WebsiteReseller;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function activated(): void
    {
        // Run migrations when plugin is activated
    }

    public static function remove(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('wr_subscription_payments');
        Schema::dropIfExists('wr_websites');
        Schema::dropIfExists('wr_subscriptions');
        Schema::dropIfExists('wr_theme_package');
        Schema::dropIfExists('wr_themes');
        Schema::dropIfExists('wr_package_prices');
        Schema::dropIfExists('wr_packages');
        Schema::dropIfExists('wr_source_codes');
    }
}
