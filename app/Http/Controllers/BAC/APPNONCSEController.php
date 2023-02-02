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
use Pdf;
use Carbon\Carbon;
use App\Http\Controllers\HistoryLogController;

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
        ->where("pt.project_year","=",$val)
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.project_category", "=", 1)
        // ->where("pt.project_category", "=", 1)
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
          ->where("pt.project_category", "=", 1)
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
          ->where("pt.project_category", "=", 1)
          ->where("p.status", "=", 4)
          ->where("pt.project_year",$val)
          ->where("pt.campus", session('campus'))
          ->groupBy("pt.project_year")
          ->get("pt.project_year");

    $campusCheck = DB::table("project_titles as pt")
          ->select("pt.campus","pt.endorse","pt.project_category","p.app_type")
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
          ->get();

    $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",1)
          ->get();

    $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",3)
          ->get();

     $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",2)
          ->get();

    $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get();
        
    // ( new HistoryLogController)->store(session('department_id'), session('employee_id'), session('campus'), null, 'View Traditional Index', 'View', $request->ip() )
    // $signName ="";
    // foreach($signatures as $sign){
    //   $signName = $sign->Name;
    // }

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
          ->select("pt.campus","pt.endorse","pt.project_category")
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
          ->get();

    $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",1)
          ->get();

    $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",3)
          ->get();

     $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",2)
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
        // ->where("pt.project_category", "=", 2)
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
          ->select("pt.campus","pt.endorse","pt.project_category")
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
          ->get();

    $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",1)
          ->get();

    $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",3)
          ->get();

     $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",/* date("Y") */ $val)
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
          ->select("pt.campus","pt.endorse","pt.project_category","p.app_type")
          ->join("ppmps as p", "p.project_code", "=", "pt.id")
          ->whereNull("pt.deleted_at")
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", $request->project_category)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->where("pt.project_year",$val)
          ->where("pt.campus",session('campus'))
          ->groupBy("pt.campus")
          ->get();

          // dd($campusCheck );

    $Project_title = DB::table("project_titles as pt")
          ->join("ppmps as p", "p.project_code", "=", "pt.id")
          ->whereNull("pt.deleted_at")
          ->where("p.app_type", 'Non-CSE')
          ->where("pt.project_category", "=", $request->project_category)
          ->where("p.status", "=", 4)
          ->where("pt.project_year",$val)
          ->groupBy("pt.project_year")
          ->get("pt.project_year");
          
    $signatures = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$val)
          ->get();

    $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year", $val)
          ->where("Role","=",1)
          ->get();

    $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year", $val)
          ->where("Role","=",3)
          ->get();

     $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year", $val)
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

  public function endorse_to_main(Request $request){
    // dd($request->all());
    $check = DB::table("signatories_app_non_cse")
          ->where("Year",$request->year)
          ->where("campus", session('campus'))
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
            ->where("pt.project_category", "=", $request->category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where("pt.campus", session('campus'))
            ->where("pt.project_year",$request->year)
            ->update([
              "pt.endorse" => $request->endorse
            ]);
            
            # this will created history_log
                  (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    NULL,
                    'Endorse APP NON-CSE , '.'Category: '.$request->category.', Year: '.$request->year,
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
    dd($request->all());

      $check = DB::table("signatories_app_non_cse")
          ->where("Year",$request->year)
          ->where("campus", session('campus'))
          ->get();
          // dd( $check);

      if($check->isEmpty()|| count($check) < 10){
        return response()->json([
          'status' => 500, 
          ]); 
      }else{
        // $done = DB::table("project_titles as pt")
        //   ->join("ppmps as p", "p.project_code", "=", "pt.id")
        //   ->whereNull("pt.deleted_at")
        //   ->where("p.app_type", 'Non-CSE')
        //   ->where("pt.project_category", "=", $request->category)
        //   ->where("p.status", "=", 4)
        //   ->where("pt.status", "=", 4)
        //   ->where("pt.campus", session('campus'))
        //   ->where("pt.project_year",$request->year)
        //   ->update([
        //     "pt.pres_status" => $request->submit
        //   ]);

          
      $done = DB::table("signatories_app_non_cse")
          ->where("Year","=",$request->year)
          ->where("users_id",'=',session('user_id'))
          ->update([
            'status' => $request->value,
            'pres_created_at' => Carbon::now()
          ]);
            
          # this will created history_log
                (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  NULL,
                  'Submit to Pres APP NON-CSE , '.'Category: '.$request->category.', Year: '.$request->year,
                  'Submit/Not:'.$request->value,
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
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "APP NON-CSE"]
    ];
  
    $Project = DB::table("project_titles as pt")
        ->join("ppmps as p", "p.project_code", "=", "pt.id")
        ->whereNull("pt.deleted_at")
        // ->whereNull("pt.deleted_at")
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.project_category", "=", 1)
        ->where("pt.campus", session('campus'))
        ->where("p.status", "=", 4)
        ->groupBy("pt.project_year")
        ->get("pt.project_year");

        
    $Project_title = DB::table("project_titles as pt")
        ->join("ppmps as p", "p.project_code", "=", "pt.id")
        ->whereNull("pt.deleted_at")
        ->where("pt.project_year","=",$request->year)
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.campus", session('campus'))
        ->where("pt.project_category", "=", 1)
        ->where("p.status", "=", 4)
        ->groupBy("pt.project_year")
        ->get("pt.project_year");

    $Categories = DB::table("ppmps as p")
        ->join("project_titles as pt", "p.project_code", "=", "pt.id")
        ->whereNull("p.deleted_at")
        ->where("pt.project_year","=",$request->year)
        ->where("p.app_type", 'Non-CSE')
        ->where("pt.project_category", "=", 1)
        ->where("p.status", "=", 4)
        ->where("pt.status", "=", 4)
        ->where("p.campus", session('campus'))
        // ->groupBy("p.campus")
        ->groupBy("p.item_category")
        ->orderBy("p.campus","ASC")
        ->get();

    $ppmps = DB::table("ppmps as p")
          ->select("pt.project_title", "d.department_name", "p.*", "pt.fund_source","pt.project_code as ProjectCode","ab.allocated_budget","ab.remaining_balance","fs.fund_source")
          ->join("project_titles as pt", "p.project_code", "=", "pt.id")
          ->join("departments as d", "pt.department_id", "=", "d.id")
          ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
          ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
          ->where("p.app_type", 'Non-CSE')
          ->whereNull("p.deleted_at")
          ->where("p.is_supplemental", "=", 0)
          ->where("p.campus", session('campus'))
          ->where("pt.project_year","=",$request->year)
          ->where("p.status", "=", 4)
          ->where("pt.status", "=", 4)
          ->orderBy("p.department_id", "ASC")
          ->orderBy("p.project_code", "ASC")
          ->get();

    $signatures = DB::table("signatories_app_non_cse")
          // ->join("project_titles as pt", "s.signatories_id", "=", "pt.signatories_id")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->get();

    $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",1)
          ->get();

    $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",3)
          ->get();

      $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",2)
          ->get();

    $campusinfo = DB::table("campusinfo")
          ->where("campus",session('campus'))
          ->get();
    $users = DB::table("users")
          ->where("campus",session('campus'))
          ->whereNull("username")
          ->get("name");
          // dd($recommending_approval);
    return view('pages.bac.generate-app-non-cse.list', compact('Categories','ppmps','signatures','campusinfo','Project','Project_title','prepared_by','recommending_approval','approved_by','users'),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]
    );
  }

  public function generatepdf(Request $request){

    // dd($request->all());
    try{
        $count = $request->campusCheck;
        if($count  == 1){
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
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type", 'Non-CSE')
            ->where("pt.project_category", "=", $request->category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
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
            ->where("p.app_type", 'Non-CSE')
            ->whereNull("p.deleted_at")
            // ->where("p.campus", session('campus'))
            ->where("pt.project_category", "=", $request->category)
            ->where("pt.project_year","=",$request->year)
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
          

      $prepared_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",1)
          ->get();

      $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",3)
          ->get();

      $approved_by = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          ->where("Year",$request->year)
          ->where("Role","=",2)
          ->get();
          
        $campusinfo = DB::table("campusinfo")
        ->where("campus",session('campus'))
        ->get();
        $pdf = Pdf::loadView('pages.bac.generate-app-non-cse.generate-pdf', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by'))->setPaper('legal', 'landscape');
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
          'Role'=>1,
          'Campus'=>session('campus'),
          'created_at'=> Carbon::now(),
        ]);

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
          'Role'=>2,
          'Campus'=>session('campus'),
          'created_at'=> Carbon::now(),
        ]);

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
          'Role'=>3,
          'Campus'=>session('campus'),
          'created_at'=> Carbon::now(),
        ]);

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
          'updated_at'=> Carbon::now(),
        ]);

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
        return view('pages.bac.generate-app-non-cse.print', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by'));
    }catch (\Throwable $th) {
        throw $th;
    }
  }
}