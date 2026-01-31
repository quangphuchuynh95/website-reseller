<?php

namespace QuangPhuc\WebsiteReseller\Http\Requests;

use Botble\Support\Http\Requests\Request;

class PackagePriceRequest extends Request
{
    public function rules(): array
    {
        return [
            'package_id' => 'required|exists:wr_packages,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'subscription_period_id' => 'nullable|exists:wr_subscription_periods,id',
            'sequence' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
        ];
    }
}
