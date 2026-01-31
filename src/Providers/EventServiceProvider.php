<?php

namespace QuangPhuc\WebsiteReseller\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use QuangPhuc\WebsiteReseller\Events\SubscriptionActivating;
use QuangPhuc\WebsiteReseller\Listeners\CreateSubscriptionWebsite;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SubscriptionActivating::class => [
            CreateSubscriptionWebsite::class,
        ],
    ];
}
