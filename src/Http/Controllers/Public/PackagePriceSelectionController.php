<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Theme\Facades\Theme;
use QuangPhuc\WebsiteReseller\Models;

class PackagePriceSelectionController extends BaseController
{
    public function __invoke(Models\Theme $theme, Models\Package $package)
    {
        return Theme::scope('website-reseller.order.price', compact('theme', 'package'))->render();
    }
}
