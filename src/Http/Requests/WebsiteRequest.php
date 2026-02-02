<?php

namespace QuangPhuc\WebsiteReseller\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class WebsiteRequest extends Request
{
    public function rules(): array
    {
        $websiteId = $this->route('website');

        return [
            'domain' => [
                'required',
                'string',
                'max:255',
                Rule::unique('wr_websites', 'domain')->ignore($websiteId),
            ],
            'subscription_id' => 'nullable|exists:wr_subscriptions,id',
            'theme_id' => 'nullable|exists:wr_themes,id',
            'source_code_id' => 'nullable|exists:wr_source_codes,id',
            'status' => 'required|string|max:60',
        ];
    }
}
