<?php

namespace QuangPhuc\WebsiteReseller\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SubscriptionPeriodRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'interval_value' => ['required', 'string', 'max:50', 'regex:/^P(\d+[YMWD])+$/'],
            'sequence' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'interval_value.regex' => 'The interval value must be a valid ISO 8601 duration format (e.g., P1D, P1W, P1M, P1Y).',
        ];
    }
}
