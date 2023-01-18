<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;

class ApprovedSupplementalController extends Controller
{
  
  public function index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Approved PPMP"]
    ];
    // $ppmp =  Http::withToken(session('token'))->post(env('APP_API'). "/api/approved-ppmp/index",[
    //   'campus' => session('campus'),
    // ])->json();

    $ppmp = DB::table("ppmps as p")
        ->select('d.department_name','pt.project_title','pt.project_code','pt.id','pt.employee_id','pt.department_id')
        ->selectRaw('SUM(estimated_price) as Total')
        ->join('project_titles as pt','pt.id','=','p.project_code')
        ->join('departments as d','d.id','=','pt.department_id')
        ->where('pt.campus','=', session('campus'))
        ->where('pt.status','=', 4)
        ->where('pt.deleted_at','=', NULL)
        // ->where('p.campus','=',session('campus'))
        ->where('pt.project_category','=', 2)
        ->groupBy('p.project_code')
        -> get();

    return view('pages.bac.ppmp.view-Approved-ppmp',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    [
      'data' => $ppmp,
      // 'data' => $ppmp['data'],
    ] 
    );
    
  }

  public function show(Request $request){
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $project_code = $aes->decrypt($request->project_code);
    $response = DB::table("ppmps")
              ->select('project_titles.project_title','project_titles.project_code as ProjectCode','ppmps.*')
              ->join('project_titles','project_titles.id','=','ppmps.project_code')
              ->where('ppmps.project_code','=',$project_code)
              ->where('ppmps.status','=', 4)
              ->where('ppmps.deleted_at','=', NULL)
              ->where('ppmps.is_supplemental','=',1)
              -> get();
        // $category=Category::all();
        // return $users;
        if(count($response) > 0)
        {
            return response()->json([
            'status' => 200, 
            'data'=>$response,
            // 'data1'=>$category,
            // 'id'=>$aes->encrypt($id),
            ]);    
        }
        else{
        return response()->json([
                    'status' => 400, 
                    'message' => 'Does not exist!.',
                ]); 
        }
  }
}
