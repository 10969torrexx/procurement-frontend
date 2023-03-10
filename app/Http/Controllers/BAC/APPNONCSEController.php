<?php

namespace App\Http\Controllers\BAC;

use App\Exports\APP_Non_CSEExport;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\GlobalDeclare;
use App\Http\Controllers\HistoryLogController;
use Pdf;
use Carbon\Carbon;
use Validator;

class APPNONCSEController extends Controller
{
  public function traditional_index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "APP NON-CSE"]
    ];
    $scope = "campus";
    $Project = DB::table("project_titles as pt")
        ->join("ppmps as p", "p.project_code", "=", "pt.id")
        ->whereNull("pt.deleted_at")
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.project_category", "=", 1)
        ->where("pt.campus", session('campus'))
        ->where("pt.status", "=", 4)
        ->groupBy("pt.project_year")
        ->get("pt.project_year");
          // dd($Project); 
       
      $value = [];
        foreach($Project as $Projects){
          // $val++;
          array_push($value, $Projects->project_year) ;
        } 
      $val = reset($value); 

    $Categories = DB::table("ppmps as p")
        ->join("project_titles as pt", "p.project_code", "=", "pt.id")
        ->whereNull("p.deleted_at")
        ->whereNull("pt.deleted_at")
        ->where("pt.project_year","=",$val)
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.project_category", "=", 1)
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
          ->where("p.app_type", 'Non-CSE')
          ->whereNull("p.deleted_at")
          ->where("pt.project_year","=",$val)
          ->where("pt.project_category", "=", 1)
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
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", 1)
          ->where("p.status", "=", 4)
          ->where("pt.project_year",$val)
          ->where("pt.campus", session('campus'))
          ->groupBy("pt.project_year")
          ->get("pt.project_year");

    $campusCheck = DB::table("project_titles as pt")
          ->select("pt.*","p.app_type")
          ->join("ppmps as p", "p.project_code", "=", "pt.id")
          ->whereNull("pt.deleted_at")
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", 1)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where("pt.project_year",$val)
          ->where("pt.campus", session('campus'))
          ->groupBy("pt.campus")
          ->get();
          
          // dd($campusCheck); 
          
    $signatures = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$val)
          ->where("project_category",1)
          ->get();

    $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("project_category",1)
          ->where("Role","=",1)
          ->get();

    $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("project_category",1)
          ->where("Role","=",3)
          ->get();

     $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("project_category",1)
          ->where("Role","=",2)
          ->get();

    $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get();

    $users = DB::table("users")
          ->select("name","id")
          ->where("campus",session('campus'))
          ->whereNull("username")
          ->get();
          
    return view('pages.bac.generate-app-non-cse.list', compact('Categories','scope','ppmps','signatures','campusinfo','Project','Project_title','prepared_by','recommending_approval','approved_by','users','campusCheck'),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]
    );
  }
  
  public function indicative_index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "APP NON-CSE"]
    ];
    $scope = "campus";
    $Project = DB::table("project_titles as pt")
        ->join("ppmps as p", "p.project_code", "=", "pt.id")
        ->whereNull("pt.deleted_at")
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.project_category", "=", 0)
        ->where("pt.campus", session('campus'))
        ->where("pt.status", "=", 4)
        ->groupBy("pt.project_year")
        ->get("pt.project_year");
          // dd($Project); 
       
      $value = [];
        foreach($Project as $Projects){
          // $val++;
          array_push($value, $Projects->project_year) ;
        } 
      $val = reset($value); 

    $Categories = DB::table("ppmps as p")
        ->join("project_titles as pt", "p.project_code", "=", "pt.id")
        ->whereNull("p.deleted_at")
        ->where("pt.project_year","=",$val)
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.project_category", "=", 0)
        // ->where("pt.project_category", "=", 0)
        ->where("p.status", "=", 4)
        ->where("pt.status", "=", 4)
        ->where("p.campus", session('campus'))
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
          ->where("p.app_type", 'Non-CSE')
          ->whereNull("p.deleted_at")
          ->where("pt.project_year","=",$val)
          ->where("pt.project_category", "=", 0)
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
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", 0)
          ->where("p.status", "=", 4)
          ->where("pt.project_year",$val)
          ->where("pt.campus", session('campus'))
          ->groupBy("pt.project_year")
          ->get("pt.project_year");

    $campusCheck = DB::table("project_titles as pt")
          ->select("pt.*","p.app_type")
          ->join("ppmps as p", "p.project_code", "=", "pt.id")
          ->whereNull("pt.deleted_at")
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", 0)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where("pt.project_year",$val)
          ->where("pt.campus", session('campus'))
          ->groupBy("pt.campus")
          ->get();
          
          // dd($campusCheck); 
          
    $signatures = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$val)
          ->where("project_category",0)
          ->get();

    $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",1)
          ->where("project_category",0)
          ->get();

    $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",3)
          ->where("project_category",0)
          ->get();

    $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",2)
          ->where("project_category",0)
          ->get();

    $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get();

    $users = DB::table("users")
          ->select("name","id")
          ->where("campus",session('campus'))
          // ->where("name",'!=',$signName)
          ->whereNull("username")
          ->get();
          
    return view('pages.bac.generate-app-non-cse.list', compact('Categories','scope','ppmps','signatures','campusinfo','Project','Project_title','prepared_by','recommending_approval','approved_by','users','campusCheck'),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]
    );
  }
  
  public function supplemental_index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "APP NON-CSE"]
    ];
    $scope = "campus";
    $Project = DB::table("project_titles as pt")
        ->join("ppmps as p", "p.project_code", "=", "pt.id")
        ->whereNull("pt.deleted_at")
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.project_category", "=", 2)
        ->where("pt.campus", session('campus'))
        ->where("pt.status", "=", 4)
        ->groupBy("pt.project_year")
        ->get("pt.project_year");
          // dd($Project); 
       
      $value = [];
        foreach($Project as $Projects){
          // $val++;
          array_push($value, $Projects->project_year) ;
        } 
      $val = reset($value); 

    $Categories = DB::table("ppmps as p")
        ->join("project_titles as pt", "p.project_code", "=", "pt.id")
        ->whereNull("p.deleted_at")
        ->where("pt.project_year","=",$val)
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.project_category", "=", 2)
        ->where("p.status", "=", 4)
        ->where("pt.status", "=", 4)
        ->where("p.campus", session('campus'))
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
          ->where("p.app_type", 'Non-CSE')
          ->whereNull("p.deleted_at")
          ->where("pt.project_year","=",$val)
          ->where("pt.project_category", "=", 2)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where("p.campus", session('campus'))
          ->orderBy("p.department_id", "ASC")
          ->orderBy("p.project_code", "ASC")
          ->get();

    $Project_title = DB::table("project_titles as pt")
          ->join("ppmps as p", "p.project_code", "=", "pt.id")
          ->whereNull("pt.deleted_at")
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", 2)
          ->where("p.status", "=", 4)
          ->where("pt.project_year",$val)
          ->where("pt.campus", session('campus'))
          ->groupBy("pt.project_year")
          ->get("pt.project_year");

    $campusCheck = DB::table("project_titles as pt")
          ->select("pt.*","p.app_type")
          ->join("ppmps as p", "p.project_code", "=", "pt.id")
          ->whereNull("pt.deleted_at")
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", 2)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where("pt.project_year",$val)
          ->where("pt.campus", session('campus'))
          ->groupBy("pt.campus")
          ->get();
          
          // dd($campusCheck); 
          
    $signatures = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$val)
          ->where("project_category",2)
          ->get();

    $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("project_category",2)
          ->where("Role","=",1)
          ->get();

    $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("project_category",2)
          ->where("Role","=",3)
          ->get();

     $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("project_category",2)
          ->where("Role","=",2)
          ->get();

    $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get();

    $users = DB::table("users")
          ->select("name","id")
          ->where("campus",session('campus'))
          ->whereNull("username")
          ->get();
          
    return view('pages.bac.generate-app-non-cse.list', compact('Categories','scope','ppmps','signatures','campusinfo','Project','Project_title','prepared_by','recommending_approval','approved_by','users','campusCheck'),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]
    );
  }

  public function university_wide(Request $request){
    // dd($request->all());    // $aes = new AESCipher();
    // $id = $aes->decrypt($request);
    try {
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["name" => "APP NON-CSE"]
      ];
      $scope = "Uwide";
      $Project = DB::table("project_titles as pt")
          ->join("ppmps as p", "p.project_code", "=", "pt.id")
          ->whereNull("pt.deleted_at")
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", $request->project_category)
          ->where("pt.status", "=", 4)
          ->where("pt.endorse", "=", 1)
          ->groupBy("pt.project_year")
          ->get("pt.project_year");
        
        $value = [];
          foreach($Project as $Projects){
            array_push($value, $Projects->project_year) ;
          } 
        $val = reset($value); 

      $Categories = DB::table("ppmps as p")
          ->join("project_titles as pt", "p.project_code", "=", "pt.id")
          ->whereNull("p.deleted_at")
          ->where("pt.project_year","=",$val)
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", $request->project_category)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where("pt.endorse", "=", 1)
          ->whereNull("p.deleted_at")
          ->whereNull("pt.deleted_at")
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
            ->where("p.app_type", 'Non-CSE')
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$val)
            ->where("pt.project_category", "=", $request->project_category)
            ->where("p.status", "=", 4)
            ->where("pt.endorse", "=", 1)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
          // dd($ppmps);
            
      $campusCheck = DB::table("project_titles as pt")
            ->select("pt.*","p.app_type")
            ->join("ppmps as p", "p.project_code", "=", "pt.id")
            ->whereNull("pt.deleted_at")
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $request->project_category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where("pt.endorse", "=", 1)
            ->where("pt.project_year",$val)
            ->groupBy("pt.campus")
            ->get();

            // dd($campusCheck );

      $Project_title = DB::table("project_titles as pt")
            ->join("ppmps as p", "p.project_code", "=", "pt.id")
            ->whereNull("pt.deleted_at")
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $request->project_category)
            ->where("p.status", "=", 4)
            ->where("pt.endorse", "=", 1)
            ->where("pt.project_year",$val)
            ->groupBy("pt.project_year")
            ->get("pt.project_year");
            
      $signatures = DB::table("signatories_app_non_cse")
            ->where("campus",session('campus'))
            ->where("project_category",$request->project_category)
            ->where("Year",$val)
            ->get();

      $prepared_by = DB::table("signatories_app_non_cse")
            ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
            ->where("campus",session('campus'))
            ->where("Year", $val)
            ->where("project_category",$request->project_category)
            ->where("Role","=",1)
            ->get();

      $recommending_approval = DB::table("signatories_app_non_cse")
            ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
            ->where("campus",session('campus'))
            ->where("Year", $val)
            ->where("project_category",$request->project_category)
            ->where("Role","=",3)
            ->get();

      $approved_by = DB::table("signatories_app_non_cse")
            ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
            ->where("campus",session('campus'))
            ->where("Year", $val)
            ->where("project_category",$request->project_category)
            ->where("Role","=",2)
            ->get();

      $campusinfo = DB::table("campusinfo")
            ->where("campus",session('campus'))
            ->get();

      $users = DB::table("users")
            ->select("name","id")
            ->where("campus",session('campus'))
            ->whereNull("username")
            ->get();

      return view('pages.bac.generate-app-non-cse.list', compact('Categories','scope','ppmps','signatures','campusinfo','Project','Project_title','prepared_by','recommending_approval','approved_by','users','campusCheck'/* ,'approvedBystat' */),
        [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs
        ]
      );
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function endorse_to_main(Request $request){
    // dd($request->all());
    $check = DB::table("signatories_app_non_cse")
          ->where("Year",$request->year_created)
          ->where("campus", session('campus'))
          ->where("project_category",$request->project_category)
          ->get();
          // dd( $check);

      if($check->isEmpty()|| count($check) < 10){
        return response()->json([
          'status' => 500, 
          ]); 
      }else{
        $done = DB::table("project_titles as pt")
            ->join("ppmps as p", "p.project_code", "=", "pt.id")
            ->whereNull("pt.deleted_at")
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $request->project_category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where("pt.campus", session('campus'))
            ->where("pt.project_year",$request->year_created)
            ->update([
              "pt.endorse" => $request->endorse
            ]);
            
            # this will created history_log
                  (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    NULL,
                    'Endorse APP NON-CSE , '.'Category: '.$request->project_category.', Year: '.$request->year_created,
                    'Endorse/Not'.$request->endorse,
                    $request->ip(),
                );
            # end
        if($done){
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

  }

  public function submit_to_president(Request $request){
    // dd($request->all());

      $check = DB::table("signatories_app_non_cse")
          ->where("Year",$request->year)
          ->where("campus", session('campus'))
          ->where("project_category",$request->category)
          ->get();
          // dd( $check);

      if($check->isEmpty()|| count($check) < 10){
        return response()->json([
          'status' => 500, 
          ]); 
      }else{
        if($request->scope == "campus"){

          $done = DB::table("project_titles as pt")
            ->join("ppmps as p", "p.project_code", "=", "pt.id")
            ->where("pt.project_category","=", $request->category)
            ->where("p.app_type","=", "Non-CSE")
            ->where("pt.status","=", 4)  
            ->where("pt.project_year","=", $request->year)
            ->whereNull("pt.deleted_at")
            ->whereNull("p.deleted_at")
            ->update([
              'pt.pres_status' => $request->submit,
            ]);
              
        }else{

         $done =  DB::table("project_titles as pt")
            ->join("ppmps as p", "p.project_code", "=", "pt.id")
            ->where("pt.project_category","=", $request->category)
            ->where("p.app_type","=", "Non-CSE")
            ->where("pt.status","=", 4)  
            ->where("pt.project_year","=", $request->year)
            ->whereNull("pt.deleted_at")
            ->whereNull("p.deleted_at")
            ->update([
              'pt.univ_wide_status' => $request->submit,
            ]);
              
        }
          # this will created history_log
                (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  NULL,
                  'Submit to Pres APP NON-CSE , '.'Category: '.$request->category.', Year: '.$request->year,
                  'Submit/Not:'.$request->submit,
                  $request->ip(),
              );
          # end

          if($done){
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
  }

  public function app_non_cse_year(Request $request){
    // dd($request->all());
    try {
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["name" => "APP NON-CSE"]
      ];
      
      if($request->scope == "Uwide"){
        $scope = "Uwide";
        $Project = DB::table("project_titles as pt")
            ->join("ppmps as p", "p.project_code", "=", "pt.id")
            ->whereNull("pt.deleted_at")
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $request->project_category)
            ->where("pt.endorse", "=", 1)
            ->where("pt.status", "=", 4)
            ->groupBy("pt.project_year")
            ->get("pt.project_year");

        $Categories = DB::table("ppmps as p")
          ->join("project_titles as pt", "p.project_code", "=", "pt.id")
          ->whereNull("p.deleted_at")
          ->where("pt.project_year","=",$request->year)
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", $request->project_category)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where("pt.endorse", "=", 1)
          ->whereNull("p.deleted_at")
          ->whereNull("pt.deleted_at")
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
              ->where("p.app_type", 'Non-CSE')
              ->whereNull("p.deleted_at")
              ->where("pt.project_year","=",$request->year)
              ->where("pt.project_category", "=", $request->project_category)
              ->where("p.status", "=", 4)
              ->where("pt.endorse", "=", 1)
              ->where("pt.status", "=", 4)
              ->orderBy("p.department_id", "ASC")
              ->orderBy("p.project_code", "ASC")
              ->get();
            // dd($ppmps);
              
        $campusCheck = DB::table("project_titles as pt")
              ->select("pt.*","p.app_type")
              ->join("ppmps as p", "p.project_code", "=", "pt.id")
              ->whereNull("pt.deleted_at")
              ->where("p.app_type", 'Non-CSE')
              ->where("pt.project_category", "=", $request->project_category)
              ->where("p.status", "=", 4)
              ->where("pt.status", "=", 4)
              ->where("pt.endorse", "=", 1)
              ->where("pt.project_year",$request->year)
              ->groupBy("pt.campus")
              ->get();

              // dd($campusCheck );

        $Project_title = DB::table("project_titles as pt")
              ->join("ppmps as p", "p.project_code", "=", "pt.id")
              ->whereNull("pt.deleted_at")
              ->where("p.app_type", 'Non-CSE')
              ->where("pt.project_category", "=", $request->project_category)
              ->where("p.status", "=", 4)
              ->where("pt.endorse", "=", 1)
              ->where("pt.project_year",$request->year)
              ->groupBy("pt.project_year")
              ->get("pt.project_year");
              
        $signatures = DB::table("signatories_app_non_cse")
              ->where("campus",session('campus'))
              ->where("project_category",$request->project_category)
              ->where("Year",$request->year)
              ->get();

        $prepared_by = DB::table("signatories_app_non_cse")
              ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
              ->where("campus",session('campus'))
              ->where("project_category",$request->project_category)
              ->where("Year", $request->year)
              ->where("Role","=",1)
              ->get();

        $recommending_approval = DB::table("signatories_app_non_cse")
              ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
              ->where("campus",session('campus'))
              ->where("project_category",$request->project_category)
              ->where("Year", $request->year)
              ->where("Role","=",3)
              ->get();

        $approved_by = DB::table("signatories_app_non_cse")
              ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
              ->where("campus",session('campus'))
              ->where("Year", $request->year)
              ->where("project_category",$request->project_category)
              ->where("Role","=",2)
              ->get();

        $campusinfo = DB::table("campusinfo")
              ->where("campus",session('campus'))
              ->get();

      }else{
        $scope = "campus";
        $Project = DB::table("project_titles as pt")
            ->join("ppmps as p", "p.project_code", "=", "pt.id")
            ->whereNull("pt.deleted_at")
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $request->project_category)
            ->where("pt.campus", session('campus'))
            ->where("pt.status", "=", 4)
            ->groupBy("pt.project_year")
            ->get("pt.project_year");
          // dd($Project); 
        $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->whereNull("pt.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $request->project_category)
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
              ->where("p.app_type", 'Non-CSE')
              ->whereNull("p.deleted_at")
              ->where("pt.project_year","=",$request->year)
              ->where("pt.project_category", "=", $request->project_category)
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
              ->where("p.app_type", 'Non-CSE')
              ->where("pt.project_category", "=", $request->project_category)
              ->where("p.status", "=", 4)
              ->where("pt.project_year",$request->year)
              ->where("pt.campus", session('campus'))
              ->groupBy("pt.project_year")
              ->get("pt.project_year");

        $campusCheck = DB::table("project_titles as pt")
              ->select("pt.*","p.app_type")
              ->join("ppmps as p", "p.project_code", "=", "pt.id")
              ->whereNull("pt.deleted_at")
              ->where("p.app_type", 'Non-CSE')
              ->where("pt.project_category", "=", $request->project_category)
              ->where("p.status", "=", 4)
              ->where("pt.status", "=", 4)
              ->where("pt.project_year",$request->year)
              ->where("pt.campus", session('campus'))
              ->groupBy("pt.campus")
              ->get();
              
              // dd($campusCheck); 
              
        $signatures = DB::table("signatories_app_non_cse")
              ->where("campus",session('campus'))
              ->where("project_category",$request->project_category)
              ->where("Year",$request->year)
              ->get();

        $prepared_by = DB::table("signatories_app_non_cse")
              ->where("campus",session('campus'))
              ->where("Year",/* date("Y") */ $request->year)
              ->where("project_category",$request->project_category)
              ->where("Role","=",1)
              ->get();

        $recommending_approval = DB::table("signatories_app_non_cse")
              ->where("campus",session('campus'))
              ->where("Year",/* date("Y") */ $request->year)
              ->where("project_category",$request->project_category)
              ->where("Role","=",3)
              ->get();

        $approved_by = DB::table("signatories_app_non_cse")
              ->where("campus",session('campus'))
              ->where("Year",/* date("Y") */ $request->year)
              ->where("project_category",$request->project_category)
              ->where("Role","=",2)
              ->get();

        $campusinfo = DB::table("campusinfo")
              ->where("campus",session('campus'))
              ->get();
      }

      $users = DB::table("users")
            ->select("name","id")
            ->where("campus",session('campus'))
            ->whereNull("username")
            ->get();

      return view('pages.bac.generate-app-non-cse.list', compact('Categories','scope','ppmps','signatures','campusinfo','Project','Project_title','prepared_by','recommending_approval','approved_by','users','campusCheck'),
        [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs
        ]
      );
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function generatepdf(Request $request){

    // dd($request->all());
    try{
        $count = $request->campusCheck;
        if($count  == 1){
          $scope = "campus";
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $request->category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where("p.campus", session('campus'))
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
            ->where("p.app_type", 'Non-CSE')
            ->whereNull("p.deleted_at")
            ->where("p.campus", session('campus'))
            ->where("pt.project_category", "=", $request->category)
            ->where("pt.project_year","=",$request->year)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();

        }else if($count  > 1){
          $scope = "Uwide";
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $request->category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where("pt.endorse", "=", 1)
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
            ->where("p.app_type", 'Non-CSE')
            ->whereNull("p.deleted_at")
            ->where("pt.project_category", "=", $request->category)
            ->where("pt.project_year","=",$request->year)
            ->where("p.status", "=", 4)
            ->where("pt.endorse", "=", 1)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
          }

      $signatures = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category",$request->category)
          ->where("Year",$request->year)
          ->get();
          

      $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category",$request->category)
          ->where("Year",$request->year)
          ->where("Role","=",1)
          ->get();

      $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category",$request->category)
          ->where("Year",$request->year)
          ->where("Role","=",3)
          ->get();

      $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category",$request->category)
          ->where("Year",$request->year)
          ->where("Role","=",2)
          ->get();
          
        $campusinfo = DB::table("campusinfo")
        ->where("campus",session('campus'))
        ->get();
        
          # this will created history_log
                (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  NULL,
                  'Generate PDF APP NON-CSE , '.'Category: '.$request->category.', Year: '.$request->year,
                  'Generate PDF',
                  $request->ip(),
              );
          # end

        $pdf = Pdf::loadView('pages.bac.generate-app-non-cse.generate-pdf', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by','scope'))->setPaper('legal', 'landscape');
        return $pdf->download(); 
        
        
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
        return response()->json([
            'status' => 600,
            'message'   => $th
        ]);
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

  public function generateexcel(){
    $reader = IOFactory::createReader('Xlsx');
    $spreadsheet = $reader->load('storage/PMIS/APPNONCSE/APP_NON-CSE(3).xlsx');
    
    // $sheet = $spreadsheet->getActiveSheet();
    $Campus = DB::table("ppmps")
    ->whereNull("deleted_at")
    ->where("app_type", 'Non-CSE')
    ->where("is_supplemental", 0)
    ->where("status", "=", 4)
    ->groupBy("campus")
    // ->groupBy("item_category")
    ->orderBy("campus","ASC")
    ->get(['campus']);

    $Categories = DB::table("ppmps")
    ->whereNull("deleted_at")
    ->where("app_type", 'Non-CSE')
    ->where("is_supplemental", 0)
    ->where("status", "=", 4)
    ->groupBy("campus")
    ->groupBy("item_category")
    ->orderBy("campus","ASC")
    ->get();

    // ($Categories);
    
    $ppmps = DB::table("ppmps as p")
      ->select("pt.project_title", "d.department_name", "p.*", "pt.fund_source","pt.project_code as ProjectCode")
      ->join("project_titles as pt", "p.project_code", "=", "pt.id")
      ->join("departments as d", "pt.department_id", "=", "d.id")
      ->where("p.app_type", 'Non-CSE')
      ->whereNull("p.deleted_at")
      ->where("p.is_supplemental", "=", 0)
      ->where("p.status", "=", 4)
      ->orderBy("p.department_id", "ASC")
      ->orderBy("p.project_code", "ASC")
      ->get();

    $signatures = DB::table("signatories_app_non_cse")
      ->where("Year",2022)
      ->where("campus", session('campus'))
      ->get();
      
    $campusinfo = DB::table("campusinfo")
    ->where("campus", 1)
    ->get();

    $spreadsheet->getDefaultStyle()
                ->getFont()
                ->setName('Arial Narrow')
                ->setSize(10);
    
    $contentStartRow = 10;
    $currentContentRow = 15;
    $oldCampus="";
    $num = 1;
    // dd($campusinfo);
    $spreadsheet->getActiveSheet()
            ->setCellValue('G4' , $campusinfo[0]->address)
            ->setCellValue('G6' , "Website: ".$campusinfo[0]->website)
            ->setCellValue('G7' , "Email: ".$campusinfo[0]->email)
            ->setCellValue('G8' , "Contact Number: ".$campusinfo[0]->contact_number);
    foreach($Campus as $category){
      if ($oldCampus != $category->campus){
        // dd($data);
        $spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow+1,1);

        $spreadsheet->getActiveSheet()
            ->setCellValue('B'.$currentContentRow , $category->campus);
        $currentContentRow++;
        foreach($Categories as $cat){
          if ($cat->campus == $category->campus){
            $spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow+1,1);
            $spreadsheet->getActiveSheet()
            // ->setCellValue('A'.$currentContentRow , $num++)
            ->setCellValue('C'.$currentContentRow , $cat->item_category);
            $oldProject="";
            $currentContentRow++;
            foreach($ppmps as $row){
              if ($row->item_category == $cat->item_category && $row->campus == $cat->campus){
                if ($oldProject != $row->project_code){
                      $total = 0;
                      $totalMOOE = 0;
                      $totalCO = 0;
                      foreach($ppmps as $project){
                          if ($project->item_category == $cat->item_category && $project->campus == $cat->campus && $project->project_code == $row->project_code){
                            $total += $project->estimated_price;
                            if ($project->unit_price <= 50000){
                              $totalMOOE += $project->estimated_price;
                            }else{
                              $totalCO += $project->estimated_price;
                            }
                          }
                      }
                  $spreadsheet->getActiveSheet()->insertNewRowBefore($currentContentRow+1,1);
                  $spreadsheet->getActiveSheet()
                  // ->setCellValue('A'.$currentContentRow , $num++)
                  ->setCellValue('B'.$currentContentRow , $row->ProjectCode)
                  ->setCellValue('C'.$currentContentRow , $row->project_title)
                  ->setCellValue('D'.$currentContentRow ,  $row->department_name)
                  ->setCellValue('E'.$currentContentRow , "No")
                  ->setCellValue('F'.$currentContentRow , $row->mode_of_procurement)
                  ->setCellValue('G'.$currentContentRow , "1st to 4th Quarter")
                  ->setCellValue('H'.$currentContentRow , "1st to 4th Quarter")
                  ->setCellValue('I'.$currentContentRow , "1st to 4th Quarter")
                  ->setCellValue('J'.$currentContentRow , "1st to 4th Quarter")
                  ->setCellValue('K'.$currentContentRow , $row->fund_source)
                  ->setCellValue('L'.$currentContentRow , $total)
                  ->setCellValue('L'.$currentContentRow , $totalMOOE)
                  ->setCellValue('L'.$currentContentRow , $totalCO);
                  $currentContentRow++;
                }
              }
            }// 
            // $currentContentRow++;
          }
        }
      }
    } 
    $preparedby1 = $currentContentRow+6;
    $preparedby2 = $currentContentRow+7;
    $recommendingapproval1 = $currentContentRow+4;
    $recommendingapprovalprof1 = $currentContentRow+5;
    $recommendingapproval2 = $currentContentRow+7;
    $recommendingapprovalprof2 = $currentContentRow+8;
    $recommendingapproval3 = $currentContentRow+10;
    $recommendingapprovalprof3 = $currentContentRow+11;
    $approvedby1 = $currentContentRow+6;
    $approvedby2 = $currentContentRow+7;
    $name = "";
    $title = "";
    $profession = "";
    foreach($signatures as $signatures){
      if($signatures->Role == 1){
        // $name = $signatures->Name;
        // $profession = $signatures->Profession;
        if($signatures->Title == 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('B'.$preparedby1 , $signatures->Name)
                  ->setCellValue('B'.$preparedby2 , $signatures->Profession);
        }else if($signatures->Title != 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('B'.$preparedby1 , $signatures->Name. ", ". $signatures->Title)
                  ->setCellValue('B'.$preparedby2 , $signatures->Profession);
        }
      }else if($signatures->Role == 3 && $signatures->Position == 31){
        // $name = $signatures->Name;
        // $profession = $signatures->Profession;
        if($signatures->Title == 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('D'.$recommendingapproval1 , $signatures->Name)
                  ->setCellValue('D'.$recommendingapprovalprof1 , $signatures->Profession);
        }else if($signatures->Title != 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('D'.$recommendingapproval1 , $signatures->Name. ", ". $signatures->Title)
                  ->setCellValue('D'.$recommendingapprovalprof1 , $signatures->Profession);
        }
      }else if($signatures->Role == 3 && $signatures->Position == 32){
        // $name = $signatures->Name;
        // $profession = $signatures->Profession;
        if($signatures->Title == 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('G'.$recommendingapproval1 , $signatures->Name)
                  ->setCellValue('G'.$recommendingapprovalprof1 , $signatures->Profession);
        }else if($signatures->Title != 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('G'.$recommendingapproval1 , $signatures->Name. ", ". $signatures->Title)
                  ->setCellValue('G'.$recommendingapprovalprof1 , $signatures->Profession);
        }
      }else if($signatures->Role == 3 && $signatures->Position == 33){
        // $name = $signatures->Name;
        // $profession = $signatures->Profession;
        if($signatures->Title == 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('J'.$recommendingapproval1 , $signatures->Name)
                  ->setCellValue('J'.$recommendingapprovalprof1 , $signatures->Profession);
        }else if($signatures->Title != 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('J'.$recommendingapproval1 , $signatures->Name. ", ". $signatures->Title)
                  ->setCellValue('J'.$recommendingapprovalprof1 , $signatures->Profession);
        }
      }else if($signatures->Role == 3 && $signatures->Position == 34){
        // $name = $signatures->Name;
        // $profession = $signatures->Profession;
        if($signatures->Title == 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('D'.$recommendingapproval2 , $signatures->Name)
                  ->setCellValue('D'.$recommendingapprovalprof2 , $signatures->Profession);
        }else if($signatures->Title != 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('D'.$recommendingapproval2 , $signatures->Name. ", ". $signatures->Title)
                  ->setCellValue('D'.$recommendingapprovalprof2 , $signatures->Profession);
        }
      }else if($signatures->Role == 3 && $signatures->Position == 35){
        // $name = $signatures->Name;
        // $profession = $signatures->Profession;
        if($signatures->Title == 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('G'.$recommendingapproval2 , $signatures->Name)
                  ->setCellValue('G'.$recommendingapprovalprof2 , $signatures->Profession);
        }else if($signatures->Title != 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('G'.$recommendingapproval2 , $signatures->Name. ", ". $signatures->Title)
                  ->setCellValue('G'.$recommendingapprovalprof2 , $signatures->Profession);
        }
      }else if($signatures->Role == 3 && $signatures->Position == 36){
        // $name = $signatures->Name;
        // $profession = $signatures->Profession;
        if($signatures->Title == 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('J'.$recommendingapproval2 , $signatures->Name)
                  ->setCellValue('J'.$recommendingapprovalprof2 , $signatures->Profession);
        }else if($signatures->Title != 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('J'.$recommendingapproval2 , $signatures->Name. ", ". $signatures->Title)
                  ->setCellValue('J'.$recommendingapprovalprof2 , $signatures->Profession);
        }
      }else if($signatures->Role == 3 && $signatures->Position == 37){
        // $name = $signatures->Name;
        // $profession = $signatures->Profession;
        if($signatures->Title == 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('E'.$recommendingapproval3 , $signatures->Name)
                  ->setCellValue('E'.$recommendingapprovalprof3 , $signatures->Profession);
        }else if($signatures->Title != 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('E'.$recommendingapproval3 , $signatures->Name. ", ". $signatures->Title)
                  ->setCellValue('E'.$recommendingapprovalprof3 , $signatures->Profession);
        }
      }else if($signatures->Role == 3 && $signatures->Position == 38){
        // $name = $signatures->Name;
        // $profession = $signatures->Profession;
        if($signatures->Title == 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('H'.$recommendingapproval3 , $signatures->Name)
                  ->setCellValue('H'.$recommendingapprovalprof3 , $signatures->Profession);
        }else if($signatures->Title != 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('H'.$recommendingapproval3 , $signatures->Name. ", ". $signatures->Title)
                  ->setCellValue('H'.$recommendingapprovalprof3 , $signatures->Profession);
        }
      }else if($signatures->Role == 2){
        // $name = $signatures->Name;
        // $profession = $signatures->Profession;
        if($signatures->Title == 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('L'.$approvedby1 , $signatures->Name)
                  ->setCellValue('L'.$approvedby2 , $signatures->Profession);
        }else if($signatures->Title != 0){
          $spreadsheet->getActiveSheet()
                  ->setCellValue('L'.$approvedby1 , $signatures->Name. ", ". $signatures->Title)
                  ->setCellValue('L'.$approvedby2 , $signatures->Profession);
        }
      }
    }
    // $spreadsheet->getActiveSheet()
    //         ->setCellValue('B'.$line1 , $name." ". $title)
    //         ->setCellValue('B'.$line2 , $profession);
    // dd($data);
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="APP_NON-CSE.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
  }
  public function try(){
      $Categories = DB::table("ppmps")
      ->whereNull("deleted_at")
      ->where("app_type", 'Non-CSE')
      ->where("is_supplemental", 0)
      ->where("status", "=", 4)
      ->groupBy("campus")
      ->groupBy("item_category")
      ->orderBy("campus","ASC")
      ->get();
    
      $ppmps = DB::table("ppmps as p")
      ->select("pt.project_title", "d.department_name", "p.*", "pt.fund_source","pt.project_code as ProjectCode")
      ->join("project_titles as pt", "p.project_code", "=", "pt.id")
      ->join("departments as d", "pt.department_id", "=", "d.id")
      ->where("p.app_type", 'Non-CSE')
      ->whereNull("p.deleted_at")
      ->where("p.is_supplemental", "=", 0)
      ->where("p.status", "=", 4)
      ->orderBy("p.department_id", "ASC")
      ->orderBy("p.project_code", "ASC")
      ->get();

      $signatures = DB::table("signatories_app_non_cse")
      ->where("campus",1)
      ->get();

      
          

      $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",2022)
          ->where("Role","=",1)
          ->get();

      $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",2022)
          ->where("Role","=",3)
          ->get();

      $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",2022)
          ->where("Role","=",2)
          ->get();

    $ppmpss = DB::table("ppmps as p")
      ->select("pt.project_title", "d.department_name", "p.*","fs.fund_source","pt.project_code as ProjectCode")
      ->join("project_titles as pt", "p.project_code", "=", "pt.id")
      ->join("fund_sources as fs", "pt.fund_source", "=", "fs.id")
      ->join("departments as d", "pt.department_id", "=", "d.id")
      ->where("p.project_code", 33)
      ->where("p.for_PR", 2)
      // ->where("p.app_type", 'Non-CSE')
      ->where("p.mode_of_procurement", "!=", "Public Bidding")
      ->whereNull("p.deleted_at")
      ->where("p.is_supplemental", "=", 0)
      ->where("p.status", "=", 4)
      ->orderBy("p.department_id", "ASC")
      ->orderBy("p.project_code", "ASC")
      ->get();

      $campusinfo = DB::table("campusinfo")
      ->where("campus", 1)
      ->get();
        // dd($ppmpss);
    return view('pages.bac.generate-app-non-cse.try'/* ,compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by') */,compact('ppmpss','campusinfo'));
  }

  public function add_preparedby(Request $request){
    // dd($request->all());
    try{
      $aes = new AESCipher();
      $id = $aes->decrypt($request->id);
      $Signatories = DB::table("signatories_app_non_cse")
        // ->where('id',$id)
        ->insert([
          'users_id'=>$request->users_id,
          'Name'=>$request->Name,
          'Profession'=>$request->Profession,
          'Title'=>$request->Title,
          'Year'=>$request->Year,
          'project_category'=>$request->project_category,
          'Role'=>1,
          'Campus'=>session('campus'),
          'created_at'=> Carbon::now(),
        ]);
        
        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'Added Signatories for Prepared By, Year: '.$request->Year,
                'ADD',
                $request->ip(),
            );
        # end

      if($Signatories) {
        return response()->json([
        'status' =>  200,
        'data'  =>  $Signatories,
        ]);
      }
      else{
        return response()->json([
          'status' =>  400,
          'data'  =>  "error",
          ]);
      }
    }catch (\Throwable $th) {
        return response()->json([
            'status' => 400,
            'message'   => $th
        ]);
    }
  }

  public function add_approvedby(Request $request){
    // dd($request->all());
    try{
      $aes = new AESCipher();
      $id = $aes->decrypt($request->id);
      $Signatories = DB::table("signatories_app_non_cse")
        // ->where('id',$id)
        ->insert([
          'users_id'=>$request->users_id,
          'Name'=>$request->Name,
          'Profession'=>$request->Profession,
          'Title'=>$request->Title,
          'Year'=>$request->Year,
          'project_category'=>$request->project_category,
          'Role'=>2,
          'Campus'=>session('campus'),
          'created_at'=> Carbon::now(),
        ]);
        
        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'Added Signatories for Approved By, Year: '.$request->Year,
                'ADD',
                $request->ip(),
            );
        # end

      if($Signatories) {
        return response()->json([
        'status' =>  200,
        'data'  =>  $Signatories,
        ]);
      }
      else{
        return response()->json([
          'status' =>  400,
          'data'  =>  "error",
          ]);
      }
    }catch (\Throwable $th) {
        return response()->json([
            'status' => 400,
            'message'   => $th
        ]);
    }
  }

  public function add_recommendingapproval(Request $request){
    // dd($request->all());
    try{
      $aes = new AESCipher();
      $id = $aes->decrypt($request->id);
      $Signatories = DB::table("signatories_app_non_cse")
        // ->where('id',$id)
        ->insert([
          'users_id'=>$request->users_id,
          'Name'=>$request->Name,
          'Profession'=>$request->Profession,
          'Title'=>$request->Title,
          'Year'=>$request->Year,
          'Position'=>$request->Position,
          'project_category'=>$request->project_category,
          'Role'=>3,
          'Campus'=>session('campus'),
          'created_at'=> Carbon::now(),
        ]);
        
        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'Added Signatories for Recommending Approval, Year: '.$request->Year,
                'ADD',
                $request->ip(),
            );
        # end

      if($Signatories) {
        return response()->json([
        'status' =>  200,
        'data'  =>  $Signatories,
        ]);
      }
      else{
        return response()->json([
          'status' =>  400,
          'data'  =>  "error",
          ]);
      }
    }catch (\Throwable $th) {
        return response()->json([
            'status' => 400,
            'message'   => $th
        ]);
    }
  }

  public function add_recommendingapproval_modal(Request $request){
    // dd($request->all());
    try{
      $users = DB::table("users as u")
          ->where("u.campus",session('campus'))
          ->whereNotIn('u.id',DB::table("signatories_app_non_cse as s")
                ->where("s.Year",'=',$request->year)
                ->where("s.campus",session('campus'))
                ->pluck('s.users_id'))
          ->whereNull("u.username")
          ->get();
          // dd($users);
      

      if($users) {
        return response()->json([
        'status' =>  200,
        // 'data'  =>  $Signatories,
        'users'  =>  $users,
        ]);
      }
      else{
        return response()->json([
          'status' =>  400,
          'data'  =>  "error",
          ]);
      }
    }catch (\Throwable $th) {
        return response()->json([
            'status' => 400,
            'message'   => $th
        ]);
    }
  }

  public function show_signatories(Request $request){
    // dd($request->all());
    try{
      $aes = new AESCipher();
      $id = $aes->decrypt($request->id);
      $Signatories = DB::table("signatories_app_non_cse")
        ->where('id',$id)
        ->get();

      if($Signatories) {
        return response()->json([
        'status' =>  200,
        'data'  =>  $Signatories,
        ]);
      }
      else{
        return response()->json([
          'status' =>  400,
          'data'  =>  "error",
          ]);
      }
    }catch (\Throwable $th) {
        return response()->json([
            'status' => 400,
            'message'   => $th
        ]);
    }
  }

  public function update_signatories(Request $request){
    // dd($request->all());
    try{
      $aes = new AESCipher();
      // $id = $aes->decrypt($request->id);
      $Signatories = DB::table("signatories_app_non_cse")
        ->where('id',$request->id)
        ->update([
          'users_id'    => $request->users_id,
          'Name'        =>$request->Name,
          'Profession'  =>$request->Profession,
          'Title'       =>$request->Title,
          'project_category'=>$request->project_category,
          'updated_at'=> Carbon::now(),
        ]);
        
        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                $request->id,
                'Update Signatories',
                'Update',
                $request->ip(),
            );
        # end

      if($Signatories) {
        return response()->json([
        'status' =>  200,
        'data'  =>  "Updated Succesfully!!",
        ]);
      }
      else{
        return response()->json([
          'status' =>  400,
          'data'  =>  "error",
          ]);
      }
    }catch (\Throwable $th) {
        return response()->json([
            'status' => 400,
            'message'   => $th
        ]);
    }
  }
  
  public function show_campusinfo(Request $request){
    // dd($request->all());
    try{
      $aes = new AESCipher();
      $id = $aes->decrypt($request->id);
      $campusinfo = DB::table("campusinfo")
        ->where('id',$id)
        ->get();

      if($campusinfo) {
        return response()->json([
        'status' =>  200,
        'data'  =>  $campusinfo,
        ]);
      }
      else{
        return response()->json([
          'status' =>  400,
          'data'  =>  "error",
          ]);
      }
    }catch (\Throwable $th) {
        return response()->json([
            'status' => 400,
            'message'   => $th
        ]);
    }
  }

  public function update_campusinfo(Request $request){
    // dd($request->all());
    try{
      $aes = new AESCipher();
      // $id = $aes->decrypt($request->id);
      $campusinfo = DB::table("campusinfo")
        ->where('id',$request->id)
        ->update([
          'address'=>$request->Address,
          'website' =>$request->Website,
          'email' =>$request->Email,
          'contact_number' =>$request->Contact,
        ]);
        
        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                $request->id,
                'Update Campus Info',
                'Update',
                $request->ip(),
            );
        # end

      if($campusinfo) {
        return response()->json([
        'status' =>  200,
        'data'  =>  "Updated Succesfully!!",
        ]);
      }
      else{
        return response()->json([
          'status' =>  400,
          'data'  =>  "error",
          ]);
      }
    }catch (\Throwable $th) {
        return response()->json([
            'status' => 400,
            'message'   => $th
        ]);
    }
  }

  public function update_campuslogo(Request $request){
    // dd($request->all());
    try{
      $aes = new AESCipher();
      $id = $aes->decrypt($request->campuslogoid);

      $path = 'images/logo/';
          if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
          }
            $file = $request->file('logo');
            $extension = $file->getClientOriginalName();
            // $filename = time() . '.' . $extension;
            $file->move($path, $extension);
            $filepath = $path.'/'.$extension;

      // $path = env('APP_NAME').'/pds/upload/' ;
      // $destination_path = 'PMIS/APPNONCSE/image/logo';
      // $file = $request->file('Logo');
      // $filename = $file->getClientOriginalName();
      // $path = $request->file('logo')->storeAs($destination_path,$file);

      // $logo = $filename ;
      // dd($filename);
      // // $extension = $file->getClientOriginalExtension();
      // // $filename = time() . '.' . $extension;
      // // $file->move('storage/'.$path, $filename);
      // // $filepath = 'storage/'.$path.'/'.$filename;

      $campusinfo = DB::table("campusinfo")
        ->where('id',$id)
        ->update([
          'slsu_logo'=>$extension,
        ]);
        
        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                $id,
                'Update Campus Logo',
                'Update',
                $request->ip(),
            );
        # end
      // dd($campusinfo);
      if($campusinfo) {
        return response()->json([
        'status' =>  200,
        'data'  =>  "Updated Succesfully!!",
        ]);
      }
      else{
        return response()->json([
          'status' =>  400,
          'data'  =>  "error",
          ]);
      }
    }catch (\Throwable $th) {
        return response()->json([
            'status' => 600,
            'message'   => $th
        ]);
    }
  }

  public function update_logo(Request $request){
    // dd($request->all());
    try{
      $aes = new AESCipher();
      $id = $aes->decrypt($request->campuslogoid);

      $path = 'images/logo';
      if (!Storage::exists($path)) {
        Storage::makeDirectory($path);
      }
        $file = $request->file('logo');
        $extension = $file->getClientOriginalName();
        // $filename = time() . '.' . $extension;
        $file->move($path, $extension);
        $filepath = $path.'/'.$extension;

     /*  $path = env('APP_NAME').'/APPNONCSE/image/logo';
          if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
          }
            $file = $request->file('logo');
            $extension = $file->getClientOriginalName();
            // $filename = time() . '.' . $extension;
            $file->move('storage/'.$path, $extension);
            $filepath = 'storage/'.$path.'/'.$extension; */

      // $path = env('APP_NAME').'/pds/upload/' ;
      // $destination_path = 'PMIS/APPNONCSE/image/logo';
      // $file = $request->file('Logo');
      // $filename = $file->getClientOriginalName();
      // $path = $request->file('logo')->storeAs($destination_path,$file);

      // $logo = $filename ;
      // dd($filename);
      // // $extension = $file->getClientOriginalExtension();
      // // $filename = time() . '.' . $extension;
      // // $file->move('storage/'.$path, $filename);
      // // $filepath = 'storage/'.$path.'/'.$filename;

      $campusinfo = DB::table("campusinfo")
        ->where('id',$id)
        ->update([
          'logo2'=>$extension,
        ]);
        
        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                $id,
                'Update Other Logo',
                'Update',
                $request->ip(),
            );
        # end
        // dd($campusinfo);

      if($campusinfo) {
        return response()->json([
        'status' =>  200,
        'data'  =>  "Updated Succesfully!!",
        ]);
      }
      else{
        return response()->json([
          'status' =>  400,
          'data'  =>  "error",
          ]);
      }
    }catch (\Throwable $th) {
        return response()->json([
            'status' => 600,
            'message'   => $th
        ]);
    }
  } 
  
  public function print(Request $request){
    // dd($request->all());
    try{
        $count = $request->campusCheck;
        if($count  == 1){
          $scope = "campus";
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

        }else if($count  > 1){
          $scope = "Uwide";
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type","=",$request->app_type)
            ->where("pt.endorse", "=", 1)
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
            ->where("pt.endorse", "=", 1)
            ->where("p.app_type","=",$request->app_type)
            ->whereNull("p.deleted_at")
            // ->where("p.campus", session('campus'))
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
          ->where("project_category",$request->category)
          ->where("Year",$request->year)
          ->get();
          // dd($signatures);
          

      $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category",$request->category)
          ->where("Year",$request->year)
          ->where("Role","=",1)
          ->get();
          // dd($prepared_by);

      $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category",$request->category)
          ->where("Year",$request->year)
          ->where("Role","=",3)
          ->get();
          // dd($recommending_approval);

      $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("project_category",$request->category)
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
                Null,
                'Print APP NON-CSE',
                'Print',
                $request->ip(),
            );
        # end
        return view('pages.bac.generate-app-non-cse.print', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by','scope'));
    }catch (\Throwable $th) {
        throw $th;
    }
  }

  public function endorsed_app_non_cse_index(Request $request){
    // dd($request->all());
    try {
      $campus = 3;
      $project_category = $request->project_category;
      $scope = $request->scope;
      $year = $request->year;
      $response = DB::table('signed_app')
                    ->where('type',3)
                    ->whereNull('deleted_at')
                    // ->orderBy('year_created','DESC')
                    ->get();
                  // dd($response );

      return view('pages.bac.generate-app-non-cse.upload-signed-app', compact('response','campus','project_category','scope','year'));
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function signed_app_non_cse_index(Request $request){
    try {
      $campus = 1;
      $project_category = "";
      $scope = "";
      $year = "";
      $response = DB::table('signed_app')
                    ->where('type',2)
                    ->where('campus',session('campus'))
                    ->whereNull('deleted_at')
                    // ->orderBy('year_created','DESC')
                    ->get();
                  // dd($response );

      return view('pages.bac.generate-app-non-cse.upload-signed-app', compact('response','campus','project_category','scope','year'));
    } catch (\Throwable $th) {
      throw $th;
    }
  }
  
  public function signed_app_non_cse_index_univ_wide(Request $request){

    try {
      $campus = 0;
      $project_category = "";
      $scope = "";
      $year = "";
      $response = DB::table('signed_app')
                    ->whereNull('deleted_at')
                    ->where('type',1)
                    ->where('campus',session('campus'))
                    // ->orderBy('year_created','DESC')
                    ->get();

      return view('pages.bac.generate-app-non-cse.upload-signed-app', compact('response','campus','project_category','scope','year'));
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /* Upload Signed app non-cse
  * - Creating files for Signed app non-cse 
  * - Downloadable app
  */
  public function upload_app(Request $request) {
    // dd($request->all());
      try {
          $validate = Validator::make($request->all(), [
              'project_category'  => ['required'],
              'year_created'  =>  ['required'],
              'file_name' => ['required'],
              'type' => ['required'],
              // 'signed_app'   => ['required', 'mimes:pdf, jpeg, jpg, png', 'max:2048']
          ]);
          if($request->hasFile('signed_app')) {
              $file = $request->file('signed_app');
              $extension = $request->file('signed_app')->getClientOriginalExtension();
            
              $is_valid = false;
              # validate extension
                  $allowed_extensions = ['pdf', 'jpeg', 'jpg', 'png'];
                  for ($i = 0; $i < count($allowed_extensions) ; $i++) { 
                    if($allowed_extensions[$i] == $extension) {
                          $is_valid = true;
                    }
                  }
                  if($is_valid == false) {
                      return back()->with([
                          'error' => 'Invalid file format!'
                      ]);
                  }
              # end
              # moving of the actual file
                  $file_name = $request->file_name.'-'. time();
                  $destination_path = env('APP_NAME').'\\bac_sec_upload\\signed_app\\';
                  if (!\Storage::exists($destination_path)) {
                      \Storage::makeDirectory($destination_path);
                  }
                  $file->storeAs($destination_path, $file_name.'.'.$extension);
                  $file->move('storage/'. $destination_path, $file_name.'.'.$extension);
              # end
              # storing data to signed_app table
                  \DB::table('signed_app')
                  ->insert([
                      'employee_id'   => session('employee_id'),
                      'department_id'   => session('department_id'),
                      'campus'   => session('campus'),
                      'year_created'   => (new AESCipher)->decrypt($request->year_created),
                      'project_category'  => (new AESCipher)->decrypt($request->project_category),
                      'file_name'   => $request->file_name,
                      'type'   => $request->type,
                      'file_directory'    => $destination_path .''. $file_name.'.'.$extension,
                      'signed_app' =>  $file_name.'.'.$extension,
                      'created_at'    => Carbon::now(),
                      'updated_at'    => Carbon::now()
                  ]);
              # this will created history_log
                  (new HistoryLogController)->store(
                      session('department_id'),
                      session('employee_id'),
                      session('campus'),
                      null,
                      'Uploaded signed app non-cse',
                      'upload',
                      $request->ip(),
                  );
              # end
              return back()->with([
                  'success' => 'APP uploaded successfully!'
              ]);
          } else {
              return back()->with([
                  'error' => 'Please fill the form accordingly!'
              ]);
          }
      } catch (\Throwable $th) {
          throw $th;
          return view('pages.error-500');
      }
  }

  /* Download Uplooded app
  * - this will enable downlaod uploade app
  * - based on: Employee id, campus, department_id
  * - get file from storage upload id search
  */
  public function download_uploaded_app(Request $request) {
      try {
          $response = \DB::table('signed_app')
          ->where('employee_id', session('employee_id'))
          ->where('department_id', session('department_id'))
          ->where('campus', session('campus'))
          ->where('id', (new AESCipher)->decrypt($request->id))
          ->whereNull('deleted_at')
          ->get([
              'signed_app'
          ]);
          # this will created history_log
              (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  null,
                  'Downloaded signed app non-cse',
                  'Download',
                  $request->ip(),
              );
          # end
          return \Storage::download(env('APP_NAME').'\\bac_sec_upload\\signed_app\\'.$response[0]->signed_app);
      } catch (\Throwable $th) {
          throw $th;
          return view('pages.error-500');
      }
  }

  /* Delete Uploaded Signed app non-cse
  * - this will delete the uploaded app
  * - based on: Employee id, campus, department_id
  */
  public function delete_uploaded_app(Request $request) {
      try {
          $response = \DB::table('signed_app')
          ->where('employee_id', session('employee_id'))
          ->where('department_id', session('department_id'))
          ->where('campus', session('campus'))
          ->where('id', (new AESCipher)->decrypt($request->id))
          ->whereNull('deleted_at')
          ->update([
              'updated_at'    => Carbon::now(),
              'deleted_at'    => Carbon::now()
          ]);
          # this will created history_log
              (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  (new AESCipher)->decrypt($request->id),
                  'Deleted signed app non-cse',
                  'Delete',
                  $request->ip(),
              );
          # end
          return back()->with([
              'success'   => 'Uploaded app successfully deleted!'
          ]);
      } catch (\Throwable $th) {
          return view('pages.error-500');
          // throw $th;
      }
  }

  /* View uploaded app
  * - this will allow preview of the uploaded app
  */
  public function view_uploaded_app(Request $request) {
    // dd($request->all());
      try {
          $response = \DB::table('signed_app')
              ->where('employee_id', session('employee_id'))
              ->where('department_id', session('department_id'))
              // ->where('campus', session('campus'))
              ->where('id', (new AESCipher)->decrypt($request->id))
              ->whereNull('deleted_at')
              ->get([
                  'file_directory',
                  'signed_app'
              ]);
              dd($response);
          # this will created history_log
              (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  (new AESCipher)->decrypt($request->id),
                  'Viewed signed app non-cse',
                  'View',
                  $request->ip(),
              );
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

  /* GET Edit Uploaded app
  * - get uploaded app for edit feature
  */
  public function get_edit_app(Request $request) {
      try {
          $response = \DB::table('signed_app')
              ->where('employee_id', session('employee_id'))
              ->where('department_id', session('department_id'))
              ->where('campus', session('campus'))
              ->where('id', (new AESCipher)->decrypt($request->id))
              ->whereNull('deleted_at')
              ->get();

          return ([
              'status'    => 200,
              'data'  => $response,
          ]);
        
      } catch (\Throwable $th) {
          return view('pages.error-500');
          throw $th;
      }
  }

  /* GET Edit Uploaded app
  * - edit / update uploaded app
  * ! wadwadaw
  * ? awdawdaw
  */
  public function edit_uploaded_app(Request $request) {
      try {
        // dd($request->year_created);
          $validate = Validator::make($request->all(), [
              'project_category'  => ['required'],
              'year_created'  =>  ['required'],
              'file_name' => ['required'],
              'signed_app'   => ['required', 'mimes:pdf, jpeg, jpg, png', 'max:2048']
          ]);
          if($request->hasFile('signed_app')) {
              $file = $request->file('signed_app');
              $extension = $request->file('signed_app')->getClientOriginalExtension();
              $is_valid = false;
              # validate extension
                  $allowed_extensions = ['pdf', 'jpeg', 'jpg', 'png'];
                  for ($i = 0; $i < count($allowed_extensions) ; $i++) { 
                    if($allowed_extensions[$i] == $extension) {
                          $is_valid = true;
                    }
                  }
                  if($is_valid == false) {
                      return back()->with([
                          'error' => 'Invalid file format!'
                      ]);
                  }
              # end
              $file_name = (new GlobalDeclare)->project_category((new AESCipher)->decrypt($request->project_category)) .'-'. time();
              $destination_path = env('APP_NAME').'\\bac_sec_upload\\signed_app\\';
              if (!\Storage::exists($destination_path)) {
                  \Storage::makeDirectory($destination_path);
              }
              $file->storeAs($destination_path, $file_name.'.'.$extension);
              // \Storage::put($destination_path, $file_name.'.'.$extension);
              $file->move('storage/'. $destination_path, $file_name.'.'.$extension);
              # storing data to signed_app table
                  \DB::table('signed_app')
                  ->where('id', $request->id)
                  ->update([
                      'year_created'   => $request->year_created,
                      'project_category'  => (new AESCipher)->decrypt($request->project_category),
                      'file_name'   => $request->file_name,
                      'file_directory'    => $destination_path .''. $file_name.'.'.$extension,
                      'signed_app' =>  $file_name.'.'.$extension,
                      'updated_at'    => Carbon::now()
                  ]);
              # this will created history_log
                  (new HistoryLogController)->store(
                      session('department_id'),
                      session('employee_id'),
                      session('campus'),
                      null,
                      'Viewed signed app non-cse',
                      'View',
                      $request->ip(),
                  );
              # end
              return back()->with([
                  'success' => 'APP uploaded successfully!'
              ]);
          } else {
              return back()->with([
                  'error' => 'Please fill the form accordingly!'
              ]);
          }
      } catch (\Throwable $th) {
          // throw $th;
          return view('pages.error-500');
      }
  }

  # upload app | signed app non-cse
  public function get_upload_app(Request $request) {
      try {
          $this->validate($request, [
              'project_category'  => ['required'],
              'year_created'  =>  ['required'],
              'file_name' => ['required'],
          ]);
          # get uplpaded app
          $response = \DB::table('signed_app')
              ->where('employee_id', session('employee_id'))
              ->where('department_id', session('department_id'))
              ->where('campus', session('campus'))
              ->where('project_category', (new AESCipher)->decrypt($request->project_category))
              ->where('year_created', (new AESCipher)->decrypt($request->year_created))
              ->where('file_name', 'like', '%'. $request->file_name .'%')
              ->whereNull('deleted_at')
              ->get();
          # return page
          return view('pages.bac.generate-app-non-cse.upload-signed-app', compact('response'));
      } catch (\Throwable $th) {
          throw $th;
          return view('pages.error-500');
      }
  }
}