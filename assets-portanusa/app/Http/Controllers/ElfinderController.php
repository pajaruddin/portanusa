<?php

namespace App\Http\Controllers;

class ElfinderController extends Controller {

    function connector() {
        require_once base_path() . "/public/plugins/elfinder/php/elFinderConnector.class.php";
        require_once base_path() . "/public/plugins/elfinder/php/elFinder.class.php";
        require_once base_path() . "/public/plugins/elfinder/php/elFinderVolumeDriver.class.php";
        require_once base_path() . "/public/plugins/elfinder/php/elFinderVolumeLocalFileSystem.class.php";

        $conn = new \elFinderConnector(new \elFinder(array(
            'roots' => array(
                array(
                    'debug' => TRUE,
                    'driver' => 'LocalFileSystem',
                    'path' => base_path() . "/public/images/media",
                    'URL' => url("/images/media"),
                    'uploadDeny' => array('all'),
                    'uploadAllow' => array('image'),
                    'uploadOrder' => array('deny', 'allow')
                )
            )
        )));
        $conn->run();
    }

    function media() {
        return view('elfinder.elfinder');
    }

}
