<?php

namespace QuangPhuc\WebsiteReseller\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SubscriptionRequest extends Request
{
    public function rules(): array
    {
        $subscriptionId = $this->route('subscription');

        return [
            'name' => ['required', 'string', 'max:255'],
            'domain' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('wr_subscriptions', 'domain')->ignore($subscriptionId),
            ],
            'package_id' => ['nullable', 'exists:wr_packages,id'],
            'package_price_id' => ['nullable', 'exists:wr_package_prices,id'],
            'subscription_period_id' => ['nullable', 'exists:wr_subscription_periods,id'],
            'commit_price' => ['nullable', 'numeric', 'min:0'],
            'start_at' => ['nullable', 'date'],
            'next_expires_at' => ['nullable', 'date'],
        ];
    }
}
