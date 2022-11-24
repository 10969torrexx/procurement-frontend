<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApprovedSupplementalController extends Controller
{
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Approved Supplemental PPMP"]
        ];
        $ppmp =  Http::withToken(session('token'))->get(env('APP_API'). "/api/approved-supplemental/index",)->json();
        //  dd($ppmp);
        // $table = $ppmp['data'];
        // $all = $ppmp['data2'];
        // $modal = [$table,$all];
        // dd($ppmp);
        // if($ppmp['data']['project_code']==)  
        if($ppmp==null)
        {
          return view('pages.error-500');
        }
        else{
          return view('pages.bac.ppmp.view-Approved-supplemental',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
          [
            'data' =>$ppmp['data'],
            // 'data2' =>  $ppmp['data2'],
            // 'data' => $ppmp['data'],
          ] 
          );
        }
        
      }

      public function show(Request $request){
        // $id = $request->id;
        $aes = new AESCipher();
        $project_code = $aes->decrypt($request->project_code);
        $employee_id = $aes->decrypt($request->employee_id);
        $department_id = $aes->decrypt($request->department_id);
        // dd($id1);  
        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/approved-supplemental/show",[
        'project_code' => $project_code,
        'employee_id' => $employee_id,
        'department_id' => $department_id,
      ])->json();
      // dd($response); 
            return $response; 
      }
}
