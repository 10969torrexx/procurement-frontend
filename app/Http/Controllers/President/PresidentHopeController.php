<?php

namespace App\Http\Controllers\President;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HistoryLogController;
use Carbon\Carbon;

class PresidentHopeController extends Controller
{
  public function list($id){
    $aes = new GlobalDeclare();
    $project_category = $aes->project_category_num($id);
    // dd($project_category);
    $app = DB::table("project_titles as pt")
              ->join("ppmps as p", "p.project_code", "=", "pt.id")
              ->where("pt.project_category","=", $project_category)
              ->where("p.app_type","=", "Non-CSE")
              ->where("pt.campus",session('campus'))
              ->where("pt.status","=", 4)  
              // ->where("pt.pres_status","=", 0)  
              ->where(function ($query) {
                  $query->where("pt.per_campus_status","=", 1)
                  ->orWhere("pt.per_campus_status","=", 3);
                })
              ->whereNull("pt.deleted_at")
              ->whereNull("p.deleted_at")
              ->groupBy("project_year")
              -> get();
              
    $app1 = DB::table("project_titles as pt")
            ->join("ppmps as p", "p.project_code", "=", "pt.id")
            ->where("pt.project_category","=", $project_category)
            ->where("p.app_type","=", "Non-CSE") 
            ->where("pt.status","=", 4)  
            ->where("pt.endorse",1)
            ->where(function ($query) {
                $query->where("pt.univ_wide_status","=", 1)
                ->orWhere("pt.univ_wide_status","=", 3);
              })
            ->whereNull("pt.deleted_at")
            ->whereNull("p.deleted_at")
            ->groupBy("project_year")
            -> get();
            // dd($app1);

    $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          // ->where("Year", $val)
          ->where("Role","=",2)
          ->get();

    // dd($app);
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" =>"APP NON CSE"]
    ];
    return view('pages.president.list',compact('app','app1','approved_by'),
    [
      'pageConfigs'=>$pageConfigs,
      'breadcrumbs'=>$breadcrumbs
    ]);
  }

  public function index_app_noncse(Request $request){
    // dd($request->all());
      $aes = new AESCipher();
      $year = $aes->decrypt($request->year);
      $category = $aes->decrypt($request->category);
      $cat = new GlobalDeclare();
      $project_category = $cat->project_category($category);
      $scope = $request->scope;
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["link" => "/president/list/".$project_category,"name" => "APP NON CSE"],["link" => "/", "name" => $year]
      ];
      if($request->scope == 1){

        $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->whereNull("pt.deleted_at")
            ->where("pt.project_year","=",$year)
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where(function ($query) {
                $query->where("pt.per_campus_status","=", 1)
                ->orWhere("pt.per_campus_status","=", 3);
              })
            ->where("p.campus", session('campus'))
            ->groupBy("p.campus")
            ->groupBy("p.item_category")
            ->orderBy("p.campus","ASC")
            ->get();
            // dd($Categories);

        $ppmps = DB::table("ppmps as p")
              ->select("pt.project_title", "d.department_name", "p.*", "pt.fund_source","pt.project_code as ProjectCode","ab.allocated_budget","ab.remaining_balance","fs.fund_source","m.mode_of_procurement as procurementName")
              ->join("project_titles as pt", "p.project_code", "=", "pt.id")
              ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
              ->join("departments as d", "pt.department_id", "=", "d.id")
              ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
              ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
              ->where("p.app_type", 'Non-CSE')
              ->whereNull("p.deleted_at")
              ->where("pt.project_year","=",$year)
              ->where(function ($query) {
                  $query->where("pt.per_campus_status","=", 1)
                  ->orWhere("pt.per_campus_status","=", 3);
                })
              ->where("pt.project_category", "=", $category)
              ->where("p.status", "=", 4)
              ->where("pt.status", "=", 4)
              ->where("p.campus", session('campus'))
              ->orderBy("p.department_id", "ASC")
              ->orderBy("p.project_code", "ASC")
              ->get();
              // dd($ppmps);

        $campusCheck = DB::table("project_titles as pt")
              ->select("pt.*","p.app_type")
              ->join("ppmps as p", "p.project_code", "=", "pt.id")
              ->whereNull("pt.deleted_at")
              ->where("p.app_type", 'Non-CSE')
              ->where("pt.project_category", "=", $category)
              ->where("p.status", "=", 4)
              ->where("pt.status", "=", 4)
              ->where("pt.project_year",$year)
              ->where(function ($query) {
                  $query->where("pt.per_campus_status","=", 1)
                  ->orWhere("pt.per_campus_status","=", 3);
                })
              ->where("pt.campus", session('campus'))
              ->groupBy("pt.campus")
              ->get();
          
        $prepared_by = DB::table("signatories_app_non_cse")
            ->where("campus",session('campus'))
            ->where("project_category", "=", $category)
            ->where("Year",$year)
            ->where("Role","=",1)
            ->get();
          // dd($prepared_by);
          
        $signatories = DB::table("signatories_app_non_cse")
            ->where("campus",session('campus'))
            ->where("project_category", "=", $category)
            ->where("Year",$year)
            ->where('Role',2)
            ->where("users_id",'=',session('user_id'))
            ->get();

        $recommending_approval = DB::table("signatories_app_non_cse")
            ->where("campus",session('campus'))
            ->where("project_category", "=", $category)
            ->where("Year",$year)
            ->where("Role","=",3)
            ->get();

        $approved_by = DB::table("signatories_app_non_cse")
            ->where("campus",session('campus'))
            ->where("project_category", "=", $category)
            ->where("Year",$year)
            ->where("Role","=",2)
            ->get();

      }else{
        $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->whereNull("pt.deleted_at")
            ->where("pt.project_year","=",$year)
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.endorse",1)
            ->where("pt.project_category", "=", $category)
            ->where(function ($query) {
                $query->where("pt.univ_wide_status","=", 1)
                ->orWhere("pt.univ_wide_status","=", 3);
              })
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->groupBy("p.campus")
            ->groupBy("p.item_category")
            ->orderBy("p.campus","ASC")
            ->get();
            // dd($Categories);

        $ppmps = DB::table("ppmps as p")
              ->select("pt.project_title", "d.department_name", "p.*", "pt.fund_source","pt.project_code as ProjectCode","ab.allocated_budget","ab.remaining_balance","fs.fund_source","m.mode_of_procurement as procurementName")
              ->join("project_titles as pt", "p.project_code", "=", "pt.id")
              ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
              ->join("departments as d", "pt.department_id", "=", "d.id")
              ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
              ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
              ->where("p.app_type", 'Non-CSE')
              ->whereNull("p.deleted_at")
              ->where("pt.endorse",1)
              ->where(function ($query) {
                  $query->where("pt.univ_wide_status","=", 1)
                  ->orWhere("pt.univ_wide_status","=", 3);
                })
              ->where("pt.project_year","=",$year)
              ->where("pt.project_category", "=", $category)
              ->where("p.status", "=", 4)
              ->where("pt.status", "=", 4)
              ->orderBy("p.department_id", "ASC")
              ->orderBy("p.project_code", "ASC")
              ->get();
              // dd($ppmps);

        $campusCheck = DB::table("project_titles as pt")
              ->select("pt.*","p.app_type")
              ->join("ppmps as p", "p.project_code", "=", "pt.id")
              ->whereNull("pt.deleted_at")
              ->where("pt.endorse",1)
              ->where(function ($query) {
                  $query->where("pt.univ_wide_status","=", 1)
                  ->orWhere("pt.univ_wide_status","=", 3);
                })
              ->where("p.app_type", 'Non-CSE')
              ->where("pt.project_category", "=", $category)
              ->where("p.status", "=", 4)
              ->where("pt.status", "=", 4)
              ->where("pt.project_year",$year)
              ->groupBy("pt.campus")
              ->get();
              
      
        $prepared_by = DB::table("signatories_app_non_cse")
            ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
            ->where("campus",session('campus'))
            ->where("project_category", "=", $category)
            ->where("Year",$year)
            ->where("Role","=",1)
            ->get();
            // dd($prepared_by);
            
        $signatories = DB::table("signatories_app_non_cse")
            ->where("campus",session('campus'))
            ->where("Year",$year)
            ->where("project_category", "=", $category)
            ->where('Role',2)
            ->where("users_id",'=',session('user_id'))
            ->get();
            // dd($signatories);

        $recommending_approval = DB::table("signatories_app_non_cse")
            ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
            ->where("campus",session('campus'))
            ->where("project_category", "=", $category)
            ->where("Year",$year)
            ->where("Role","=",3)
            ->get();

        $approved_by = DB::table("signatories_app_non_cse")
            ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
            ->where("campus",session('campus'))
            ->where("project_category", "=", $category)
            ->where("Year",$year)
            ->where("Role","=",2)
            ->get();
      }
      foreach( $approved_by as $stat){
        $approvedBystat = $stat->status;
      }

      $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get();
          
      return view('pages.President.app', compact('ppmps','signatories','campusinfo'/* ,'Project' */,'Categories','campusCheck','scope','prepared_by','recommending_approval','approved_by',/* 'blocked', 'expired'*/),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]);
  }

  public function generatepdf(Request $request){
    // dd($request->all());
    try{
        $scope = $request->scope;
        if($request->scope  == 1){
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->whereNull("pt.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type","=",$request->app_type)
            ->where("pt.project_category", "=", $request->project_category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where(function ($query) {
                $query->where("pt.per_campus_status","=", 1)
                ->orWhere("pt.per_campus_status","=", 3);
              })
            ->where("p.campus", session('campus'))
            ->groupBy("p.campus")
            ->groupBy("p.item_category")
            ->orderBy("p.campus","ASC")
            ->get();
          // dd($Categories);

          $ppmps = DB::table("ppmps as p")
            ->select("pt.project_title", "d.department_name", "p.*", "pt.fund_source","pt.project_code as ProjectCode","ab.allocated_budget","ab.remaining_balance","fs.fund_source","m.mode_of_procurement as procurementName")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
            ->join("departments as d", "pt.department_id", "=", "d.id")
            ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
            ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
            ->where("p.app_type","=",$request->app_type)
            ->whereNull("p.deleted_at")
            ->where("p.campus", session('campus'))
            ->where(function ($query) {
                $query->where("pt.per_campus_status","=", 1)
                ->orWhere("pt.per_campus_status","=", 3);
              })
            ->where("pt.project_category", "=", $request->project_category)
            ->where("pt.project_year","=",$request->year)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
            // dd($ppmps);

        }else if($request->scope  == 0 ){
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type","=",$request->app_type)
            ->where("pt.project_category", "=", $request->project_category)
            ->where("pt.endorse",1)
            ->where(function ($query) {
                $query->where("pt.univ_wide_status","=", 1)
                ->orWhere("pt.univ_wide_status","=", 3);
              })
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->groupBy("p.campus")
            ->groupBy("p.item_category")
            ->orderBy("p.campus","ASC")
            ->get();
          $ppmps = DB::table("ppmps as p")
            ->select("pt.project_title", "d.department_name", "p.*", "pt.fund_source","pt.project_code as ProjectCode","ab.allocated_budget","ab.remaining_balance","fs.fund_source","m.mode_of_procurement as procurementName")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
            ->join("departments as d", "pt.department_id", "=", "d.id")
            ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
            ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
            ->where("p.app_type","=",$request->app_type)
            ->whereNull("p.deleted_at")
            ->where("pt.endorse",1)
            ->where(function ($query) {
                $query->where("pt.univ_wide_status","=", 1)
                ->orWhere("pt.univ_wide_status","=", 3);
              })
            ->where("pt.project_category", "=", $request->project_category)
            ->where("pt.project_year","=",$request->year)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
          }

      $signatures = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category", "=", $request->project_category)
          ->where("Year",$request->year)
          ->get();
          // dd($signatures);
          

      $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category", "=", $request->project_category)
          ->where("Year",$request->year)
          ->where("Role","=",1)
          ->get();
          // dd($prepared_by);

      $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category", "=", $request->project_category)
          ->where("Year",$request->year)
          ->where("Role","=",3)
          ->get();
          // dd($recommending_approval);

      $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category", "=", $request->project_category)
          ->where("Year",$request->year)
          ->where("Role","=",2)
          ->get();
          // dd($approved_by);
          
        $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get();
        // dd($campusinfo);

          # this will created history_log
            (new HistoryLogController)->store(
              session('department_id'),
              session('employee_id'),
              session('campus'),
              NULL,
              'Generate PDF From President, Year: '.$request->year,", Category: ".$request->project_category.", Type: ".$request->app_type,
              'Generate PDF',
              $request->ip(),
            );
          # end 

        $pdf = \Pdf::loadView('pages.president.generate-pdf', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by','scope'))->setPaper('legal', 'landscape');
        return $pdf->download("APP_NON_CSE_".$request->year.".pdf"); 
        
    }catch (\Throwable $th) {
        throw $th;
    }
  }

  public function pres_decision(Request $request){
    // dd($request->all());
    try {
      if ($request->scope == 0) {
          $signatories = DB::table("signatories_app_non_cse")
            ->where("Year","=",$request->year)
            ->where("project_category", "=", $request->category)
            ->where("Role","=",2)
            ->where("campus",session('campus'))
            ->where("users_id",'=',session('user_id'))
            ->update([
              'univ_wide_status' => $request->value,
            ]);

            if($request->value == 1){
              $pt_value == 3;
            }else{
              $pt_value == 1;
            }
        
            # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'UAPP Approve / Not From President, Year: '.$request->year,
                'Approve / Not',
                $request->ip(),
              );
            # end 

          if($signatories){
            $project = DB::table("project_titles as pt")
                ->join("ppmps as p", "p.project_code", "=", "pt.id")
                ->where("pt.project_category","=", $request->category)
                ->where("p.app_type","=", $request->app_type)
                ->where("pt.status","=", 4)  
                ->where("pt.project_year","=", $request->year)
                ->where("pt.endorse",1)
                ->whereNull("pt.deleted_at")
                ->whereNull("p.deleted_at")
                ->update([
                  'pt.univ_wide_status' => $pt_value,
                ]);
            
            return response()->json([
              'status' => 200, 
          ]); 
          }else{
            return response()->json([
              'status' => 400, 
              'message' => 'Error!.',
            ]); 
          }
      } else {
          $signatories = DB::table("signatories_app_non_cse")
            ->where("Year","=",$request->year)
            ->where("project_category", "=", $request->category)
            ->where("Role","=",2)
            ->where("campus",session('campus'))
            ->where("users_id",'=',session('user_id'))
            ->update([
              'status' => $request->value,
            ]);
        
            if($request->value == 1){
              $pt_value == 3;
            }else{
              $pt_value == 1;
            }
            # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'Approve / Not From President, Year: '.$request->year,
                'Approve / Not',
                $request->ip(),
              );
            # end 

          if($signatories){
            
            $project = DB::table("project_titles as pt")
                ->join("ppmps as p", "p.project_code", "=", "pt.id")
                ->where("pt.project_category","=", $request->category)
                ->where("p.app_type","=", $request->app_type)
                ->where("pt.status","=", 4)  
                ->where("pt.campus",session('campus'))  
                ->where("pt.project_year","=", $request->year)
                ->whereNull("pt.deleted_at")
                ->whereNull("p.deleted_at")
                ->update([
                  'pt.per_campus_status' => $pt_value,
                ]);
            
            return response()->json([
              'status' => 200, 
              // 'data' => $supplier,
          ]); 
          }else{
            return response()->json([
              'status' => 400, 
              'message' => 'Error!.',
            ]); 
          }
      }
      
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function print(Request $request){
    // dd($request->all());
    try{
        $scope = $request->scope;
        if($request->scope  == 1){
          $Categories = DB::table("ppmps as p")
          ->join("project_titles as pt", "p.project_code", "=", "pt.id")
          ->whereNull("p.deleted_at")
          ->whereNull("pt.deleted_at")
          ->where("pt.project_year","=",$request->year)
          ->where("p.app_type","=",$request->app_type)
          ->where("pt.project_category", "=", $request->category)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where("p.campus", session('campus'))
          ->groupBy("p.campus")
          ->groupBy("p.item_category")
          ->orderBy("p.campus","ASC")
          ->get();
          // dd($Categories);

          $ppmps = DB::table("ppmps as p")
            ->select("pt.project_title", "d.department_name", "p.*", "pt.fund_source","pt.project_code as ProjectCode","ab.allocated_budget","ab.remaining_balance","fs.fund_source","m.mode_of_procurement as procurementName")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
            ->join("departments as d", "pt.department_id", "=", "d.id")
            ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
            ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
            ->where("p.app_type","=",$request->app_type)
            ->whereNull("p.deleted_at")
            ->where("p.campus", session('campus'))
            ->where("pt.project_category", "=", $request->category)
            ->where("pt.project_year","=",$request->year)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
            // dd($ppmps);

        }else if($request->scope == 0){
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type","=",$request->app_type)
            ->where("pt.project_category", "=", $request->category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->groupBy("p.campus")
            ->groupBy("p.item_category")
            ->orderBy("p.campus","ASC")
            ->get();

          $ppmps = DB::table("ppmps as p")
            ->select("pt.project_title", "d.department_name", "p.*", "pt.fund_source","pt.project_code as ProjectCode","ab.allocated_budget","ab.remaining_balance","fs.fund_source","m.mode_of_procurement as procurementName")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
            ->join("departments as d", "pt.department_id", "=", "d.id")
            ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
            ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
            ->where("p.app_type","=",$request->app_type)
            ->whereNull("p.deleted_at")
            ->where("pt.project_category", "=", $request->category)
            ->where("pt.project_year","=",$request->year)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
          }

      $signatures = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category", "=", $request->category)
          ->where("Year",$request->year)
          ->get();
          // dd($signatures);
          

      $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category", "=", $request->category)
          ->where("Year",$request->year)
          ->where("Role","=",1)
          ->get();
          // dd($prepared_by);

      $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category", "=", $request->category)
          ->where("Year",$request->year)
          ->where("Role","=",3)
          ->get();
          // dd($recommending_approval);

      $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category", "=", $request->category)
          ->where("Year",$request->year)
          ->where("Role","=",2)
          ->get();
          // dd($approved_by);
          
        $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get(); 
        // dd($campusinfo);

        # this will created history_log
          (new HistoryLogController)->store(
            session('department_id'),
            session('employee_id'),
            session('campus'),
            NULL,
            'Print App From President, Year: '.$request->year.", Category: ".$request->category.", Type: ".$request->app_type,
            'Print',
            $request->ip(),
          );
        # end 

        return view('pages.president.print', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by','scope'));
    }catch (\Throwable $th) {
        throw $th;
    }
  }
}
