<?php

namespace QuangPhuc\WebsiteReseller\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ThemeRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'preview_url' => 'nullable|string|max:255',
            'packages' => 'nullable|array',
            'packages.*' => 'exists:wr_packages,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:wr_categories,id',
            'source_code_id' => 'nullable|exists:wr_source_codes,id',
        ];
    }
}
