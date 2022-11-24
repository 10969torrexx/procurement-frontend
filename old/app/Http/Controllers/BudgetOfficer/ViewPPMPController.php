<?php

namespace App\Http\Controllers\BudgetOfficer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Deadline;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Support\Facades\Validator;
use DB;

class ViewPPMPController extends Controller
{
    public function PPMPindex(){
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["name" =>"PPMPs"]
      ];
      
      $ppmp = DB::table("project_titles as pt")
            ->select("pt.*","ab.allocated_budget","ab.remaining_balance","fs.fund_source")
            // ->selectRaw("Sum(p.estimated_price) as Total")
            ->join('ppmps as p','p.project_code','=','pt.id')
            ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
            ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
            ->whereNull("pt.deleted_at")
            ->where("pt.status","!=", 0)  
            ->where("pt.department_id","=", session("department_id"))  
            // ->orwhere("pt.status","=", 2)  
            // ->orwhere("pt.status","=", 6)  
            // ->orwhere("pt.status","=", 3)   
            ->where("p.is_supplemental","=", 0)
            ->groupBy("pt.project_title")
            -> get();

            
      $item = DB::table("ppmps as p")
            ->select("pt.project_code as code","pt.id as pt_id","pt.project_title as title","p.*")
            ->join('project_titles as pt','pt.id','=','p.project_code')
            ->whereNull("p.deleted_at")
            ->where("p.is_supplemental","=", 0)
            -> get();

      // $ppmp =  Http::withToken(session('token'))->get(env('APP_API'). "/api/supervisor/index")->json();
      // dd($ppmp);
      return view('pages.budgetofficer.view-ppmp', compact('ppmp','item'),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]);
        // $pageConfigs = ['pageHeader' => true];
        // $breadcrumbs = [
        //   ["link" => "/", "name" => "Home"],["name" =>"Supervisor"]
        // ];
        
        // $ppmp = DB::table("project_titles as pt")
        //       ->select("pt.*")
        //       ->join('ppmps as p','p.project_code','=','pt.id')
        //       ->whereNull("pt.deleted_at")
        //       ->where("pt.status","=", 1)  
        //       ->orwhere("pt.status","=", 2)  
        //       ->orwhere("pt.status","=", 3)   
        //       ->where("p.is_supplemental","=", 0)
        //       ->groupBy("pt.project_title")
        //       -> get();
        // // $ppmp =  Http::withToken(session('token'))->get(env('APP_API'). "/api/supervisor/index")->json();
        // // dd($ppmp);
        // return view('pages.budgetofficer.view-ppmp', compact('ppmp'),
        // [
        //   'pageConfigs'=>$pageConfigs,
        //   'breadcrumbs'=>$breadcrumbs
        // ]);
        // $pageConfigs = ['pageHeader' => true];
        // $breadcrumbs = [
        //   ["link" => "/", "name" => "Home"],["name" => "Pending PPMP"]
        // ];
        // // return view('pages.budgetofficer.allocatebudget',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);

        // $ppmp_deadline =  Http::withToken(session('token'))->get(env('APP_API'). "/api/budget/get_deadline")->json();
        //     // dd($ppmp_deadline);
        //     $error="";
        //     if($ppmp_deadline['status']==400){
        //         $error=$ppmp_deadline['message'];
        //     }

        //     return view('pages.budgetofficer.view-ppmp',compact('ppmp_deadline'), [
        //         'pageConfigs'=>$pageConfigs,
        //         'breadcrumbs'=>$breadcrumbs,
        //         'error' => $error,
        //         'allocated_budgets' => $ppmp_deadline['data'],
        //     ]); 
    }

    public function show(Request $request){
       

      $aes = new AESCipher();
      $id = $aes->decrypt($request->project_code);
      $data = DB::table("ppmps as p")
                ->select("pt.project_code as code","pt.status as projectStatus","pt.id as pt_id","pt.project_title as title","p.*")
                ->join('project_titles as pt','pt.id','=','p.project_code')
                ->where('p.project_code','=',$id)
                ->whereNull("p.deleted_at")
                ->where("p.is_supplemental","=", 0)
                // ->where("p.status","=", 1)  
                // ->orwhere("p.status","=", 2)  
                // ->orwhere("p.status","=", 3)   
                -> get();
      // $title = $data->title;
      dd($data);   
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["link" => "/bac/supervisor","name" =>"Supervisor"],["name" =>"PPMP"]
      ];
          return view('pages.supervisor.supervisor_check_ppmp',compact('data'),
          [
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs
          ]);
        }
}
