<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class SignedPRsController extends Controller
{
    public function SignedPRIndex(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Signed PR"]
        ];
    
        $response = DB::table("signed_purchase_request as spr")
              ->select('spr.*','u.name','d.department_name')
              ->join('users as u','spr.employee_id','u.employee_id')
              ->join('departments as d','spr.department_id','d.id')
              ->where("spr.campus", session('campus'))
              ->whereNull("spr.deleted_at")
              ->get();
              // dd($response);
    
            return view('pages.bac.signed-pr-index',compact('response'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]); 
    }
}
