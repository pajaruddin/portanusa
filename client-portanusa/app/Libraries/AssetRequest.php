<?php

namespace App\Libraries;

use App\Libraries\AppConfiguration;
use Illuminate\Support\Facades\Auth;

class AssetRequest {

    function uploadDirectory($path, $mode, $recursive, $force) {
        $asset_domain = AppConfiguration::assetDomain()->value;
        $url_create_directory = AppConfiguration::assetCreateDirectoryDomain()->value;
        $token = Auth::user()->app_token;

        $url = $asset_domain . "/" . $url_create_directory . "?app_token=" . $token;
        $post = array(
            'path' => $path,
            'mode' => $mode,
            'recursive' => $recursive,
            'force' => $force
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response_json = curl_exec($ch);
        curl_close($ch);

        $response_decode = json_decode($response_json, TRUE);
        return $response_decode;
    }

    function deleteDirectory($path) {
        $asset_domain = AppConfiguration::assetDomain()->value;
        $url_delete_directory = AppConfiguration::assetDeleteDirectoryDomain()->value;
        $token = Auth::user()->app_token;

        $url = $asset_domain . "/" . $url_delete_directory . "?app_token=" . $token;
        $post = array(
            'path' => $path
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response_json = curl_exec($ch);
        curl_close($ch);

        $response_decode = json_decode($response_json, TRUE);
        return $response_decode;
    }

    function upload($filepath, $filetype, $filename, $destination_path, $new_filename) {
        $asset_domain = AppConfiguration::assetDomain()->value;
        $url_upload = AppConfiguration::assetUploadDomain()->value;
        $token = Auth::user()->app_token;

        $url = $asset_domain . "/" . $url_upload . "?app_token=" . $token;
        $post = array(
            'filename' => $new_filename,
            'path' => $destination_path,
            'file' => curl_file_create($filepath, $filetype, $filename)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response_json = curl_exec($ch);
        curl_close($ch);

        $response_decode = json_decode($response_json, TRUE);
        return $response_decode;
    }

    function anonymousUpload($filepath, $filetype, $filename, $destination_path, $new_filename) {
        $asset_domain = AppConfiguration::assetDomain()->value;
        $url_upload = AppConfiguration::anonymousUploadDomain()->value;

        $url = $asset_domain . "/" . $url_upload;
        $post = array(
            'filename' => $new_filename,
            'path' => $destination_path,
            'file' => curl_file_create($filepath, $filetype, $filename)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response_json = curl_exec($ch);
        curl_close($ch);

        $response_decode = json_decode($response_json, TRUE);
        return $response_decode;
    }

    function delete($filepath, $filename) {
        $asset_domain = AppConfiguration::assetDomain()->value;
        $url_delete = AppConfiguration::assetDeleteDomain()->value;
        $token = Auth::user()->app_token;

        $url = $asset_domain . "/" . $url_delete . "?app_token=" . $token;
        $post = array(
            'filename' => $filename,
            'path' => $filepath
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response_json = curl_exec($ch);
        curl_close($ch);

        $response_decode = json_decode($response_json, TRUE);
        return $response_decode;
    }

    function moveFile($source_path, $destination_path) {
        $asset_domain = AppConfiguration::assetDomain()->value;
        $url_move_file = AppConfiguration::assetMoveFileDomain()->value;
        $token = Auth::user()->app_token;

        $url = $asset_domain . "/" . $url_move_file . "?app_token=" . $token;
        $post = array(
            'source_path' => $source_path,
            'destination_path' => $destination_path
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response_json = curl_exec($ch);
        curl_close($ch);

        $response_decode = json_decode($response_json, TRUE);
        return $response_decode;
    }

}
