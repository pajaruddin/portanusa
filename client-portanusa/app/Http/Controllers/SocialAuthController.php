<?php

namespace App\Http\Controllers;

use Socialite;
use Redirect;
use App\Mail\SocmedRegis;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SocialAuthController extends Controller {

    public function facebookRedirect() {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback(Request $request) {
        $providerUser = Socialite::driver('facebook')->user();
        $getUser = User::where('login_socmed', 'fb')->where('socmed_user_id', $providerUser->getId())->first();
        if ($getUser) {
            $login = Auth::loginUsingId($getUser->id);
            if ($login) {
                $token = bin2hex(random_bytes(32));
                Auth::user()->update(['app_token' => $token]);
                return Redirect::back();
            } else {
                return Redirect::back()->with("failed_login", AuthUser::get_message());
            }
        } else {
            $name = $providerUser->getName();
            $email = $providerUser->getEmail();
            $socmed_user_id = $providerUser->getId();

            $getUser = User::where('email', $email)->first();
            if ($getUser) {
                return Redirect::back()->with("failed_login", 'Login unsuccessful');
            } else {
                $explode_name = explode(" ", $name);

                $first_name = $explode_name[0];
                $last_name = "";

                $count_explode_name = count($explode_name);
                if ($count_explode_name > 1) {
                    for ($i = 1; $i < $count_explode_name; $i++) {
                        $last_name .= " " . $explode_name[$i];
                    }
                    $last_name = trim($last_name);
                }

                $customer_role_id = 1;
                $active = 1;
                $login_socmed = "fb";

                $user = new User();
                $user->customer_role_id = $customer_role_id;
                $user->socmed_user_id = $socmed_user_id;
                $user->ip_address = \Request::ip();
                $user->email = $email;
                $user->first_name = $first_name;
                $user->last_name = ($last_name != "") ? $last_name : NULL;
                $user->active = $active;
                $user->login_socmed = $login_socmed;
                if ($user->save()) {
                    //Send Mail
                    $login = Auth::loginUsingId($user->id);
                    if ($login) {
                        Mail::to($email)->send(new SocmedRegis($user));
                        $token = bin2hex(random_bytes(32));
                        Auth::user()->update(['app_token' => $token]);
                        return Redirect::back();
                    } else {
                        return Redirect::back()->with("failed_login", AuthUser::get_message());
                    }
                } else {
                    return Redirect::back();
                }
            }
        }
    }

    public function googleRedirect() {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request) {
        $providerUser = Socialite::driver('google')->user();
        $getUser = User::where('login_socmed', 'google')->where('socmed_user_id', $providerUser->getId())->first();
        if ($getUser) {
            $login = Auth::loginUsingId($getUser->id);
            if ($login) {
                $token = bin2hex(random_bytes(32));
                Auth::user()->update(['app_token' => $token]);
                return Redirect::back();
            } else {
                return Redirect::back()->with("failed_login", AuthUser::get_message());
            }
        } else {
            $name = $providerUser->getName();
            $email = $providerUser->getEmail();
            $socmed_user_id = $providerUser->getId();

            $getUser = User::where('email', $email)->first();
            if ($getUser) {
                return Redirect::back()->with("failed_login", 'Login unsuccessful');
            } else {
                $explode_name = explode(" ", $name);

                $first_name = $explode_name[0];
                $last_name = "";

                $count_explode_name = count($explode_name);
                if ($count_explode_name > 1) {
                    for ($i = 1; $i < $count_explode_name; $i++) {
                        $last_name .= " " . $explode_name[$i];
                    }
                    $last_name = trim($last_name);
                }

                $customer_role_id = 1;
                $active = 1;
                $login_socmed = "google";

                $user = new User();
                $user->customer_role_id = $customer_role_id;
                $user->socmed_user_id = $socmed_user_id;
                $user->ip_address = \Request::ip();
                $user->email = $email;
                $user->first_name = $first_name;
                $user->last_name = ($last_name != "") ? $last_name : NULL;
                $user->active = $active;
                $user->login_socmed = $login_socmed;
                if ($user->save()) {
                    //Send Mail
                    $login = Auth::loginUsingId($user->id);
                    if ($login) {
                        Mail::to($email)->send(new SocmedRegis($user));
                        $token = bin2hex(random_bytes(32));
                        Auth::user()->update(['app_token' => $token]);
                        return Redirect::back();
                    } else {
                        return Redirect::back()->with("failed_login", AuthUser::get_message());
                    }
                } else {
                    return Redirect::back();
                }
            }
        }
    }

}
