<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Newsletter;
use Validator;
use Redirect;
use App\Mail\NewsletterMail;

class NewslettersController extends Controller
{
    public function create(Request $request) {
        $attributeNames = array(
            'email' => 'Email'
        );

        $validator = Validator::make($request->all(), array(
            'email' => 'required|unique:newsletters|email'
        ));

        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {

            $email = $request->input('email');

            $newsletter = new Newsletter();
            $newsletter->email = $email;
            if($newsletter->save()){
                Mail::to($email)->send(new NewsletterMail($email));

                $status = "success_newsletter";
                $message = "Thank you for subscribing with our newsletter";
            }else{
                $status = "failed_newsletter";
                $message = "Sorry, your registration failed";
            }

        }else{
            $status = "failed_newsletter";
            $message = "";
            $errors = $validator->errors();
            foreach ($errors->all() as $error_message) {
                $message .= $error_message . "<br/>";
            }

        }

        return Redirect::back()->with($status, $message);
        
    }
}
