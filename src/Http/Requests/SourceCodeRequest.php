<?php

namespace QuangPhuc\WebsiteReseller\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SourceCodeRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'files' => 'nullable|file|mimes:zip|max:512000', // Max 500MB
            'caddy_template' => 'nullable|string',
            'setup_command' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'files' => 'zip file',
        ];
    }

    public function messages(): array
    {
        return [
            'files.mimes' => 'The :attribute must be a zip file.',
            'files.max' => 'The :attribute must not be greater than 500MB.',
        ];
    }
}
