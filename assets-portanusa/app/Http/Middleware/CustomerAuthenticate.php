<?php

namespace App\Http\Middleware;

use Closure;
use App\Customer;

class CustomerAuthenticate {

    /**
     * Create a new middleware instance.
     *
     */
    public function __construct() {
        
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($request->has('app_token')) {
            $token = $request->input('app_token');
            $check_token = Customer::where('app_token', $token)->first();
            if ($check_token == null) {
                $res['code'] = 403;
                $res['description'] = 'Access Forbidden!!!';

                return response($res, 403);
            }
        } else {
            $res['code'] = 403;
            $res['description'] = 'Invalid API Key!!!';

            return response($res, 403);
        }
        return $next($request);
    }

}
