<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Theme\Facades\Theme;
use QuangPhuc\WebsiteReseller\Models;

class PackageSelectionController extends BaseController
{
    public function __invoke(Models\Theme $theme)
    {
        if ($theme->packages()->count() === 1) {
            return redirect()->route('website.order.package_price', [
                'theme' => $theme,
                'package' => $theme->packages()->first(),
            ]);
        }
        return Theme::scope('website-reseller.order.package', compact('theme'))->render();
    }
}
