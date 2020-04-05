<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Validator;

use App\Career_post;
use App\Career_applicant;

use App\Libraries\AssetRequest;
use App\Libraries\AppConfiguration;
use App\Mail\CareerMail;

class AboutUsController extends Controller
{
    function index(){
        $today = date('Y-m-d H:i:s');
        $data['career_posts'] = Career_post::where('publish', 'T')->where('date_start', '<=', $today)->where('date_end', '>=', $today)->orderBy('id', 'desc')->get();
        $data['title'] = "Portanusa - About Us";
        return view('about_us.page')->with($data);
    }

    public function createApplyed(Request $request) {
        $attributeNames = array(
            'career_post_id' => 'Position',
            'full_name' => 'Full Name',
            'phone' => 'Phone Number',
            'email' => 'Email',
            'address' => 'Address',
            'cv_file' => 'CV'
        );
        $validator = Validator::make($request->all(), array(
                    'career_post_id' => 'required',
                    'full_name' => 'required',
                    'phone' => 'required',
                    'email' => 'required|email',
                    'address' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {
            $applicant = Career_applicant::where('email', $request->input('email'))->where('career_post_id', $request->input('career_post_id'))->first();
            
            $status = "";
            $message = "";
            
            if(!empty($applicant)){
                $status = "failed";
                $message = trans('This email is already registered');
            }else{
                $career_post_id = $request->input('career_post_id');
                $full_name = $request->input('full_name');
                $phone = $request->input('phone');
                $email = $request->input('email');
                $address = $request->input('address');

                $slug = strtolower(str_slug($full_name, "_"));
                $cv_file = NULL;
                $asset_request = new AssetRequest;

                if ($request->hasFile('cv_file')) {
                    $document_file = $request->file('cv_file');
                    $document_filename = $slug . "_" . uniqid();
                    $document_full_filename = $document_filename . "." . $document_file->getClientOriginalExtension();
                    $document_filetype = $document_file->getClientMimeType();
                    $document_filepath = $_FILES['cv_file']['tmp_name'];
                    $destination_path = AppConfiguration::CareerCVImagePath()->value;

                    $upload_document = $asset_request->anonymousUpload($document_filepath, $document_filetype, $document_full_filename, $destination_path, $document_filename);
                    if ($upload_document['code'] != 200) {
                        return redirect('about_us')->with('failed', $upload_document['description']);
                    } else {
                        $cv_file = $upload_document['result']['file_name'];
                    }
                }

                $model = new Career_applicant();
                $model->career_post_id = $career_post_id;
                $model->full_name = $full_name;
                $model->phone = $phone;
                $model->email = $email;
                $model->address = $address;
                $model->cv_file = $cv_file;
        
                if ($model->save()) {
                    Mail::to($email)->send(new CareerMail($model));

                    $status = "success";
                    $message = trans('Your data successfully sent');
                }else{
                    $status = "failed";
                    $message = trans('Your data unsuccessfully sent');
                }
            }
            return redirect('about_us')->with($status, $message);
        }else{
            return redirect('about_us')->withErrors($validator)->withInput();
        }
    }
}
