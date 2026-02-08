<?php

namespace QuangPhuc\WebsiteReseller\Listeners;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use QuangPhuc\WebsiteReseller\Events\SubscriptionActivating;
use QuangPhuc\WebsiteReseller\Models\SubscriptionPeriod;
use QuangPhuc\WebsiteReseller\Models\Theme;
use QuangPhuc\WebsiteReseller\Models\Website;

class CreateSubscriptionWebsite
{
    public function handle(SubscriptionActivating $event): void
    {
        DB::transaction(function () use ($event) {
            $subscription = $event->subscription;

            if (! $subscription->customer_id || ! $subscription->theme_id) {
                return;
            }

            $now = Carbon::now();

            // Calculate next expiry based on subscription period
            $nextExpiresAt = $this->calculateNextExpiry($now, $subscription->subscriptionPeriod);

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
        });
    }

    protected function calculateNextExpiry(Carbon $startDate, ?SubscriptionPeriod $period): Carbon
    {
        if (! $period) {
            // Default to 1 month if no period is set
            return $startDate->copy()->addMonth();
        }

        $interval = $period->getInterval();

        return $startDate->copy()->add($interval);
    }
}
