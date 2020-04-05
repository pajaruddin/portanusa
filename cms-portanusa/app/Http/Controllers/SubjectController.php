<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Subject;
use App\Header;

use App\Libraries\AppConfiguration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SubjectController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('subject.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $subjects = Subject::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'name', 'description', 'publish')
                ->get();

        return Datatables::of($subjects)
                ->editColumn('active', function ($subject) {
                    if ($subject->publish == 'T') {
                        $active_checked = 'checked';
                    } else {
                        $active_checked = '';
                    }
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $subject->id . '" data-size="mini" ' . $active_checked . '>';
                    
                    return $active;
                })
                ->addColumn('action', function ($subject) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="' . url($menu . "/edit-subject", $subject->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit Subject"><i class="fa fa-pencil"></i></a>';
                    $action_button .= '<a href="#" id="delete" data-id="' . $subject->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Subject"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
    }

    function updateActive(Request $request) {
        if ($request->ajax()) {
            $subject = Subject::find($request->id);
            $active = $request->input('active');
            
            $subject->publish = $active;
            
            if ($subject->save()) {

                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_save', ['menu' => 'Subject'])
                ));
            }
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $subject = Subject::find($request->id);

            if ($subject->delete()) {

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Subject']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Subject'])
                ));
            }
        }
    }

    function add() {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $parent_subject = Subject::whereNull('parent')->get();

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['parent_subject'] = $parent_subject;
        return view('subject.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama Brand'
        );

        $validator = Validator::make($request->all(), array(
            'name' => 'required|unique:brands'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $parent = $request->input('parent');
            $description = $request->input('description_indonesia');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            $publish = $request->input('publish');

            $slug = strtolower(str_slug($name, "_"));

            $subject = new Subject();
            $subject->name = $name;
            $subject->description = ($description != "") ? $description : NULL;
            $subject->meta_description = ($meta_description != "") ? $meta_description : NULL;
            $subject->meta_keyword = ($meta_keyword != "") ? $meta_keyword : NULL;
            $subject->publish = ($publish != "") ? "T" : "F";
            $subject->parent = ($parent != "") ? $parent : NULL;
            $subject->url = $slug;

            if ($subject->save()) {
                $menu = $menu_uri;

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Subject']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Subject']));
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

        $subject = Subject::find($id);
        $parent_subject = Subject::whereNull('parent')->get();

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['subject'] = $subject;
        $data['parent_subject'] = $parent_subject;
        return view('subject.update')->with($data);
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'name' => 'Nama Subject',
            'ur' => 'Url Subject'
        );

        $subject = Subject::find($id);

        if ($subject->name == $request->name) {
            $is_unique = "";
        } else {
            $is_unique = "|unique:categories";
        }

        if ($subject->url) {
            $is_url_unique = "";
        } else {
            $is_url_unique = "|unique:categories";
        }

        $validator = Validator::make($request->all(), array(
                    'name' => 'required' . $is_unique,
                    'url' => $is_url_unique
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $parent = $request->input('parent');
            $description = $request->input('description_indonesia');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            $publish = $request->input('publish');

            $slug = strtolower(str_slug($name, "_"));

            $subject->name = $name;
            $subject->description = ($description != "") ? $description : NULL;
            $subject->meta_description = ($meta_description != "") ? $meta_description : NULL;
            $subject->meta_keyword = ($meta_keyword != "") ? $meta_keyword : NULL;
            $subject->publish = ($publish != "") ? "T" : "F";
            $subject->parent = ($parent != "") ? $parent : NULL;
            $subject->url = $slug;
            if ($subject->save()) {

                return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Subject']));
            } else {
                return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Subject']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri. "/" . $id)->withErrors($validator)->withInput();
        }
    }

}