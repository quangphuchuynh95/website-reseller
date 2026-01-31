<?php

namespace QuangPhuc\WebsiteReseller\Services;

use QuangPhuc\WebsiteReseller\Enums\SubscriptionStatusEnum;
use QuangPhuc\WebsiteReseller\Events\SubscriptionActivating;
use QuangPhuc\WebsiteReseller\Models\Subscription;

class SubscriptionService
{
    public function __construct()
    {
    }

    public function activateSubscription(string $chargeId): ?Subscription
    {
        $subscription = Subscription::query()
            ->where('charge_id', $chargeId)
            ->first();

        if (! $subscription) {
            return null;
        }

        $subscription->update([
            'status' => SubscriptionStatusEnum::ACTIVE,
        ]);

        event(new SubscriptionActivating($subscription));

        return $subscription;
    }
}
