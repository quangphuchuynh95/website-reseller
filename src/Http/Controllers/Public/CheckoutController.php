<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Facades\PaymentMethods;
use Botble\Payment\Models\Payment;
use Botble\Payment\Services\Gateways\BankTransferPaymentService;
use Botble\Payment\Services\Gateways\CodPaymentService;
use Botble\Payment\Supports\PaymentHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use QuangPhuc\WebsiteReseller\Models;
use QuangPhuc\WebsiteReseller\Supports\CheckoutHelper;

class CheckoutController extends BaseController
{
    public function getCheckout(Models\Theme $theme, Models\Package $package, Models\PackagePrice $price)
    {
        if (! is_plugin_active('payment')) {
            abort(404, 'Payment plugin is not active.');
        }


        // Generate checkout token and store checkout data
        $token = CheckoutHelper::getCheckoutToken();

        CheckoutHelper::setCheckoutData([
            'theme_id' => $theme->id,
            'package_id' => $package->id,
            'package_price_id' => $price->id,
            'amount' => $price->price,
            'payment_interval' => $price->payment_interval,
            'customer_id' => auth('wr_customer')->id(),
        ]);

        return Theme::scope('website-reseller.order.checkout', [
            'token' => $token,
            'theme' => $theme,
            'package' => $package,
            'price' => $price,
            'paymentMethods' => PaymentMethods::render(),
            'amount' => $price->price,
            'currency' => get_application_currency()->title ?? 'USD',
        ])->render();
    }

    public function postCheckout(Request $request, Models\Theme $theme, Models\Package $package, Models\PackagePrice $price)
    {
        if (! is_plugin_active('payment')) {
            return $this->httpResponse()
                ->setError()
                ->setMessage(__('Payment plugin is not active.'));
        }

        // Ensure customer is authenticated
        if (! auth('wr_customer')->check()) {
            return $this->httpResponse()
                ->setError()
                ->setMessage(__('Please login to continue checkout.'));
        }

        $token = CheckoutHelper::getCheckoutToken();
        $paymentMethod = $request->input('payment_method');
        $amount = $price->price;
        $currency = get_application_currency()->title ?? 'USD';
        $customer = auth('wr_customer')->user();

        // Validate payment method
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        // Store checkout data
        CheckoutHelper::setCheckoutData([
            'theme_id' => $theme->id,
            'package_id' => $package->id,
            'package_price_id' => $price->id,
            'amount' => $amount,
            'payment_interval' => $price->payment_interval,
            'customer_id' => $customer->getKey(),
            'payment_method' => $paymentMethod,
        ]);

        // Prepare payment data
        $paymentData = [
            'error' => false,
            'message' => false,
            'amount' => $amount,
            'currency' => $currency,
            'type' => $paymentMethod,
            'charge_id' => null,
            'order_id' => [],
            'checkout_token' => $token,
            'customer_id' => $customer->getKey(),
            'customer_type' => get_class($customer),
        ];

        // Process payment by method
        switch ($paymentMethod) {
            case PaymentMethodEnum::COD:
                $paymentData['charge_id'] = app(CodPaymentService::class)->execute([
                    'amount' => $amount,
                    'currency' => $currency,
                    'customer_id' => $customer->getKey(),
                    'customer_type' => get_class($customer),
                    'order_id' => [],
                ]);
                break;

            case PaymentMethodEnum::BANK_TRANSFER:
                $paymentData['charge_id'] = app(BankTransferPaymentService::class)->execute([
                    'amount' => $amount,
                    'currency' => $currency,
                    'customer_id' => $customer->getKey(),
                    'customer_type' => get_class($customer),
                    'order_id' => [],
                ]);
                break;

            default:
                // Third-party payment gateways (Stripe, PayPal, etc.)
                $paymentData = apply_filters(PAYMENT_FILTER_AFTER_POST_CHECKOUT, $paymentData, $request);
                break;
        }

        // Handle external gateway redirect
        if ($checkoutUrl = Arr::get($paymentData, 'checkoutUrl')) {
            return $this->httpResponse()
                ->setNextUrl($checkoutUrl);
        }

        // Handle payment errors
        if ($paymentData['error'] || empty($paymentData['charge_id'])) {
            return $this->httpResponse()
                ->setError()
                ->setMessage($paymentData['message'] ?: __('Payment failed!'))
                ->setNextUrl(PaymentHelper::getCancelURL($token));
        }

        // Create Subscription
        $subscription = $this->createSubscription($package, $price);

        // Store the subscription and website IDs in checkout data
        CheckoutHelper::setCheckoutData([
            'subscription_id' => $subscription->id,
        ]);

        // Store payment record
        PaymentHelper::storeLocalPayment([
            'amount' => $amount,
            'currency' => $currency,
            'charge_id' => $paymentData['charge_id'],
            'order_id' => [$subscription->id],
            'customer_id' => $customer->getKey(),
            'customer_type' => get_class($customer),
            'payment_channel' => $paymentMethod,
            'status' => PaymentStatusEnum::PENDING,
        ]);


        // Redirect to success page
        return $this->httpResponse()
            ->setNextUrl(PaymentHelper::getRedirectURL($token))
            ->setMessage(__('Order placed successfully!'));
    }

    public function getCheckoutSuccess(Request $request, ?string $token = null)
    {
        $checkoutData = CheckoutHelper::getCheckoutData($token);

        if (empty($checkoutData)) {
            return redirect()
                ->route('wr.front.public.theme.index')
                ->with('error_msg', __('Invalid checkout session.'));
        }

        $chargeId = $request->input('charge_id');

        if ($chargeId) {
            $payment = Payment::query()
                ->where('charge_id', $chargeId)
                ->first();

            if ($payment && $payment->status === PaymentStatusEnum::PENDING) {
                $payment->status = PaymentStatusEnum::COMPLETED;
                $payment->save();

                do_action(ACTION_AFTER_UPDATE_PAYMENT, $request, $payment);
            }
        }

        // Get the created records
        $subscription = null;
        $website = null;

        if (! empty($checkoutData['subscription_id'])) {
            $subscription = Models\Subscription::find($checkoutData['subscription_id']);
        }

        if (! empty($checkoutData['website_id'])) {
            $website = Models\Website::find($checkoutData['website_id']);
        }

        // Clear checkout session
        CheckoutHelper::clearCheckoutSession();

        return Theme::scope('website-reseller.order.success', [
            'subscription' => $subscription,
            'website' => $website,
            'checkoutData' => $checkoutData,
        ])->render();
    }

    public function getCheckoutCancel(Request $request, ?string $token = null)
    {
        $errorMessage = $request->input('error_message', __('Payment was cancelled.'));

        // Get checkout data before clearing
        $checkoutData = CheckoutHelper::getCheckoutData($token);

        // If subscription/website were created, we might need to cancel them
        if (! empty($checkoutData['subscription_id'])) {
            $subscription = Models\Subscription::find($checkoutData['subscription_id']);
            if ($subscription) {
                // Optionally delete or mark as cancelled
                $subscription->delete();
            }
        }

        if (! empty($checkoutData['website_id'])) {
            $website = Models\Website::find($checkoutData['website_id']);
            if ($website) {
                // Optionally delete or mark as cancelled
                $website->delete();
            }
        }

        // Clear checkout session
        CheckoutHelper::clearCheckoutSession();

        return redirect()
            ->route('wr.front.public.theme.index')
            ->with('error_msg', $errorMessage);
    }

    protected function createSubscription(Models\Package $package, Models\PackagePrice $price): Models\Subscription
    {
        $now = Carbon::now();

        // Calculate next expiry based on payment interval
        $nextExpiresAt = $this->calculateNextExpiry($now, $price->payment_interval);

        return Models\Subscription::create([
            'package_id' => $package->id,
            'package_price_id' => $price->id,
            'name' => $package->name . ' - ' . $price->name,
            'commit_price' => $price->price,
            'payment_interval' => $price->payment_interval,
            'start_at' => $now,
            'next_expires_at' => $nextExpiresAt,
        ]);
    }

    protected function createWebsite(
        Models\Customer $customer,
        Models\Subscription $subscription,
        Models\Theme $theme
    ): Models\Website {
        return Models\Website::create([
            'customer_id' => $customer->id,
            'subscription_id' => $subscription->id,
            'theme_id' => $theme->id,
            'source_code_id' => $theme->source_code_id ?? null,
            'domain' => null, // Will be set later by customer
            'status' => 'pending', // Initial status
        ]);
    }

    protected function calculateNextExpiry(Carbon $startDate, ?string $interval): Carbon
    {
        return match ($interval) {
            'daily' => $startDate->copy()->addDay(),
            'weekly' => $startDate->copy()->addWeek(),
            'monthly' => $startDate->copy()->addMonth(),
            'quarterly' => $startDate->copy()->addMonths(3),
            'yearly', 'annually' => $startDate->copy()->addYear(),
            default => $startDate->copy()->addMonth(),
        };
    }
}
