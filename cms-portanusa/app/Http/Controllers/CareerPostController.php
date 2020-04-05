<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Career_post;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Libraries\AssetRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CareerPostController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('career-post.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $career_posts = Career_post::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'position', 'date_start', 'date_end', 'publish')
            ->orderBy('id', 'desc')    
            ->get();

        return Datatables::of($career_posts)
                ->editColumn('date_start', function ($career_post) {
                    return date('d M Y H:i:s', strtotime($career_post->date_start));
                })
                ->editColumn('date_end', function ($career_post) {
                    return date('d M Y H:i:s', strtotime($career_post->date_end));
                })
                ->editColumn('active', function ($career_post) {
                    if ($career_post->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $career_post->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->addColumn('action', function ($career_post) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-career", $career_post->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Career"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $career_post->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Career"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $career_post = Career_post::find($request->id);
            $active = $request->input('active');
            
            $career_post->publish = $active;
            
            if ($career_post->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Career'])
                ));
            }
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $career_post = Career_post::find($request->id);

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

    function add() {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        return view('career-post.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'position' => 'Posisi',
            'job_description' => 'Job Deskripsi',
            'job_requirement' => 'Job Requirement',
            'date_start' => 'Tanggal Mulai',
            'date_end' => 'Tanggal Akhir'
        );

        $validator = Validator::make($request->all(), array(
            'position' => 'required|unique:career_posts',
            'job_description' => 'required',
            'job_requirement' => 'required',
            'date_start' => 'required',
            'date_end' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {

            $position = $request->input('position');
            $job_description = $request->input('job_description');
            $job_requirement = $request->input('job_requirement');
            $publish = $request->input('publish');
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');
            $slug = strtolower(str_slug($position, "_"));   

            $career = new Career_post();
            $career->position = $position;
            $career->job_description = $job_description;
            $career->job_requirement = $job_requirement;
            $career->date_start = date('Y-m-d H:i:s', strtotime($date_start));
            $career->date_end = date('Y-m-d H:i:s', strtotime($date_end));
            $career->publish = ($publish != "") ? "T" : "F";
            $career->url = $slug;

            if ($career->save()) {
                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Career']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Career']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri)->withErrors($validator)->withInput();
        }
    }

    function edit($id) {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $career = Career_post::find($id);
        if (!empty($career)) {

            $data['menu'] = $menu;
            $data['submenu'] = $submenu;
            $data['career'] = $career;
    
            return view('career-post.update')->with($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'position' => 'Posisi',
            'job_description' => 'Job Deskripsi',
            'job_requirement' => 'Job Requirement',
            'date_start' => 'Tanggal Mulai',
            'date_end' => 'Tanggal Akhir'
        );

        $career = Career_post::find($id);

        if ($career->position == $request->position) {
            $is_unique = "";
        } else {
            $is_unique = "|unique:career_posts";
        }

        $validator = Validator::make($request->all(), array(
            'position' => 'required' . $is_unique,
            'job_description' => 'required',
            'job_requirement' => 'required',
            'date_start' => 'required',
            'date_end' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {

            $position = $request->input('position');
            $job_description = $request->input('job_description');
            $job_requirement = $request->input('job_requirement');
            $publish = $request->input('publish');
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');
            $slug = strtolower(str_slug($position, "_"));   

            $career->position = $position;
            $career->job_description = $job_description;
            $career->job_requirement = $job_requirement;
            $career->date_start = date('Y-m-d H:i:s', strtotime($date_start));
            $career->date_end = date('Y-m-d H:i:s', strtotime($date_end));
            $career->publish = ($publish != "") ? "T" : "F";
            $career->url = $slug;

            if ($career->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Career']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Career']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}