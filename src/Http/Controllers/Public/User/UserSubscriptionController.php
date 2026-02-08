<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public\User;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Theme\Facades\Theme;
use FriendsOfBotble\VietnamBankQr\VietQR;
use Illuminate\Support\Str;
use QuangPhuc\WebsiteReseller\Enums\SubscriptionStatusEnum;
use QuangPhuc\WebsiteReseller\Models\Subscription;

class UserSubscriptionController extends BaseController
{
    public function showPaymentInfo(Subscription $subscription)
    {
        $customer = auth('wr_customer')->user();

        // Ensure the subscription belongs to the authenticated customer
        if ($subscription->customer_id !== $customer->id) {
            abort(403, __('You are not authorized to view this subscription.'));
        }

        // Only pending subscriptions can show payment info
        if ($subscription->status->getValue() !== SubscriptionStatusEnum::PENDING) {
            return redirect()
                ->route('wr.front.customer.websites')
                ->with('error', __('This subscription is no longer pending payment.'));
        }

        // Load relationships
        $subscription->load(['theme', 'package', 'packagePrice', 'subscriptionPeriod']);

        // Generate QR code if fob-vietnam-bank-qr plugin is active
        $qrCode = null;
        if (is_plugin_active('fob-vietnam-bank-qr')) {
            $orderCode = Str::padLeft($subscription->id, 5, '0');
            $qrCode = view(
                'plugins/fob-vietnam-bank-qr::bank-info',
                [
                    'orderAmount' => $subscription->commit_price,
                    'imageUrl' => VietQR::getImageUrl($subscription->commit_price, $orderCode),
                    'bank' => VietQR::getBankInfo(),
                    'bankTransferDescription' => VietQR::getTransferDescription($orderCode),
                    'currentCurrency' => 'VND',
                ]
            )->render();
        }

        $bankInfo = get_payment_setting('description', PaymentMethodEnum::BANK_TRANSFER);

        return Theme::scope('website-reseller.user.subscription-payment', [
            'subscription' => $subscription,
            'bankInfo' => $bankInfo,
            'qrCode' => $qrCode,
            'customer' => $customer,
        ])->render();
    }
}
