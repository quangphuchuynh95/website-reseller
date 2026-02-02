<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public\User;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Theme\Facades\Theme;
use QuangPhuc\WebsiteReseller\Models\Website;

class UserWebsiteController extends BaseController
{
    public function __invoke()
    {
        $customer = auth('wr_customer')->user();

        $websites = Website::query()
            ->where('customer_id', $customer->id)
            ->with([
                'subscription',
                'subscription.package',
                'subscription.packagePrice',
                'subscription.subscriptionPeriod',
                'theme',
            ])
            ->latest()
            ->get();

        return Theme::scope('website-reseller.user.websites', compact('websites', 'customer'))->render();
    }
}
