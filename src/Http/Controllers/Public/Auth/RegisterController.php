<?php

namespace QuangPhuc\WebsiteReseller\Http\Controllers\Public\Auth;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use QuangPhuc\WebsiteReseller\Models\Customer;

class RegisterController extends BaseController
{
    public function showRegistrationForm()
    {
        return Theme::scope('website-reseller.auth.register')->render();
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:wr_customers,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customer = Customer::create($data);

        Auth::guard('customer')->login($customer);

        return redirect()->route('customer.websites');
    }
}
