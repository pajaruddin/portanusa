<?php

namespace App\Http\Controllers;

use App\Header;
use App\Libraries\AppConfiguration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {

    public function login() {
        if (Auth::check()) {
            return redirect('/');
        }

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('auth.login', $data);
    }    
    
    public function authenticated(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ]);
        
        $email = $request->input('email');
        $password = $request->input('password');
        if (Auth::attempt(['email' => $email, 'password' => $password, 'active' => 1])) {
            $token = bin2hex(random_bytes(32));
            Auth::user()->update(['app_token' => $token]);
            return redirect('/');
        } else {
            return redirect('/login')->with('message', 'Login tidak berhasil.');
        }
    }

    public function logout() {
        Auth::user()->update(['app_token' => NULL]);
        Auth::logout();
        return redirect('/login');
    }
}