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
            'sequence' => 'nullable|integer|min:0',
            'payment_interval' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
        ];
    }
}
