<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate {

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        if ($this->auth->guard($guard)->guest()) {
            if ($request->has('app_token')) {
                $token = $request->input('app_token');
                $check_token = User::where('app_token', $token)->first();
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
        }
        return $next($request);
    }

}
