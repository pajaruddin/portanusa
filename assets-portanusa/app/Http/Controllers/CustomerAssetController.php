<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

class CustomerAssetController extends Controller {

    function upload(Request $request) {
        $attributeNames = array(
            'path' => 'Folder Tujuan',
            'file' => 'Berkas',
            'filename' => 'Nama Berkas',
        );

        $validator = Validator::make($request->all(), array(
                    'path' => 'required',
                    'file' => 'required|mimes:jpeg,png',
                    'filename' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {
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
        } else {
            $message = "";
            $errors = $validator->errors();
            foreach ($errors->all() as $error_message) {
                $message .= $error_message . "<br/>";
            }

            $res['code'] = 417;
            $res['description'] = $message;
            $res['result'] = NULL;
        }
        return response($res, $res['code']);
    }

    function delete(Request $request) {
        $attributeNames = array(
            'path' => 'Folder Tujuan',
            'filename' => 'Nama Berkas',
        );

        $validator = Validator::make($request->all(), array(
                    'path' => 'required',
                    'file' => 'required|mimes:jpeg,png',
                    'filename' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {
            $path = $request->input('path');
            $filename = $request->input('filename');

            if (file_exists(base_path() . "/public/" . $path . '/' . $filename)) {
                unlink(base_path() . "/public/" . $path . '/' . $filename);
            }

            $res['code'] = 200;
            $res['description'] = "OK";
        } else {
            $message = "";
            $errors = $validator->errors();
            foreach ($errors->all() as $error_message) {
                $message .= $error_message . "<br/>";
            }

            $res['code'] = 417;
            $res['description'] = $message;
        }
        return response($res, $res['code']);
    }

}
