<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;

class ApprovedPPMPController extends Controller
{
  public function Indicative_index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Approved PPMP"]
    ];
    // $ppmp =  Http::withToken(session('token'))->post(env('APP_API'). "/api/approved-ppmp/index",[
    //   'campus' => session('campus'),
    // ])->json();
    // $aes = new GlobalDeclare();
    // $project_category = $aes->project_category_num($id);
    $ppmp = DB::table("ppmps as p")
        ->select('d.department_name','pt.project_title','pt.project_code','pt.id','pt.employee_id','pt.department_id','pt.project_category')
        ->selectRaw('SUM(estimated_price) as Total')
        ->join('project_titles as pt','pt.id','=','p.project_code')
        ->join('departments as d','d.id','=','pt.department_id')
        ->where('pt.campus','=', session('campus'))
        ->where('pt.status','=', 4)
        ->where('pt.deleted_at','=', NULL)
        // ->where('p.campus','=',session('campus'))
        ->where('pt.project_category','=', 0)
        ->groupBy('p.project_code')
        -> get();

    return view('pages.bac.ppmp.view-Approved-ppmp',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    [
      'data' => $ppmp,
      // 'data' => $ppmp['data'],
    ] 
    );
    
  }
  public function PPMP_index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Approved PPMP"]
    ];
    // $ppmp =  Http::withToken(session('token'))->post(env('APP_API'). "/api/approved-ppmp/index",[
    //   'campus' => session('campus'),
    // ])->json();
    // $aes = new GlobalDeclare();
    // $project_category = $aes->project_category_num($id);
    $ppmp = DB::table("ppmps as p")
        ->select('d.department_name','pt.project_title','pt.project_code','pt.id','pt.employee_id','pt.department_id','pt.project_category')
        ->selectRaw('SUM(estimated_price) as Total')
        ->join('project_titles as pt','pt.id','=','p.project_code')
        ->join('departments as d','d.id','=','pt.department_id')
        ->where('pt.campus','=', session('campus'))
        ->where('pt.status','=', 4)
        ->where('pt.deleted_at','=', NULL)
        // ->where('p.campus','=',session('campus'))
        ->where('pt.project_category','=', 1)
        ->groupBy('p.project_code')
        -> get();

    return view('pages.bac.ppmp.view-Approved-ppmp',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    [
      'data' => $ppmp,
      // 'data' => $ppmp['data'],
    ] 
    );
    
  }
  public function Supplemental_index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Approved PPMP"]
    ];
    // $ppmp =  Http::withToken(session('token'))->post(env('APP_API'). "/api/approved-ppmp/index",[
    //   'campus' => session('campus'),
    // ])->json();
    // $aes = new GlobalDeclare();
    // $project_category = $aes->project_category_num($id);
    $ppmp = DB::table("ppmps as p")
        ->select('d.department_name','pt.project_title','pt.project_code','pt.id','pt.employee_id','pt.department_id','pt.project_category')
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

  public function signed_indicative(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Approved PPMP"]
    ];
    // $ppmp =  Http::withToken(session('token'))->post(env('APP_API'). "/api/approved-ppmp/index",[
    //   'campus' => session('campus'),
    // ])->json();
    // $aes = new GlobalDeclare();
    // $project_category = $aes->project_category_num();
    $ppmp = DB::table("signed_ppmp as sp")
        ->select('d.department_name','sp.*')
        ->join('departments as d','d.id','=','sp.department_id')
        ->where('sp.campus','=', session('campus'))
        ->where('sp.deleted_at','=', NULL)
        ->where('sp.project_category','=', 0)
        -> get();
      // dd($ppmp);
    return view('pages.bac.ppmp.view-signed-ppmp',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    [
      'data' => $ppmp,
      // 'data' => $ppmp['data'],
    ] 
    );
    
  }

  public function signed_ppmp(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Approved PPMP"]
    ];
    // $ppmp =  Http::withToken(session('token'))->post(env('APP_API'). "/api/approved-ppmp/index",[
    //   'campus' => session('campus'),
    // ])->json();
    // $aes = new GlobalDeclare();
    // $project_category = $aes->project_category_num();
    $ppmp = DB::table("signed_ppmp as sp")
        ->select('d.department_name','sp.*')
        ->join('departments as d','d.id','=','sp.department_id')
        ->where('sp.campus','=', session('campus'))
        ->where('sp.deleted_at','=', NULL)
        ->where('sp.project_category','=', 1)
        -> get();
      // dd($ppmp);
    return view('pages.bac.ppmp.view-signed-ppmp',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    [
      'data' => $ppmp,
      // 'data' => $ppmp['data'],
    ] 
    );
    
  }

  public function signed_supplemental(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Approved PPMP"]
    ];
    // $ppmp =  Http::withToken(session('token'))->post(env('APP_API'). "/api/approved-ppmp/index",[
    //   'campus' => session('campus'),
    // ])->json();
    // $aes = new GlobalDeclare();
    // $project_category = $aes->project_category_num();
    $ppmp = DB::table("signed_ppmp as sp")
        ->select('d.department_name','sp.*')
        ->join('departments as d','d.id','=','sp.department_id')
        ->where('sp.campus','=', session('campus'))
        ->where('sp.deleted_at','=', NULL)
        ->where('sp.project_category','=', 2)
        -> get();
      // dd($ppmp);
    return view('pages.bac.ppmp.view-signed-ppmp',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    [
      'data' => $ppmp,
      // 'data' => $ppmp['data'],
    ] 
    );
    
  }

  public function show(Request $request){
    // dd($request->all());
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $project_code = $aes->decrypt($request->project_code);
    $response = DB::table("ppmps")
              ->select('project_titles.project_title','project_titles.project_code as ProjectCode','ppmps.*')
              ->join('project_titles','project_titles.id','=','ppmps.project_code')
              ->where('ppmps.project_code','=',$project_code)
              ->where('ppmps.status','=', 4)
              ->where('project_titles.project_category','=', $request->project_category)
              ->where('ppmps.deleted_at','=', NULL)
              ->where('ppmps.is_supplemental','=',0)
              -> get();
    // dd($response);
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

  public function view_signed_ppmp(Request $request) {
    // dd($request->all());
    try {
        $response = \DB::table('signed_ppmp')
            // ->where('employee_id', session('employee_id'))
            ->where('department_id', session('department_id'))
            ->where('campus', session('campus'))
            ->where('id', (new AESCipher)->decrypt($request->id))
            ->whereNull('deleted_at')
            ->get([
                'file_directory',
                'signed_ppmp'
            ]);
        # this will created history_log
            // (new HistoryLogController)->store(
            //     session('department_id'),
            //     session('employee_id'),
            //     session('campus'),
            //     null,
            //     'Viewed sigend ppmp',
            //     'View',
            //     $request->ip(),
            // );
        # end
        return response ([
            'status'    => 200,
            'data'  => $response
        ]);
    } catch (\Throwable $th) {
        return view('pages.error-500');
        throw $th;
    }
  }
  
  public function download_signed_PPMP(Request $request) {
    // dd($request->all());
    try {
        $response = \DB::table('signed_ppmp')
        // ->where('employee_id', session('employee_id'))
        ->where('department_id', session('department_id'))
        ->where('campus', session('campus'))
        ->where('id', (new AESCipher)->decrypt($request->id))
        ->whereNull('deleted_at')
        ->get([
            'signed_ppmp',
            'file_directory'
        ]);
        // dd($response );
        # this will created history_log
            // (new HistoryLogController)->store(
            //     session('department_id'),
            //     session('employee_id'),
            //     session('campus'),
            //     null,
            //     'Downloaded sigend ppmp',
            //     'Download',
            //     $request->ip(),
            // );
        # end
        $path = 'department_upload/signed_ppmp/';
        return \Storage::download($path.$response[0]->signed_ppmp);
    } catch (\Throwable $th) {
        throw $th;
        return view('pages.error-500');
    }
  }
}
