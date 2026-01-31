<?php

namespace QuangPhuc\WebsiteReseller\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SubscriptionRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'package_id' => 'nullable|exists:wr_packages,id',
            'package_price_id' => 'nullable|exists:wr_package_prices,id',
            'commit_price' => 'nullable|numeric|min:0',
            'payment_interval' => 'nullable|string|max:50',
            'start_at' => 'nullable|date',
            'next_expires_at' => 'nullable|date',
        ];
    }
}
