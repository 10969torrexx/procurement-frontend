<?php

namespace App\Http\Controllers\President;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use DB;
use Carbon\Carbon;

class PresidentHopeController extends Controller
{
    public function list(){
      $app = DB::table("project_titles")
                ->select("year_created","id","pres_status")
                ->whereNull("deleted_at")
                ->where("project_category","=", 1)
                ->where("status","=", 4)  
                ->where("pres_status","=", 0)
                ->groupBy("year_created")
                -> get();
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["link" => "/supervisor","name" =>"Supervisor"],["name" =>"PPMP"]
      ];
      return view('pages.president.list',compact('app'),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]);
    }

    public function Traditional_index(){
      
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" =>"Supervisor"]
        ];
        
        $ppmp = DB::table("project_titles as pt")
              ->select("pt.*","ab.allocated_budget","ab.remaining_balance","fs.fund_source","u.name as username")
              // ->selectRaw("Sum(p.estimated_price) as Total")
              ->join('ppmps as p','p.project_code','=','pt.id')
              ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
              ->join('fund_sources as fs','fs.id','=','pt.fund_source')
              ->join('users as u','u.employee_id','=','pt.employee_id')
              ->whereNull("pt.deleted_at")
              ->where("pt.status","!=", 0)
              ->where("pt.campus",session('campus'))
              ->where("pt.department_id","=", session("department_id"))  
              // ->orwhere("pt.status","=", 2)  
              // ->orwhere("pt.status","=", 6)  
              // ->orwhere("pt.status","=", 3)   
              ->where("pt.project_category","=", 1)
              ->groupBy("pt.project_title")
              -> get();
              // dd($ppmp);

              
        $item = DB::table("ppmps as p")
              ->select("pt.project_code as code","pt.id as pt_id","pt.project_title as title","p.*")
              ->join('project_titles as pt','pt.id','=','p.project_code')
              ->whereNull("p.deleted_at")
              ->where("pt.campus",session('campus'))
              // ->where("p.is_supplemental","=", 0)
              -> get();

        // $ppmp =  Http::withToken(session('token'))->get(env('APP_API'). "/api/supervisor/index")->json();
        return view('pages.supervisor.traditional.ppmp', compact('ppmp','item'),
        [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs
        ]);
    }
    
    public function Supplemental_index(){
      
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["name" =>"Supervisor"]
      ];
      
      $ppmp = DB::table("project_titles as pt")
            ->select("pt.*","ab.allocated_budget","ab.remaining_balance","fs.fund_source","u.name as username")
            // ->selectRaw("Sum(p.estimated_price) as Total")
            ->join('ppmps as p','p.project_code','=','pt.id')
            ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
            ->join('fund_sources as fs','fs.id','=','pt.fund_source')
            ->join('users as u','u.employee_id','=','pt.employee_id')
            ->whereNull("pt.deleted_at")
            ->where("pt.status","!=", 0)
            ->where("pt.campus",session('campus'))
            ->where("pt.department_id","=", session("department_id"))  
            // ->orwhere("pt.status","=", 2)  
            // ->orwhere("pt.status","=", 6)  
            // ->orwhere("pt.status","=", 3)   
            ->where("pt.project_category","=", 2)
            ->groupBy("pt.project_title")
            -> get();
            // dd($ppmp);

            
      $item = DB::table("ppmps as p")
            ->select("pt.project_code as code","pt.id as pt_id","pt.project_title as title","p.*")
            ->join('project_titles as pt','pt.id','=','p.project_code')
            ->whereNull("p.deleted_at")
            ->where("pt.campus",session('campus'))
            // ->where("p.is_supplemental","=", 0)
            -> get();

      // $ppmp =  Http::withToken(session('token'))->get(env('APP_API'). "/api/supervisor/index")->json();
      return view('pages.supervisor.supplemental.ppmp', compact('ppmp','item'),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]);
    }

    public function Indicative_index(){
      
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["name" =>"Supervisor"]
      ];
      
      $ppmp = DB::table("project_titles as pt")
            ->select("pt.*","ab.allocated_budget","ab.remaining_balance","fs.fund_source","u.name as username")
            // ->selectRaw("Sum(p.estimated_price) as Total")
            ->join('ppmps as p','p.project_code','=','pt.id')
            ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
            ->join('fund_sources as fs','fs.id','=','pt.fund_source')
            ->join('users as u','u.employee_id','=','pt.employee_id')
            ->whereNull("pt.deleted_at")
            ->where("pt.status","!=", 0)
            ->where("pt.campus",session('campus'))
            ->where("pt.department_id","=", session("department_id"))  
            // ->orwhere("pt.status","=", 2)  
            // ->orwhere("pt.status","=", 6)  
            // ->orwhere("pt.status","=", 3)   
            ->where("pt.project_category","=", 0)
            ->groupBy("pt.project_title")
            -> get();
            // dd($ppmp);

            
      $item = DB::table("ppmps as p")
            ->select("pt.project_code as code","pt.id as pt_id","pt.project_title as title","p.*")
            ->join('project_titles as pt','pt.id','=','p.project_code')
            ->whereNull("p.deleted_at")
            ->where("pt.campus",session('campus'))
            // ->where("p.is_supplemental","=", 0)
            -> get();

      // $ppmp =  Http::withToken(session('token'))->get(env('APP_API'). "/api/supervisor/index")->json();
      return view('pages.supervisor.indicative.ppmp', compact('ppmp','item'),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]);
    }

}
