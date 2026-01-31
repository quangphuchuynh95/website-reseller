<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;
use QuangPhuc\WebsiteReseller\Http\Controllers\CategoryController;
use QuangPhuc\WebsiteReseller\Http\Controllers\CustomerController;
use QuangPhuc\WebsiteReseller\Http\Controllers\PackageController;
use QuangPhuc\WebsiteReseller\Http\Controllers\PackagePriceController;
use QuangPhuc\WebsiteReseller\Http\Controllers\Public\PackagePriceSelectionController;
use QuangPhuc\WebsiteReseller\Http\Controllers\ThemeController;
use QuangPhuc\WebsiteReseller\Http\Controllers\SourceCodeController;
use QuangPhuc\WebsiteReseller\Http\Controllers\SubscriptionController;
use QuangPhuc\WebsiteReseller\Http\Controllers\SubscriptionPeriodController;
use QuangPhuc\WebsiteReseller\Http\Controllers\WebsiteController;
use QuangPhuc\WebsiteReseller\Http\Controllers\Public\Auth\LoginController;
use QuangPhuc\WebsiteReseller\Http\Controllers\Public\Auth\LogoutController;
use QuangPhuc\WebsiteReseller\Http\Controllers\Public\Auth\RegisterController;
use QuangPhuc\WebsiteReseller\Http\Controllers\Public\CheckoutController;
use QuangPhuc\WebsiteReseller\Http\Controllers\Public\PackageSelectionController;
use QuangPhuc\WebsiteReseller\Http\Controllers\Public\ThemesIndexController;
use QuangPhuc\WebsiteReseller\Http\Controllers\Public\ThemePreviewController;
use QuangPhuc\WebsiteReseller\Http\Controllers\Public\UserWebsiteController;
use QuangPhuc\WebsiteReseller\Http\Middleware\RedirectIfNotCustomer;


Theme::registerRoutes(function (): void {
    Route::name('wr.front.')->group(function () {
        // Authentication routes
        Route::prefix('customer')->name('customer.')->group(function () {
            Route::name('auth.')->group(function () {
                Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
                Route::post('login', [LoginController::class, 'login'])->name('login.post');
                Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
                Route::post('register', [RegisterController::class, 'register'])->name('register.post');
                Route::post('logout', LogoutController::class)->name('logout');
            });

            // User routes (protected)
            Route::middleware(RedirectIfNotCustomer::class)->group(function () {
                Route::get('websites', UserWebsiteController::class)->name('websites');
            });
        });

        Route::name('theme.')->group(function () {
            // Theme routes
            Route::get('themes', ThemesIndexController::class)->name('index');
//        Route::get('themes/{category}', ThemeCategoryController::class)->name('public.themes.category');
//        Route::get('themes/detail/{theme}', ThemeDetailController::class)->name('public.themes.detail');
            Route::get('theme/preview/{theme}', ThemePreviewController::class)->name('preview');
        });

        Route::name('website.')->prefix('website')->group(function () {
            // Order routes
            Route::get('order/{theme}/package', PackageSelectionController::class)->name('order.package');
//            Route::get('order/{theme}/{package}/price', PackagePriceSelectionController::class)->name('order.package_price');

            Route::middleware(RedirectIfNotCustomer::class)->group(function () {
                Route::get('order/{theme}/{package}/{price}/checkout', [CheckoutController::class, 'getCheckout'])->name('order.checkout');
                Route::post('order/{theme}/{package}/{price}/checkout', [CheckoutController::class, 'postCheckout'])->name('order.checkout.post');

                // Checkout callback routes
                Route::get('order/checkout/success/{token?}', [CheckoutController::class, 'getCheckoutSuccess'])->name('order.checkout.success');
                Route::get('order/checkout/cancel/{token?}', [CheckoutController::class, 'getCheckoutCancel'])->name('order.checkout.cancel');
                Route::get('order/checkout/bank-transfer/{token?}', [CheckoutController::class, 'getBankTransferInfo'])->name('order.checkout.bank-transfer');
            });
        });
    });
});


AdminHelper::registerRoutes(function () {
    Route::prefix('website-reseller')->name('website-reseller.')->group(function () {
        // Categories
        Route::resource('categories', CategoryController::class)->parameters([
            'categories' => 'category',
        ]);

        // Customers
        Route::resource('customers', CustomerController::class)->parameters([
            'customers' => 'customer',
        ]);

        // Packages
        Route::resource('packages', PackageController::class)->parameters([
            'packages' => 'package',
        ]);

        // Package Prices
        Route::resource('package-prices', PackagePriceController::class)->parameters([
            'package-prices' => 'packagePrice',
        ]);

        // Subscription Periods
        Route::resource('subscription-periods', SubscriptionPeriodController::class)->parameters([
            'subscription-periods' => 'subscriptionPeriod',
        ]);

        // Themes
        Route::resource('themes', ThemeController::class)->parameters([
            'themes' => 'theme',
        ]);

        // Source Codes
        Route::resource('source-codes', SourceCodeController::class)->parameters([
            'source-codes' => 'sourceCode',
        ]);

        // Subscriptions
        Route::resource('subscriptions', SubscriptionController::class)->parameters([
            'subscriptions' => 'subscription',
        ]);

        // Websites
        Route::resource('websites', WebsiteController::class)->parameters([
            'websites' => 'website',
        ]);
    });
});
