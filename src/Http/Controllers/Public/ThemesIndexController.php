<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Theme\Facades\Theme;

class ThemesIndexController extends BaseController
{
    public function __invoke()
    {

        $themes = \QuangPhuc\WebsiteReseller\Models\Theme::query()->latest()->get();
        return Theme::scope('website-reseller.themes.index', compact('themes'))->render();
    }
}
