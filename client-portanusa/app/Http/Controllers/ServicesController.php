<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Returning Of Units'
        ];
        return view('services.returnUnit')->with($data);
    }

    public function sas()
    {
        $data = [
            'title' => 'Services and After Sales'
        ];
        return view('services.serviceAfterSales')->with($data);
    }

    public function term()
    {
        $data = [
            'title' => 'Term And Conditions'
        ];
        return view('services.termCondition')->with($data);
    }

    public function policy()
    {
        $data = [
            'title' => 'Privacy Policy'
        ];
        return view('services.privacyPolicy')->with($data);
    }
}
