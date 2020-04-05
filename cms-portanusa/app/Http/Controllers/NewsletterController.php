<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;

use App\Newsletter;
use App\Header;

use App\Libraries\AppConfiguration;
use App\Exports\NewsletterExport;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class NewsletterController extends Controller {

    function index() {
        $uri = request()->segment(1);
        $menu = $uri;
        $data['menu'] = $menu;
        
        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('newsletter.list')->with($data);
    }

    function getLists() {
        DB::statement(DB::raw('set @rownum=0'));
        $newsletters = Newsletter::select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'email', 'created_at')
            ->orderBy('id', 'desc')    
            ->get();

        return Datatables::of($newsletters)
                ->editColumn('created_at', function ($newsletter) {
                    return date('d M Y H:i:s', strtotime($newsletter->created_at));
                })
                ->addColumn('action', function ($newsletter) {
                    $uri = request()->segment(1);
                    $menu = $uri;

                    $action_button = "";
                    $action_button .= '<a href="#" id="delete" data-id="' . $newsletter->id . '" class="btn btn-circle btn-icon-only btn-danger" title="Delete Newsletter"><i class="fa fa-trash"></i></a>';
            
                    return $action_button;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
        
            $newsletter = Newsletter::find($request->id);

            if ($newsletter->delete()) {

                $request->session()->flash('alert-success', trans('messages.success_delete', ['menu' => 'Newsletter']));
                return response()->json(array(
                            'status' => 'success'
                ));
            } else {
                return response()->json(array(
                            'status' => 'error',
                            'message' => trans('messages.failed_delete', ['menu' => 'Newsletter'])
                ));
            }
        }
    }

    function formExport() {
        $menu_uri = request()->segment(1);
        $submenu_uri = request()->segment(2);

        $data['menu'] = $menu_uri;
        $data['submenu'] = $submenu_uri;
        
        $domain = AppConfiguration::assetPortalDomain()->value;
        $path = AppConfiguration::logoPath()->value;

        $header = Header::find(1);
        $data['logo'] = $domain . '/' . $path . '/' . $header->logo;
        $data['icon'] = $domain . '/' . $path . '/' . $header->icon;

        return view('newsletter.export')->with($data);
    }
    
    function export(Request $request) {
        $periode = $request->input('periode');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');
        
        $data = [
            'periode' => $periode,
            'date_start' => $date_start,
            'date_end' => $date_end
        ];
        
        return (new NewsletterExport($data))->download('Newsletter.xlsx');        
    }

}