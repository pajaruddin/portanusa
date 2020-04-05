<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller {

    function makeDirectory(Request $request) {
        $path = $request->input('path');
        $mode = $request->input('mode');
        $recursive = $request->input('recursive');
        $force = $request->input('force');

        if ($force) {
            $create_directory = @mkdir($path, $mode, $recursive);
        } else {
            $create_directory = mkdir($path, $mode, $recursive);
        }

        if ($create_directory) {
            $res['code'] = 200;
            $res['description'] = 'OK';
        } else {
            $res['code'] = 417;
            $res['description'] = 'Direktori tidak berhasil dibuat';
        }
        return response($res, $res['code']);
    }

    function deleteDirectory(Request $request) {
        $path = $request->input('path');
        array_map('unlink', glob("$path/*.*"));

        $remove_directory = rmdir($path);
        if ($remove_directory) {
            $res['code'] = 200;
            $res['description'] = 'OK';
        } else {
            $res['code'] = 417;
            $res['description'] = 'Direktori tidak berhasil dihapus';
        }
        return response($res, $res['code']);
    }

    function upload(Request $request) {
        $path = $request->input('path');
        $filename = $request->input('filename');

        $file = $request->file('file');
        $filename = $filename . "." . $file->getClientOriginalExtension();
        if ($file->move($path, $filename)) {
            $res['code'] = 200;
            $res['description'] = 'OK';
            $res['result'] = array(
                'file_name' => $filename
            );
        } else {
            $res['code'] = 417;
            $res['description'] = $file->getErrorMessage();
            $res['result'] = NULL;
        }
        return response($res, $res['code']);
    }

    function delete(Request $request) {
        $path = $request->input('path');
        $filename = $request->input('filename');

        if (file_exists(base_path() . "/public/" . $path . '/' . $filename)) {
            unlink(base_path() . "/public/" . $path . '/' . $filename);
        }

        $res['code'] = 200;
        $res['description'] = "OK";
        return response($res);
    }

    function moveFile(Request $request) {
        $source_file = $request->input('source_path');
        $destination_file = $request->input('destination_path');

        if (file_exists(base_path() . "/public/" . $source_file)) {
            rename(base_path() . "/public/" . $source_file, base_path() . "/public/" . $destination_file);
        }

        $res['code'] = 200;
        $res['description'] = "OK";
        return response($res);
    }

}
