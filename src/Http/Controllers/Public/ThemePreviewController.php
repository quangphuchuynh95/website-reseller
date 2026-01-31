<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Theme\Facades\Theme;
use QuangPhuc\WebsiteReseller\Models\Theme as ThemeModel;

class ThemePreviewController extends BaseController
{
    public function __invoke(ThemeModel $theme)
    {
        return Theme::scope('website-reseller.themes.preview', compact('theme'))->render();
    }
}
