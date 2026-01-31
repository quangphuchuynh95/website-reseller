<?php

namespace QuangPhuc\WebsiteReseller\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Slug\Facades\SlugHelper;
use QuangPhuc\WebsiteReseller\Models\Package;
use QuangPhuc\WebsiteReseller\Models\Theme;
use QuangPhuc\WebsiteReseller\Models\Category;
use QuangPhuc\WebsiteReseller\Services\CaddyService;
use QuangPhuc\WebsiteReseller\Services\SourceCodeService;
use QuangPhuc\WebsiteReseller\Services\WebsiteService;

class WebsiteResellerServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->register(HookServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(SourceCodeService::class, function () {
            return new SourceCodeService(
                basePath: config('plugins.website-reseller.source-code.base')
            );
        });

        $this->app->singleton(CaddyService::class, function () {
            return new CaddyService(
                configPath: config('plugins.website-reseller.caddy.config_path')
            );
        });
        $this->app->singleton(WebsiteService::class, function () {
            return new WebsiteService();
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/website-reseller')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'caddy', 'source-code', 'auth'])
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadMigrations();

        $this->mergeConfigFrom($this->getConfigFilePath("database"), "database");

        // Merge auth config
        config([
            'auth.guards.wr_customer' => config('plugins.website-reseller.auth.guards.wr_customer'),
            'auth.providers.wr_customers' => config('plugins.website-reseller.auth.providers.wr_customers'),
            'auth.passwords.wr_customers' => config('plugins.website-reseller.auth.passwords.wr_customers'),
        ]);

        DashboardMenu::default()->beforeRetrieving(function () {
            DashboardMenu::registerItem([
                'id' => 'cms-plugins-website-reseller',
                'priority' => 50,
                'parent_id' => null,
                'name' => 'Website Reseller',
                'icon' => 'ti ti-world',
                'permissions' => ['website-reseller.websites.index'],
            ])
            ->registerItem([
                'id' => 'cms-plugins-website-reseller-categories',
                'priority' => 1,
                'parent_id' => 'cms-plugins-website-reseller',
                'name' => 'Categories',
                'icon' => 'ti ti-category',
                'url' => route('website-reseller.categories.index'),
                'permissions' => ['website-reseller.categories.index'],
            ])
            ->registerItem([
                'id' => 'cms-plugins-website-reseller-customers',
                'priority' => 2,
                'parent_id' => 'cms-plugins-website-reseller',
                'name' => 'Customers',
                'icon' => 'ti ti-users',
                'url' => route('website-reseller.customers.index'),
                'permissions' => ['website-reseller.customers.index'],
            ])
            ->registerItem([
                'id' => 'cms-plugins-website-reseller-websites',
                'priority' => 3,
                'parent_id' => 'cms-plugins-website-reseller',
                'name' => 'Websites',
                'icon' => 'ti ti-world-www',
                'url' => route('website-reseller.websites.index'),
                'permissions' => ['website-reseller.websites.index'],
            ])
            ->registerItem([
                'id' => 'cms-plugins-website-reseller-packages',
                'priority' => 4,
                'parent_id' => 'cms-plugins-website-reseller',
                'name' => 'Packages',
                'icon' => 'ti ti-package',
                'url' => route('website-reseller.packages.index'),
                'permissions' => ['website-reseller.packages.index'],
            ])
            ->registerItem([
                'id' => 'cms-plugins-website-reseller-package-prices',
                'priority' => 5,
                'parent_id' => 'cms-plugins-website-reseller',
                'name' => 'Package Prices',
                'icon' => 'ti ti-currency-dollar',
                'url' => route('website-reseller.package-prices.index'),
                'permissions' => ['website-reseller.package-prices.index'],
            ])
            ->registerItem([
                'id' => 'cms-plugins-website-reseller-themes',
                'priority' => 6,
                'parent_id' => 'cms-plugins-website-reseller',
                'name' => 'Themes',
                'icon' => 'ti ti-palette',
                'url' => route('website-reseller.themes.index'),
                'permissions' => ['website-reseller.themes.index'],
            ])
            ->registerItem([
                'id' => 'cms-plugins-website-reseller-source-codes',
                'priority' => 7,
                'parent_id' => 'cms-plugins-website-reseller',
                'name' => 'Source Codes',
                'icon' => 'ti ti-code',
                'url' => route('website-reseller.source-codes.index'),
                'permissions' => ['website-reseller.source-codes.index'],
            ])
            ->registerItem([
                'id' => 'cms-plugins-website-reseller-subscriptions',
                'priority' => 8,
                'parent_id' => 'cms-plugins-website-reseller',
                'name' => 'Subscriptions',
                'icon' => 'ti ti-repeat',
                'url' => route('website-reseller.subscriptions.index'),
                'permissions' => ['website-reseller.subscriptions.index'],
            ]);
        });

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(Category::class, fn () => trans('plugins/blog::base.blog_posts'));
            SlugHelper::registerModule(Theme::class, fn () => trans('plugins/blog::base.blog_categories'));
            SlugHelper::registerModule(Package::class, fn () => trans('plugins/blog::base.blog_tags'));

            SlugHelper::setPrefix(Category::class, 'category');
            SlugHelper::setPrefix(Theme::class, 'theme');
            SlugHelper::setPrefix(Package::class, 'package');
        });
    }
}
