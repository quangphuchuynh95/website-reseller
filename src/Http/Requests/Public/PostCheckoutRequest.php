<?php

namespace QuangPhuc\WebsiteReseller\Http\Requests\Public;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PostCheckoutRequest extends Request
{
    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string'],
            'domain' => [
                'required',
                'string',
                'max:255',
                Rule::unique('wr_subscriptions', 'domain'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'domain.unique' => __('This domain is already in use. Please choose a different domain.'),
        ];
    }
}
