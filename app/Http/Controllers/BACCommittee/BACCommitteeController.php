<?php

namespace App\Http\Controllers\BACCommittee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use DB;
use Carbon\Carbon;

class BACCommitteeController extends Controller
{
  public function list($id){
    
    $aes = new GlobalDeclare();
    $project_category = $aes->project_category_num($id);
    // dd($project_category);
    $app = DB::table("project_titles")
              // ->select("project_year","id","bac_committee_status")
              ->whereNull("deleted_at")
              ->where("project_category","=", $project_category)
              ->where("status","=", 4)  
              // ->where("bac_committee_status","=", 1)
              ->where(function ($query) {
                  $query->where("bac_committee_status","=", 0)
                  ->orWhere("bac_committee_status","=", 1)
                  ->orWhere("bac_committee_status","=", 2);
                })
              ->groupBy("project_year")
              -> get();
    // dd(session('user_id'));
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" =>"APP NON CSE"]
    ];
    return view('pages.BACCommitte.list',compact('app'),
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

        if(session('role') != 13) {
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
                $query->where("pt.bac_committee_status","=", 0)
                ->orWhere("pt.bac_committee_status","=", 1)
                ->orWhere("pt.bac_committee_status","=", 2);
              })
            ->where("p.campus", session('campus'))
            ->groupBy("p.campus")
            ->groupBy("p.item_category")
            ->orderBy("p.campus","ASC")
            ->get();
        } else {
           /**
           * ! Torrexx Addtionals
           * ? TODO get all recommended app non cse
           * ? TODO check if role is 13
           * ? Key | bac commitee status is 1
           */
           $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->whereNull("pt.deleted_at")
            ->where('pt.bac_committee_status', 1) // ! torrexx - change
            ->where("pt.project_year","=",$year)
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where(function ($query) {
                $query->where("pt.bac_committee_status","=", 0)
                ->orWhere("pt.bac_committee_status","=", 1)
                ->orWhere("pt.bac_committee_status","=", 2);
              })
            ->where("p.campus", session('campus'))
            ->groupBy("p.campus")
            ->groupBy("p.item_category")
            ->orderBy("p.campus","ASC")
            ->get();
        }
       
        


      $Categories = DB::table("ppmps as p")
          ->join("project_titles as pt", "p.project_code", "=", "pt.id")
          ->whereNull("p.deleted_at")
          ->whereNull("pt.deleted_at")
          ->where("pt.project_year","=",$year)
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", $category)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          // ->where(function ($query) {
          //     $query->where("pt.bac_committee_status","=", 0)
          //     ->orWhere("pt.bac_committee_status","=", 1)
          //     ->orWhere("pt.bac_committee_status","=", 2);
          //   })
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

      $signatories = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$year)
          ->where("users_id",'=',session('user_id'))
          ->get();

      $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get();

      $campusCheck = DB::table("project_titles as pt")
          ->select("pt.campus","pt.endorse","pt.bac_committee_status","pt.project_category","pt.project_year","p.app_type")
          ->join("ppmps as p", "p.project_code", "=", "pt.id")
          ->whereNull("pt.deleted_at")
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", $category)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where("pt.project_year",$year)
          ->where("pt.campus", session('campus'))
          ->groupBy("pt.campus")
          ->get();

      // $ppmp =  Http::withToken(session('token'))->get(env('APP_API'). "/api/supervisor/index")->json();
      return view('pages.BACCommitte.app', compact('ppmps','signatories','campusinfo'/* ,'Project' */,'Categories','campusCheck','Project_title','prepared_by','recommending_approval','approved_by'),
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
              $query->where("pt.bac_committee_status","=", 0)
              ->orWhere("pt.bac_committee_status","=", 1)
              ->orWhere("pt.bac_committee_status","=", 2);
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
                $query->where("pt.bac_committee_status","=", 1)
                ->orWhere("pt.bac_committee_status","=", 2)
                ->orWhere("pt.bac_committee_status","=", 3);
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
                $query->where("pt.bac_committee_status","=", 1)
                ->orWhere("pt.bac_committee_status","=", 2)
                ->orWhere("pt.bac_committee_status","=", 3);
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
                $query->where("pt.bac_committee_status","=", 1)
                ->orWhere("pt.bac_committee_status","=", 2)
                ->orWhere("pt.bac_committee_status","=", 3);
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
        $pdf = \Pdf::loadView('pages.BACCommitte.generate-pdf', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by'))->setPaper('legal', 'landscape');
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

  public function bac_committee_decision(Request $request){
    // dd($request->all());
      try {
        // $Project_title = DB::table("project_titles as pt")
        //   ->join("ppmps as p", "p.project_code", "=", "pt.id")
        //   ->whereNull("pt.deleted_at")
        //   ->where("pt.project_year","=",$request->year)
        //   ->where("p.app_type","=",$request->app_type)
        //   ->where("pt.campus", session('campus'))
        //   ->where("pt.project_category", "=", $request->category)
        //   ->where("p.status", "=", 4)
        //   ->update([
        //     'pt.bac_committee_status' => $request->value
        //   ]);
          if ($request->value == 1) {
            $signatories = DB::table("signatories_app_non_cse")
              ->where("Year","=",$request->year)
              ->where("users_id",'=',session('user_id'))
              ->update([
                'status' => $request->value,
                'bac_committee_created_at' => Carbon::now()
              ]);
          }else{
            $signatories = DB::table("signatories_app_non_cse")
              ->where("Year","=",$request->year)
              ->where("users_id",'=',session('user_id'))
              ->update([
                'status' => $request->value,
                'bac_committee_updated_at' => Carbon::now()
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
      } catch (\Throwable $th) {
        throw $th;
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
              $query->where("pt.bac_committee_status","=", 0)
              ->orWhere("pt.bac_committee_status","=", 1)
              ->orWhere("pt.bac_committee_status","=", 2);
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
                $query->where("pt.bac_committee_status","=", 1)
                ->orWhere("pt.bac_committee_status","=", 2)
                ->orWhere("pt.bac_committee_status","=", 3);
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
                $query->where("pt.bac_committee_status","=", 1)
                ->orWhere("pt.bac_committee_status","=", 2)
                ->orWhere("pt.bac_committee_status","=", 3);
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
                $query->where("pt.bac_committee_status","=", 1)
                ->orWhere("pt.bac_committee_status","=", 2)
                ->orWhere("pt.bac_committee_status","=", 3);
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
        return view('pages.BACCommitte.print', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by'));
    }catch (\Throwable $th) {
        throw $th;
    }
  }
}