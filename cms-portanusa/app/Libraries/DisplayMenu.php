<?php

namespace App\Libraries;

use App\Module;
use App\Permission;
use Illuminate\Support\Facades\Auth;

class DisplayMenu {

    public static function baseUrl() {
        // $module_id = 1;
        // $module = Module::find($module_id);

        // return $module->base_url;
    }

    public static function home() {
        // $menu_id = 1;
        // $menu = Permission::find($menu_id);
        // return $menu;
    }
    
    public static function getMenu($uri) {
        // $menu = Permission::where('base_url', $uri)->first();
        // return $menu;
    }

    public static function navigation() {
        // $menu = "";
        // $token = Auth::user()->app_token;
        // $role_id = Auth::user()->roles->first()->id;

        // $modules = Module::where('id', '!=', 1)->orderBy('position')->get();
        // if (!empty($modules)) {
        //     foreach ($modules as $module) {
        //         $permissions = Permission::select('permissions.*')
        //                 ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
        //                 ->where('level', 'head')
        //                 ->where('publish', 'T')
        //                 ->where('role_id', $role_id)
        //                 ->where('module_id', $module->id)
        //                 ->whereNull('parent')
        //                 ->orderBy('position', 'ASC')
        //                 ->get();

        //         if (!empty($permissions) && count($permissions) > 0) {
        //             $menu .= '<li class="nav-item">';
        //             $menu .= '<a href="javascript:;" class="nav-link nav-toggle">';
        //             $menu .= '<i class="' . $module->icon . '"></i>';
        //             $menu .= '<span class="title">' . $module->name . '</span>';
        //             $menu .= '<span class="arrow"></span>';
        //             $menu .= '</a>';
        //             $menu .= '<ul class="sub-menu">';

        //             foreach ($permissions as $permission) {
        //                 $current_url = request()->segment(1);
        //                 $active_class = ($permission->base_url == $current_url) ? "active open" : "";

        //                 $menu .= '<li class="nav-item ' . $active_class . '">';
        //                 $menu .= '<a href="' . $module->base_url . '/authenticated/' . $permission->base_url . '/' . $token . '" class="nav-link">';
        //                 $menu .= '<span class = "title">' . $permission->display_name . '</span>';
        //                 $menu .= ($permission->base_url == $current_url) ? '<span class="selected"></span>' : "";
        //                 $menu .= '</a>';
        //                 $menu .= '</li>';
        //             }
        //             $menu .= '</ul>';
        //             $menu .= '</li>';
        //         }
        //     }
        // }
        // return $menu;
    }

}
