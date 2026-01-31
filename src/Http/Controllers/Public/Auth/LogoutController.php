<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public\Auth;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends BaseController
{
    public function __invoke(Request $request)
    {
        Auth::guard('wr_customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('wr.front.customer.auth.login');
    }
}
