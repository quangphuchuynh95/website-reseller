<?php

namespace QuangPhuc\WebsiteReseller\Http\Requests\Public;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PostCheckoutRequest extends Request
{
    public function rules(): array
    {
        $baseDomains = config('plugins.website-reseller.website.base_domains', []);

        return [
            'payment_method' => ['required', 'string'],
            'subdomain' => [
                'required',
                'string',
                'max:63',
                'regex:/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/i',
            ],
            'base_domain' => [
                'required',
                'string',
                Rule::in($baseDomains),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'subdomain.regex' => __('The subdomain may only contain letters, numbers, and hyphens, and cannot start or end with a hyphen.'),
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->hasAny(['subdomain', 'base_domain'])) {
                return;
            }

            $subdomain = $this->input('subdomain');
            $baseDomain = $this->input('base_domain');
            $fullDomain = strtolower($subdomain) . '.' . $baseDomain;

            $exists = \QuangPhuc\WebsiteReseller\Models\Subscription::query()
                ->where('domain', $fullDomain)
                ->exists();

            if ($exists) {
                $validator->errors()->add('subdomain', __('This subdomain is already in use. Please choose a different one.'));
            }
        });
    }
}
