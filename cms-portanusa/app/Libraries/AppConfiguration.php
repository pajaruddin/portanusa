<?php

namespace App\Libraries;

use App\Configuration;
use App\Header;

class AppConfiguration {

    public static function cmsDomain() {
        $config = Configuration::find(2);
        return $config;
    }

    public static function assetPortalDomain() {
        $config = Configuration::find(3);
        return $config;
    }
    
    public static function assetUploadDomain() {
        $config = Configuration::find(4);
        return $config;
    }
    
    public static function assetDeleteDomain() {
        $config = Configuration::find(5);
        return $config;
    }
    
    public static function avatarImagePath() {
        $config = Configuration::find(8);
        return $config;
    }

    public static function productImagePath() {
        $config = Configuration::find(13);
        return $config;
    }

    public static function brandBannerImagePath() {
        $config = Configuration::find(15);
        return $config;
    }

    public static function categoryBannerImagePath() {
        $config = Configuration::find(16);
        return $config;
    }

    public static function categoryThumbImagePath() {
        $config = Configuration::find(17);
        return $config;
    }

    public static function voucherBannerImagePath() {
        $config = Configuration::find(14);
        return $config;
    }

    public static function saleEventBannerImagePath() {
        $config = Configuration::find(18);
        return $config;
    }

    public static function bankLogoPath() {
        $config = Configuration::find(19);
        return $config;
    }

    public static function transferPath() {
        $config = Configuration::find(20);
        return $config;
    }

    public static function articleBannerPath() {
        $config = Configuration::find(21);
        return $config;
    }

    public static function catalogsThumbPath() {
        $config = Configuration::find(22);
        return $config;
    }

    public static function cvPath() {
        $config = Configuration::find(23);
        return $config;
    }

    public static function bannerPath() {
        $config = Configuration::find(24);
        return $config;
    }

    public static function logoPath() {
        $config = Configuration::find(25);
        return $config;
    }

    public static function catalogPath() {
        $config = Configuration::find(27);
        return $config;
    }

    public static function servicePath() {
        $config = Configuration::find(26);
        return $config;
    }

    public static function taxPath() {
        $config = Configuration::find(28);
        return $config;
    }

    public static function headerLogo() {
        $config = Header::find(1);
        return $config;
    }

}
