<?php

namespace QuangPhuc\WebsiteReseller\Http\Middleware;

use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotCustomer
{
    public function handle(Request $request, Closure $next)
    {
        $guard = 'wr_customer';
        if (! Auth::guard($guard)->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }

            return redirect()->guest(route('wr.front.customer.auth.login'));
        }

        return $next($request);
    }
}
