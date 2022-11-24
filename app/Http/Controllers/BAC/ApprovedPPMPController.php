<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApprovedPPMPController extends Controller
{
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Approved PPMP"]
        ];
        $ppmp =  Http::withToken(session('token'))->post(env('APP_API'). "/api/approved-ppmp/index",[
          'campus' => session('campus'),
        ])->json();
       
        // $table = $ppmp['data'];
        // $modal = $ppmp['data2'];
        // $all = [$table];
        // dd($ppmp);
        // if($ppmp['data']['project_code']==)  
        return view('pages.bac.ppmp.view-Approved-ppmp',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
          [
            'data' => $ppmp['data'],
            // 'data' => $ppmp['data'],
          ] 
          );
        // if($ppmp==null)
        // {
        //   return view('pages.error-500');
        // }
        // else{
        //   return view('pages.bac.ppmp.view-Approved-ppmp',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
        //   [
        //     'data' => $ppmp['data'],
        //     // 'data' => $ppmp['data'],
        //   ] 
        //   );
        // }
        
      }

      public function show(Request $request){
        
      // dd($request->all()); 
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $project_code = $aes->decrypt($request->project_code);
        // $employee_id = $aes->decrypt($request->employee_id);
        // $department_id = $aes->decrypt($request->department_id);
        // dd($id1);  
        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/approved-ppmp/show",[
          'project_code' => $project_code,
          // 'employee_id' => $employee_id,
          // 'department_id' => $department_id,
      ])->json();
      // dd($response); 
            return $response; 
      }
}
