<?php

namespace App\Libraries;

use App\Configuration;

class AppConfiguration {

    public static function primaryDomain() {
        $config = Configuration::find(1);
        return $config;
    }

    public static function cmsDomain() {
        $config = Configuration::find(2);
        return $config;
    }

    public static function assetDomain() {
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

    public static function customerNpwpImagePath() {
        $config = Configuration::find(6);
        return $config;
    }

    public static function customerKtpImagePath() {
        $config = Configuration::find(7);
        return $config;
    }

    public static function userAvatarImagePath() {
        $config = Configuration::find(8);
        return $config;
    }

    public static function assetCreateDirectoryDomain() {
        $config = Configuration::find(9);
        return $config;
    }

    public static function assetDeleteDirectoryDomain() {
        $config = Configuration::find(10);
        return $config;
    }

    public static function assetMoveFileDomain() {
        $config = Configuration::find(11);
        return $config;
    }

    public static function anonymousUploadDomain() {
        $config = Configuration::find(12);
        return $config;
    }

    public static function productImagePath() {
        $config = Configuration::find(13);
        return $config;
    }

    public static function VoucherImagePath() {
        $config = Configuration::find(14);
        return $config;
    }

    public static function categoryBannerPath() {
        $config = Configuration::find(16);
        return $config;
    }

    public static function eventBannerPath() {
        $config = Configuration::find(18);
        return $config;
    }

    public static function transferImagePath() {
        $config = Configuration::find(20);
        return $config;
    }

    public static function BankImagePath() {
        $config = Configuration::find(19);
        return $config;
    }

    public static function AricleBannerImagePath() {
        $config = Configuration::find(21);
        return $config;
    }

    public static function DownloadThumbImagePath() {
        $config = Configuration::find(22);
        return $config;
    }

    public static function CareerCVImagePath() {
        $config = Configuration::find(23);
        return $config;
    }

    public static function bannerImagePath() {
        $config = Configuration::find(24);
        return $config;
    }

    public static function logoImagePath() {
        $config = Configuration::find(25);
        return $config;
    }

    public static function serviceImagePath() {
        $config = Configuration::find(26);
        return $config;
    }

    public static function catalogueFilePath() {
        $config = Configuration::find(27);
        return $config;
    }
    
}
