<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public\Auth;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{

    public function showLoginForm()
    {
        if (request()->has('redirect') && request()->get('redirect')) {
            session(['url.intended' => request()->get('redirect')]);
        } elseif (! session()->has('url.intended') && ! in_array(url()->previous(), [route('wr.front.customer.auth.login'), route('wr.front.customer.auth.register')])) {
            session(['url.intended' => url()->previous()]);
        }
        return Theme::scope('website-reseller.auth.login')->render();
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('wr_customer')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended(route('wr.front.customer.websites'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
