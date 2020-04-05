<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Libraries\Shipping;

use App\Mail\InquiryMail;

use App\Inquiry;

use Validator;

class InquiriesController extends Controller
{
    function index(){

        $data_provinces = array();
        $provinces = Shipping::getProvinces();
        if ($provinces['rajaongkir']['status']['code'] == 200) {
            $data_provinces = $provinces['rajaongkir']['results'];
        }
        $data['data_provinces'] = $data_provinces;

        $data['title'] = "PortaNusa - Inquiry";
        return view('inquiry.page')->with($data);

    }

    function createInquiry(Request $request){

        $attributeNames = array(
            'full_name' => 'Full Name',
            'person_as' => 'Person As',
            'handphone' => 'Handphone',
            'email' => 'Email',
            'confirm_email' => 'Confirm Email',
            'address' => 'Address',
            'province_id' => 'Province',
            'state_id' => 'City',
            'postal_zip' => 'Postal Code',
            'message' => 'Message'
        );
        $validator = Validator::make($request->all(), array(
                    'full_name' => 'required',
                    'person_as' => 'required',
                    'handphone' => 'required',
                    'email' => 'required|email',
                    'confirm_email' => 'required|same:email',
                    'address' => 'required',
                    'province_id' => 'required',
                    'state_id' => 'required',
                    'postal_zip' => 'required',
                    'message' => 'required',
        ));
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {
        
            $status = "";
            $message = "";

            $full_name = $request->input('full_name');
            $company_name = ($request->input('company_name') == "" ? NULL : $request->input('company_name'));
            $telephone = ($request->input('telephone') == "" ? NULL : $request->input('telephone'));
            $handphone = ($request->input('handphone') == "" ? NULL : $request->input('handphone'));
            $person_as = ($request->input('person_as') == "" ? NULL : $request->input('person_as'));
            $email = $request->input('email');
            $address = $request->input('address');
            $province_id = $request->input('province_id');
            $state_id = $request->input('state_id');
            $postal_zip = $request->input('postal_zip');
            $message = $request->input('message');
    
            $model = new Inquiry();
            $model->full_name = $full_name;
            $model->company_name = $company_name;
            $model->telephone = $telephone;
            $model->handphone = $handphone;
            $model->person_as = $person_as;
            $model->email = $email;
            $model->address = $address;
            $model->province_id = $province_id;
            $model->state_id = $state_id;
            $model->postal_zip = $postal_zip;
            $model->message = $message;
    
            if ($model->save()) {
                $status = "success";
                $message = "Your Data Successfully Sent";

                //Send Mail

                Mail::to($email)->send(new InquiryMail($full_name, $email));
            }else{
                $status = "failed";
                $message = "Your Data Unsuccessfully Sent";
            }
            
            return redirect('/inquiry')->with($status, $message);
        }else{
            return redirect('/inquiry')->withErrors($validator)->withInput();
        }

    }
}
