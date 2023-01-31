<?php

namespace App\Http\Controllers\President;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Support\Facades\DB;;
use Carbon\Carbon;

class PresidentHopeController extends Controller
{
  public function list($id){
    
    $aes = new GlobalDeclare();
    $project_category = $aes->project_category_num($id);
    // dd($project_category);
    $app = DB::table("project_titles")
              // ->select("project_year","id","pres_status")
              ->whereNull("deleted_at")
              ->where("project_category","=", $project_category)
              ->where("status","=", 4)  
              // ->where("pres_status","=", 1)
              ->where(function ($query) {
                  $query->where("pres_status","=", 1)
                  ->orWhere("pres_status","=", 2)
                  ->orWhere("pres_status","=", 3);
                })
              ->groupBy("project_year")
              -> get();

    // dd($expired);
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" =>"APP NON CSE"]
    ];
    return view('pages.president.list',compact('app'),
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

      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["link" => "/president/list/".$project_category,"name" => "APP NON CSE"],["link" => "/", "name" => $year]
      ];
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
              $query->where("pt.pres_status","=", 1)
              ->orWhere("pt.pres_status","=", 2)
              ->orWhere("pt.pres_status","=", 3);
            })
          ->where("p.campus", session('campus'))
          ->groupBy("p.campus")
          ->groupBy("p.item_category")
          ->orderBy("p.campus","ASC")
          ->get();
          // dd($Categories);

      // $ppmps = DB::table("project_titles as pt")
      //       ->select("pt.*","ab.allocated_budget","ab.remaining_balance","fs.fund_source","u.name as username")
      //       // ->selectRaw("Sum(p.estimated_price) as Total")
      //       ->join('ppmps as p','p.project_code','=','pt.id')
      //       ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
      //       ->join('fund_sources as fs','fs.id','=','pt.fund_source')
      //       ->join('users as u','u.employee_id','=','pt.employee_id')
      //       ->whereNull("pt.deleted_at")
      //       ->where("pt.status","!=", 0)
      //       ->where("pt.campus",session('campus'))
      //       ->where("pt.department_id","=", session("department_id"))  
      //       ->where("pt.project_year","=",$year )  
      //       ->where("pt.project_category","=", 1)
      //       ->groupBy("pt.project_title")
      //       -> get();

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
            ->where("pt.project_category", "=", $category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where("p.campus", session('campus'))
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
            // dd($ppmps);

      $Project_title = DB::table("project_titles as pt")
        ->join("ppmps as p", "p.project_code", "=", "pt.id")
        ->whereNull("pt.deleted_at")
        ->where("pt.project_year","=",$year)
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.campus", session('campus'))
        ->where("pt.project_category", "=", $category)
        ->where("p.status", "=", 4)
        ->groupBy("pt.project_year")
        ->get("pt.project_year");
              // dd($Project_title);

      $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$year)
          ->where("Role","=",1)
          ->get();
          // dd($prepared_by);
          
      $signatories = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$year)
          ->where('Role',2)
          ->where("users_id",'=',session('user_id'))
          ->get();

      $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$year)
          ->where("Role","=",3)
          ->get();

      $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$year)
          ->where("Role","=",2)
          ->get();

      $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get();

      $campusCheck = DB::table("project_titles as pt")
          ->select("pt.campus","pt.endorse","pt.pres_status","pt.project_category","pt.project_year","p.app_type")
          ->join("ppmps as p", "p.project_code", "=", "pt.id")
          ->whereNull("pt.deleted_at")
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", $category)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where(function ($query) {
              $query->where("pt.pres_status","=", 1)
              ->orWhere("pt.pres_status","=", 2)
              ->orWhere("pt.pres_status","=", 3);
            })
          ->where("pt.project_year",$year)
          ->where("pt.campus", session('campus'))
          ->groupBy("pt.campus")
          ->get();

      // $expired = DB::table("project_titles")
      //     ->whereNull("deleted_at")
      //     ->where("project_category","=", $category)
      //     ->where("status","=", 4)  
      //     ->where("project_year",$year)
      //     ->where(function ($query) {
      //         $query->where("pres_status","=", 1)
      //         ->orWhere("pres_status","=", 2)
      //         ->orWhere("pres_status","=", 3);
      //       })
      //     ->get();

          
      $expired = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$year)
          ->where("users_id",'=',session('user_id'))
          ->where(function ($query) {
              $query->where("status","=", 0)
              ->orWhere("status","=", 1)
              ->orWhere("status","=", 2);
            })
          ->where('Role',2)
          ->whereDate('pres_created_at','<', Carbon::now()->subDays(1))
          ->get();

          // dd($expired);

      // $ppmp =  Http::withToken(session('token'))->get(env('APP_API'). "/api/supervisor/index")->json();
      return view('pages.President.app', compact('ppmps','signatories','campusinfo'/* ,'Project' */,'Categories','campusCheck','Project_title','prepared_by','recommending_approval','approved_by','expired'),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]);
  }

  public function generatepdf(Request $request){
    // dd($request->all());
    try{
        $count = $request->campusCheck;
        if($count  == 1){
          // $Categories = DB::table("ppmps as p")
          //   ->join("project_titles as pt", "p.project_code", "=", "pt.id")
          //   ->whereNull("p.deleted_at")
          //   ->where("pt.project_year","=",$request->year)
          //   ->where("p.app_type", 'Non-CSE')
          //   ->where("pt.project_category", "=", $request->category)
          //   ->where("p.status", "=", 4)
          //   ->where("pt.status", "=", 4)
          //   ->where("p.campus", session('campus'))
          //   ->groupBy("p.campus")
          //   ->groupBy("p.item_category")
          //   ->orderBy("p.campus","ASC")
          //   ->get();
          
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
              $query->where("pt.pres_status","=", 1)
              ->orWhere("pt.pres_status","=", 2)
              ->orWhere("pt.pres_status","=", 3);
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
            ->where("pt.project_category", "=", $request->project_category)
            ->where("pt.project_year","=",$request->year)
            ->where(function ($query) {
                $query->where("pt.pres_status","=", 1)
                ->orWhere("pt.pres_status","=", 2)
                ->orWhere("pt.pres_status","=", 3);
              })
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
            // dd($ppmps);

        }else if($count  > 1){
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type","=",$request->app_type)
            ->where("pt.project_category", "=", $request->project_category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where(function ($query) {
                $query->where("pt.pres_status","=", 1)
                ->orWhere("pt.pres_status","=", 2)
                ->orWhere("pt.pres_status","=", 3);
              })
            // ->where("p.campus", session('campus'))
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
            // ->where("p.campus", session('campus'))
            ->where("pt.project_category", "=", $request->project_category)
            ->where("pt.project_year","=",$request->year)
            ->where(function ($query) {
                $query->where("pt.pres_status","=", 1)
                ->orWhere("pt.pres_status","=", 2)
                ->orWhere("pt.pres_status","=", 3);
              })
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
            // dd($ppmps);
            // $Categories = DB::table("ppmps as p")
            // ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            // ->whereNull("p.deleted_at")
            // ->where("pt.project_year","=",$request->year)
            // ->where("p.app_type", 'Non-CSE')
            // ->where("pt.project_category", "=", $request->category)
            // ->where("pt.endorse", "=", 1)
            // ->where("p.status", "=", 4)
            // // ->where("p.campus", session('campus'))
            // ->groupBy("p.campus")
            // ->groupBy("p.item_category")
            // ->orderBy("p.campus","ASC")
            // ->get();

            // $ppmps = DB::table("ppmps as p")
            //   ->select("pt.project_title", "d.department_name", "p.*", "pt.fund_source","pt.project_code as ProjectCode","ab.allocated_budget","ab.remaining_balance","fs.fund_source","m.mode_of_procurement as procurementName")
            //   ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            //   ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
            //   ->join("departments as d", "pt.department_id", "=", "d.id")
            //   ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
            //   ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
            //   ->where("p.app_type", 'Non-CSE')
            //   ->whereNull("p.deleted_at")
            //   // ->where("p.campus", session('campus'))
            // ->where("pt.project_category", "=", $request->category)
            //   ->where("pt.project_year","=",$request->year)
            //   ->where("pt.endorse", "=", 1)
            //   ->where("p.status", "=", 4)
            //   ->orderBy("p.department_id", "ASC")
            //   ->orderBy("p.project_code", "ASC")
            //   ->get();
          }

      $signatures = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->get();
          // dd($signatures);
          

      $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",1)
          ->get();
          // dd($prepared_by);

      $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",3)
          ->get();
          // dd($recommending_approval);

      $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",2)
          ->get();
          // dd($approved_by);
          
        $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get();
        // dd($campusinfo);
        $pdf = \Pdf::loadView('pages.president.generate-pdf', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by'))->setPaper('legal', 'landscape');
        return $pdf->download("APP_NON_CSE_".$request->year.".pdf"); 
        
        
      //   $path = env('APP_NAME').'/APPNONCSE/pdf';
      //   if (!Storage::exists($path)) {
      //     Storage::makeDirectory($path);
      //   }
      // // $path = public_path('pdf/');
      //   $fileName =  time().'.'. 'pdf' ;
      //   $pdf->save($path . '/' . $fileName);

      //   $pdf = public_path('pdf/'.$fileName);
      //   return response()->download($pdf);
      // }
    }catch (\Throwable $th) {
        // return response()->json([
        //     'status' => 600,
        //     'message'   => $th
        // ]);
        throw $th;
    }
    
      // if($signatures) {
      //   return response()->json([
      //   'status' =>  200,
      //   'data'  =>  $signatures,
      //   ]);
      // }
      // else{
      //   return response()->json([
      //     'status' =>  400,
      //     'data'  =>  $signatures,
      //     ]);
      // }
  }

  public function pres_decision(Request $request){
    // dd($request->all());
    if($request->value == 1){
      // $Project_title = DB::table("project_titles as pt")
      //   ->join("ppmps as p", "p.project_code", "=", "pt.id")
      //   ->whereNull("pt.deleted_at")
      //   ->where("pt.project_year","=",$request->year)
      //   ->where("p.app_type","=",$request->app_type)
      //   ->where("pt.campus", session('campus'))
      //   ->where("pt.project_category", "=", $request->category)
      //   ->where("p.status", "=", 4)
      //   ->update([
      //     'pt.pres_status' => $request->value,
      //     'pt.pres_created_at' => Carbon::now(),
      //   ]);
      
      $signatories = DB::table("signatories_app_non_cse")
      ->where("Year","=",$request->year)
      ->where("Role","=",2)
      ->where("users_id",'=',session('user_id'))
      ->update([
        'status' => $request->value,
        'pres_created_at' => Carbon::now()
      ]);
    }else{
      // $Project_title = DB::table("project_titles as pt")
      //   ->join("ppmps as p", "p.project_code", "=", "pt.id")
      //   ->whereNull("pt.deleted_at")
      //   ->where("pt.project_year","=",$request->year)
      //   ->where("p.app_type","=",$request->app_type)
      //   ->where("pt.campus", session('campus'))
      //   ->where("pt.project_category", "=", $request->category)
      //   ->where("p.status", "=", 4)
      //   ->update([
      //     'pt.pres_status' => $request->value,
      //     'pt.pres_updated_at'=> Carbon::now(),
      //   ]);
      
      $signatories = DB::table("signatories_app_non_cse")
      ->where("Year","=",$request->year)
      ->where("users_id",'=',session('user_id'))
      ->update([
        'status' => $request->value,
        'pres_updated_at' => Carbon::now()
      ]);
    }
              // dd($Project_title);
              
      if($signatories){
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

  public function print(Request $request){
    // dd($request->all());
    try{
        $count = $request->campusCheck;
        if($count  == 1){
          $Categories = DB::table("ppmps as p")
          ->join("project_titles as pt", "p.project_code", "=", "pt.id")
          ->whereNull("p.deleted_at")
          ->whereNull("pt.deleted_at")
          ->where("pt.project_year","=",$request->year)
          ->where("p.app_type","=",$request->app_type)
          ->where("pt.project_category", "=", $request->category)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where(function ($query) {
              $query->where("pt.pres_status","=", 1)
              ->orWhere("pt.pres_status","=", 2)
              ->orWhere("pt.pres_status","=", 3);
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
            ->where("pt.project_category", "=", $request->category)
            ->where("pt.project_year","=",$request->year)
            ->where("p.status", "=", 4)
            ->where(function ($query) {
                $query->where("pt.pres_status","=", 1)
                ->orWhere("pt.pres_status","=", 2)
                ->orWhere("pt.pres_status","=", 3);
              })
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
            // dd($ppmps);

        }else if($count  > 1){
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type","=",$request->app_type)
            ->where("pt.project_category", "=", $request->category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where(function ($query) {
                $query->where("pt.pres_status","=", 1)
                ->orWhere("pt.pres_status","=", 2)
                ->orWhere("pt.pres_status","=", 3);
              })
            // ->where("p.campus", session('campus'))
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
            // ->where("p.campus", session('campus'))
            ->where("pt.project_category", "=", $request->category)
            ->where("pt.project_year","=",$request->year)
            ->where(function ($query) {
                $query->where("pt.pres_status","=", 1)
                ->orWhere("pt.pres_status","=", 2)
                ->orWhere("pt.pres_status","=", 3);
              })
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
          }

      $signatures = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->get();
          // dd($signatures);
          

      $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",1)
          ->get();
          // dd($prepared_by);

      $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",3)
          ->get();
          // dd($recommending_approval);

      $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",2)
          ->get();
          // dd($approved_by);
          
        $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get(); 
        // dd($campusinfo);
        return view('pages.president.print', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by'));
    }catch (\Throwable $th) {
        throw $th;
    }
  }
}