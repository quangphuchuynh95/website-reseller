<?php

namespace QuangPhuc\WebsiteReseller\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use QuangPhuc\WebsiteReseller\Models\Subscription;

class SubscriptionActivating
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Subscription $subscription)
    {
    }
}
