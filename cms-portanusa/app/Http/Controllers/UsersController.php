<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;
use App\User;
use App\Role;
use App\Role_user;
use App\User_admin;
use App\Header;
use App\Libraries\Shipping;
use App\Libraries\DisplayMenu;
use App\Libraries\AppConfiguration;
use App\Mail\UserRegister;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class UsersController extends Controller {

    function getDetail(Request $request) {
        if ($request->ajax()) {
            $output = array();
            $id = $request->input('id');
            $user = User::find($id);
            $role_users = Role_user::where('user_id', $user->id)->first();
            $roles = Role::find($role_users->role_id);
            if (!empty($user)) {
                if ($user->active == 1) {
                    $active = '<span class="label label-success label-sm">Aktif</span>';
                } else {
                    $active = '<span class="label label-danger label-sm">Tidak Aktif</span>';
                }

                $output = array(
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'telephone' => ($user->telephone != NULL) ? $user->telephone : "",
                    'handphone' => ($user->handphone != NULL) ? $user->handphone : "",
                    'active' => $active,
                    'group' => $roles->display_name,
                    'last_login_at' => ($user->last_login_at != NULL) ? date('d/M/Y H:i', strtotime($user->last_login_at)) : "-",
                    'created_at' => date('d/M/Y H:i', strtotime($user->created_at)),
                    'updated_at' => ($user->updated_at != NULL) ? date('d/M/Y H:i', strtotime($user->updated_at)) : "-"
                );
            }
            return response()->json($output);
        } else {
            abort(403);
        }
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));

        $position = 1;

        $users = User::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'users.id', 'users.first_name', 'users.email', 'roles.display_name', 'users.active')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.position', '>=', $position)
                ->get();

        return Datatables::of($users)
            ->editColumn('active', function ($user) {
                if ($user->active == 1) {
                    $active_checked = 'checked';
                } else {
                    $active_checked = '';
                }
                $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $user->id . '" data-size="mini" ' . $active_checked . '>';
            
                if ($user->active == 1) {
                    $active = '<span class="label label-success label-sm">Aktif</span>';
                } else {
                    $active = '<input type="checkbox" name="active" class="make-switch" data-id="' . $customer->id . '" data-size="mini" ' . $active_checked . '>';
                }
                
                return $active;
            })
            ->addColumn('action', function ($user) {
                $uri = request()->segment(1);
                $menu = $uri;

                $action_button = "";
                $action_button .= '<a href="' . url($menu . "/edit-user", $user->id) . '" class="btn btn-circle btn-icon-only btn-warning" title="Edit User"><i class="fa fa-pencil"></i></a>';
                $action_button .= '<a href="#" id="delete" data-id="' . $user->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete User"><i class="fa fa-trash"></i></a>';
            
                return $action_button;
            })
            ->rawColumns(['active', 'action'])
            ->make(true);
    }

    function index() {
        $uri = request()->segment(1);
        $data['menu'] = $uri;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('user.list')->with($data);
    }

    function add() {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $roles = Role::where('position', '>', 0)->get();
        $role_users = null;

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        $data['menu'] = $menu;
        $data['submenu'] = $submenu;
        $data['roles'] = $roles;
        $data['role_user'] = $role_users;

        return view('user.create')->with($data);
    }

    public function create(Request $request) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'first_name' => 'Nama Depan',
            'email' => 'Email',
            'role' => 'Grup Akses'
        );

        $validator = Validator::make($request->all(), array(
                    'first_name' => 'required',
                    'email' => 'required|unique:users|email',
                    'role' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);

        if (!$validator->fails()) {
            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $email = $request->input('email');
            $telephone = $request->input('telephone');
            $handphone = $request->input('handphone');
            $role_id = $request->input('role');
            $password = str_random(8);

            $user = new User;
            $user->ip_address = \Request::ip();
            $user->first_name = $first_name;
            $user->last_name = ($last_name != "") ? $last_name : NULL;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->telephone = ($telephone != "") ? $telephone : NULL;
            $user->handphone = ($handphone != "") ? $handphone : NULL;
            if ($user->save()) {

                $role_user = new Role_user();
                $role_user->user_id = $user->id;
                $role_user->role_id = $role_id;

                if ($role_user->save()) {

                    //Send Mail
                    Mail::to($email)->send(new UserRegister($user, $password));

                    return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Pengguna']));
                }

            } else {
                return redirect($menu_uri . "/" . $submenu_uri)->with('alert-error', trans('messages.failed_save', ['menu' => 'Pengguna']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri)->withErrors($validator)->withInput();
        }
    }

    function edit($user_id) {
        $menu_uri = request()->segment(1);
        $menu = $menu_uri;

        $submenu_uri = request()->segment(2);
        $submenu = $submenu_uri;

        $user = User::find($user_id);
        $role_users = Role_user::where('user_id', $user->id)->first();

        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        if (!empty($user)) {
            $roles = Role::where('position', '>', 0)->get();

            $data['menu'] = $menu;
            $data['submenu'] = $submenu;
            $data['user_access'] = $user;
            $data['role_users'] = $role_users;
            $data['roles'] = $roles;
            return view('user.update')->with($data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id) {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $attributeNames = array(
            'first_name' => 'Nama Depan',
            'email' => 'Email',
            'role' => 'Grup Akses'
        );

        $user = User::find($id);
        $role_user = Role_user::where('user_id', $user->id)->first();

        if ($user->email == $request->email) {
            $is_email_unique = "";
        } else {
            $is_email_unique = "|unique:users";
        }

        $validator = Validator::make($request->all(), array(
                    'first_name' => 'required',
                    'email' => 'required' . $is_email_unique . '|email',
                    'role' => 'required'
        ));
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {
            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $email = $request->input('email');
            $telephone = $request->input('telephone');
            $handphone = $request->input('handphone');
            $role_id = $request->input('role');

            $user->ip_address = \Request::ip();
            $user->first_name = $first_name;
            $user->last_name = ($last_name != "") ? $last_name : NULL;
            $user->email = $email;
            $user->telephone = ($telephone != "") ? $telephone : NULL;
            $user->handphone = ($handphone != "") ? $handphone : NULL;
            if ($user->save()) {

                $role_user->user_id = $user->id;
                $role_user->role_id = $role_id;

                if ($role_user->update()) {

                    return redirect($menu_uri)->with('alert-success', trans('messages.success_save', ['menu' => 'Pengguna']));
                }

            } else {
                return redirect($menu_uri . "/" . $submenu_uri . "/" . $id)->with('alert-error', trans('messages.failed_save', ['menu' => 'Pengguna']));
            }
        } else {
            return redirect($menu_uri . "/" . $submenu_uri . "/" . $id)->withErrors($validator)->withInput();
        }
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $user = User::find($request->id);

            if ($user->delete()) {

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Pengguna']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Pengguna'])
                ));
            }
        }
    }

}