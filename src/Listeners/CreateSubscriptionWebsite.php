<?php

namespace QuangPhuc\WebsiteReseller\Listeners;

use Illuminate\Support\Carbon;
use QuangPhuc\WebsiteReseller\Events\SubscriptionActivating;
use QuangPhuc\WebsiteReseller\Models\Theme;
use QuangPhuc\WebsiteReseller\Models\Website;

class CreateSubscriptionWebsite
{
    public function handle(SubscriptionActivating $event): void
    {
        $subscription = $event->subscription;

        if (! $subscription->customer_id || ! $subscription->theme_id) {
            return;
        }


        $price = $subscription->packagePrice;

        $now = Carbon::now();

        // Calculate next expiry based on payment interval
        $nextExpiresAt = $this->calculateNextExpiry($now, $price->payment_interval);

        $subscription->update([
            'start_at' => $now,
            'next_expires_at' => $nextExpiresAt,
        ]);


        $theme = Theme::find($subscription->theme_id);

        if (! $theme) {
            return;
        }

        Website::create([
            'customer_id' => $subscription->customer_id,
            'subscription_id' => $subscription->id,
            'theme_id' => $theme->id,
            'source_code_id' => $theme->source_code_id,
            'domain' => $subscription->domain,
            'status' => 'pending',
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
