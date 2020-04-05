<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Career_applicant;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CareerAplicantController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;
        
        return view('career-aplicant.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $careers = Career_applicant::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'career_applicants.id', 'career_posts.position', 'career_applicants.full_name', 'career_applicants.email', 'career_applicants.cv_file')
                ->leftJoin('career_posts', 'career_posts.id', '=', 'career_applicants.career_post_id')
                ->orderBy('id', 'desc')
                ->get();

        return Datatables::of($careers)
                ->addColumn('action', function ($career_post) {
                    $uri = request()->segment(1);
                    $menu = $uri;
                    $url_cv = AppConfiguration::assetPortalDomain()->value . "/" . AppConfiguration::cvPath()->value . "/" . $career_post->cv_file;

                    $action_button = "";
                    $action_button .= '<a target="_blank" href="' . $url_cv .'" class="btn btn-circle btn-icon-only btn-primary" title="Download CV"><i class="fa fa-download"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $career_post->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Career"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $career_post = Career_applicant::find($request->id);

            if ($career_post->delete()) {

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Career']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Career'])
                ));
            }
        }
    }

}