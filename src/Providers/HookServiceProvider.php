<?php

namespace QuangPhuc\WebsiteReseller\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Botble\Slug\Facades\SlugHelper;
use QuangPhuc\WebsiteReseller\Models\Package;
use QuangPhuc\WebsiteReseller\Models\Theme;
use QuangPhuc\WebsiteReseller\Models\Category;
use QuangPhuc\WebsiteReseller\Services\CaddyService;
use QuangPhuc\WebsiteReseller\Services\PublicViewService;
use QuangPhuc\WebsiteReseller\Services\SourceCodeService;
use QuangPhuc\WebsiteReseller\Services\SubscriptionService;
use QuangPhuc\WebsiteReseller\Services\WebsiteService;
use QuangPhuc\WebsiteReseller\Supports\CheckoutHelper;

class HookServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
    }

    public function boot(): void
    {
        add_filter(BASE_FILTER_PUBLIC_SINGLE_DATA, [app(PublicViewService::class), 'handleFrontRoutes'], 2);

        $this->registerPaymentFilters();
    }

    protected function registerPaymentFilters(): void
    {
        if (! is_plugin_active('payment')) {
            return;
        }

        // Define success redirect URL
        add_filter(PAYMENT_FILTER_REDIRECT_URL, function ($checkoutToken) {
            $checkoutData = CheckoutHelper::getCheckoutData($checkoutToken);

            if (empty($checkoutData['theme_id'])) {
                return $checkoutToken;
            }

            return route('wr.front.website.order.checkout.success', $checkoutToken ?: '');
        }, 120);

        // Define cancel/failure redirect URL
        add_filter(PAYMENT_FILTER_CANCEL_URL, function ($checkoutToken) {
            $checkoutData = CheckoutHelper::getCheckoutData($checkoutToken);

            if (empty($checkoutData['theme_id'])) {
                return $checkoutToken;
            }

            return route('wr.front.website.order.checkout.cancel', $checkoutToken ?: '');
        }, 120);

        // Listen for payment processed
        add_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [$this, 'handlePaymentProcessed'], 120, 1);

        // Listen for payment status updates
        add_action(ACTION_AFTER_UPDATE_PAYMENT, [$this, 'handlePaymentUpdated'], 120, 2);
    }

    public function handlePaymentProcessed(array $data): void
    {
        // This is called when a payment is processed (COD, Bank Transfer, etc.)
        // The actual order/subscription creation happens in CheckoutController
    }

    public function handlePaymentUpdated($request, Payment $payment): void
    {
        $subscriptionService = $this->app->make(SubscriptionService::class);
        if ($payment->status === PaymentStatusEnum::COMPLETED) {
            $subscriptionService->activateSubscription($payment->charge_id);
            // Payment completed - can trigger additional actions here
            // e.g., activate the subscription, send confirmation emails
        }

        if ($payment->status === PaymentStatusEnum::FAILED) {
            // Payment failed - handle failure
        }
    }
}
