<?php

namespace App\Libraries;

use Avatar;
use App\Libraries\AppConfiguration;
use Illuminate\Support\Facades\Auth;

class AuthUser {

    public static function firstName() {
        return Auth::user()->first_name;
    }

    public static function lastName() {
        return Auth::user()->last_name;
    }

    public static function fullName() {
        return Auth::user()->first_name . " " . Auth::user()->last_name;
    }

    public static function avatar() {
        $avatar = "";
        if (Auth::user()->photo_image == NULL) {
            $fullName = Auth::user()->first_name . " " . Auth::user()->last_name;
            $avatar = Avatar::create($fullName)->toBase64();
        } else {
            $avatar = AppConfiguration::assetPortalDomain()->value . "/" . AppConfiguration::avatarImagePath()->value . '/' . Auth::user()->photo_image;
        }
        return $avatar;
    }

}
