<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Theme\Facades\Theme;

class UserWebsiteController extends BaseController
{
    public function __invoke()
    {
        return Theme::scope('website-reseller.user.websites')->render();
    }
}
