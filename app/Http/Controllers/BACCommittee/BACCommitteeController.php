<?php

namespace App\Http\Controllers\BACCommittee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use App\Http\Controllers\HistoryLogController;
use DB;
use Carbon\Carbon;

class BACCommitteeController extends Controller
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
        ->where(function ($query) {
            $query->where("pt.per_campus_status","=", 0)
            ->orWhere("pt.per_campus_status","=", 1)
            ->orWhere("pt.per_campus_status","=", 2)
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
            $query->where("pt.univ_wide_status","=", 0)
            ->orWhere("pt.univ_wide_status","=", 1)
            ->orWhere("pt.univ_wide_status","=", 2)
            ->orWhere("pt.univ_wide_status","=", 3);
          })
        ->whereNull("pt.deleted_at")
        ->whereNull("p.deleted_at")
        ->groupBy("project_year")
        -> get();
    // dd($app);

    $recommending_approval = DB::table("signatories_app_non_cse")
          ->where("campus",session('campus'))
          // ->where("Year",/* date("Y") */ $val)
          ->where("Role","=",3)
          ->where("users_id","=",session('user_id'))
          ->get();
          // dd($app);
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" =>"APP NON CSE"]
    ];
    return view('pages.BACCommitte.list',compact('app','app1','recommending_approval'),
    [
      'pageConfigs'=>$pageConfigs,
      'breadcrumbs'=>$breadcrumbs
    ]);
  }

  public function index_app_noncse(Request $request){
    // dd($request->all());
      try {
        $aes = new AESCipher();
        $year = $aes->decrypt($request->year);
        $category = $aes->decrypt($request->category);
        $scope = $request->scope;
        $cat = new GlobalDeclare();
        $project_category = $cat->project_category($category);

        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["link" => "/baccommittee/list/".$project_category,"name" => "APP NON CSE"],["link" => "/", "name" => $year]
        ];
          if($request->scope == 1){
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
                ->where("p.campus", session('campus'))
                ->where(function ($query) {
                    $query->where("pt.per_campus_status","=", 0)
                    ->orWhere("pt.per_campus_status","=", 1)
                    ->orWhere("pt.per_campus_status","=", 2)
                    ->orWhere("pt.per_campus_status","=", 3);
                  })
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
                ->where('pt.per_campus_status', 1) // ! torrexx - change
                ->where("pt.project_year","=",$year)
                ->where("p.app_type", 'Non-CSE')
                ->where("pt.project_category", "=", $category)
                ->where("p.status", "=", 4)
                ->where("pt.status", "=", 4)
                ->where("p.campus", session('campus'))
                ->groupBy("p.campus")
                ->groupBy("p.item_category")
                ->orderBy("p.campus","ASC")
                  ->get();
            }
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
                      $query->where("pt.per_campus_status","=", 0)
                      ->orWhere("pt.per_campus_status","=", 1)
                      ->orWhere("pt.per_campus_status","=", 2)
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
              ->where(function ($query) {
                  $query->where("pt.per_campus_status","=", 0)
                  ->orWhere("pt.per_campus_status","=", 1)
                  ->orWhere("pt.per_campus_status","=", 2)
                  ->orWhere("pt.per_campus_status","=", 3);
                })
              ->where("pt.project_year",$year)
              ->where("pt.campus", session('campus'))
              ->groupBy("pt.campus")
              ->get();
              
            $prepared_by = DB::table("signatories_app_non_cse")
                ->where("campus",session('campus'))
                ->where("project_category",$category)
                ->where("Year",$year)
                ->where("Role","=",1)
                ->get();
              // dd($prepared_by);
              
            $signatories = DB::table("signatories_app_non_cse")
                ->where("campus",session('campus'))
                ->where("project_category",$category)
                ->where("Year",$year)
                ->where('Role',3)
                ->where("users_id",'=',session('user_id'))
                ->get();

            $recommending_approval = DB::table("signatories_app_non_cse")
                ->where("campus",session('campus'))
                ->where("project_category",$category)
                ->where("Year",$year)
                ->where("Role","=",3)
                ->get();

            $approved_by = DB::table("signatories_app_non_cse")
                ->where("campus",session('campus'))
                ->where("project_category",$category)
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
                ->where("pt.project_category", "=", $category)
                ->where("pt.endorse",1)
                ->where(function ($query) {
                    $query->where("pt.univ_wide_status","=", 0)
                    ->orWhere("pt.univ_wide_status","=", 1)
                    ->orWhere("pt.univ_wide_status","=", 2)
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
                      $query->where("pt.univ_wide_status","=", 0)
                      ->orWhere("pt.univ_wide_status","=", 1)
                      ->orWhere("pt.univ_wide_status","=", 2)
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
                  ->where("p.app_type", 'Non-CSE')
                  ->where("pt.project_category", "=", $category)
                  ->where("pt.endorse",1)
                  ->where(function ($query) {
                      $query->where("pt.univ_wide_status","=", 0)
                      ->orWhere("pt.univ_wide_status","=", 1)
                      ->orWhere("pt.univ_wide_status","=", 2)
                      ->orWhere("pt.univ_wide_status","=", 3);
                    })
                  ->where("p.status", "=", 4)
                  ->where("pt.status", "=", 4)
                  ->where("pt.project_year",$year)
                  ->groupBy("pt.campus")
                  ->get();
                  
          
            $prepared_by = DB::table("signatories_app_non_cse")
                ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
                ->where("campus",session('campus'))
                ->where("project_category",$category)
                ->where("Year",$year)
                ->where("Role","=",1)
                ->get();
                // dd($prepared_by);
                
            $signatories = DB::table("signatories_app_non_cse")
                ->where("campus",session('campus'))
                ->where("project_category",$category)
                ->where("Year",$year)
                ->where('Role',3)
                ->where("users_id",'=',session('user_id'))
                ->get();

            $recommending_approval = DB::table("signatories_app_non_cse")
                ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
                ->where("campus",session('campus'))
                ->where("project_category",$category)
                ->where("Year",$year)
                ->where("Role","=",3)
                ->get();

            $approved_by = DB::table("signatories_app_non_cse")
                ->select("id","users_id","Name","Profession","Title","Role","Position","univ_wide_status as status","campus","Year",)
                ->where("campus",session('campus'))
                ->where("project_category",$category)
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

        return view('pages.BACCommitte.app', compact('ppmps','signatories','campusinfo'/* ,'expired' */,'scope','Categories','campusCheck'/* ,'Project_title' */,'prepared_by','recommending_approval','approved_by'),
        [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs
        ]);
      } catch (\Throwable $th) {
        throw $th;
      }
  }

  public function generatepdf(Request $request){
    // dd($request->all());
    try{
        $scope = $request->scope;
        $count = $request->campusCheck;
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
          ->where("p.campus", session('campus'))
          ->where(function ($query) {
              $query->where("pt.per_campus_status","=", 0)
              ->orWhere("pt.per_campus_status","=", 1)
              ->orWhere("pt.per_campus_status","=", 2)
              ->orWhere("pt.per_campus_status","=", 3);
            })
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
                $query->where("pt.per_campus_status","=", 0)
                ->orWhere("pt.per_campus_status","=", 1)
                ->orWhere("pt.per_campus_status","=", 2)
                ->orWhere("pt.per_campus_status","=", 3);
              })
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
            // dd($ppmps);

        }else{
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type","=",$request->app_type)
            ->where("pt.project_category", "=", $request->project_category)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->where("pt.endorse",1)
            ->where(function ($query) {
                $query->where("pt.univ_wide_status","=", 0)
                ->orWhere("pt.univ_wide_status","=", 1)
                ->orWhere("pt.univ_wide_status","=", 2)
                ->orWhere("pt.univ_wide_status","=", 3);
              })
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
                'BAC Committee generate APP '.$request->app_type,
                'Generate PDF',
                $request->ip(),
              );
            # end   
        $pdf = \Pdf::loadView('pages.BACCommitte.generate-pdf', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by','scope'))->setPaper('legal', 'landscape');
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
        throw $th;
    }
  }

  public function bac_committee_decision(Request $request){
    // dd($request->all());
      try {
          if ($request->scope == 0) {

            $signatories = DB::table("signatories_app_non_cse")
              ->where("Year","=",$request->year)
              ->where("project_category", "=", $request->category)
              ->where("users_id",'=',session('user_id'))
              ->where("Role",'=',3)
              ->update([
                'univ_wide_status' => $request->value,
                // 'bac_committee_created_at' => Carbon::now()
              ]);
  
            if($signatories){
            
              #Bac Committee Status in Project titles table
                $sign = DB::table("signatories_app_non_cse")
                  ->where("Year","=",$request->year)
                  ->where("project_category", "=", $request->category)
                  ->where("campus",'=',session('campus'))
                  ->where("Role",'=',3)
                  ->where("univ_wide_status",'=',1)
                  ->get("univ_wide_status");
                  // dd($sign);
                  $signatures = 0;
                  foreach($sign as $bacStat) {
                    $signatures += $bacStat->univ_wide_status;
                  }
    
                  // $bac_committee_stat = "";
                  if($signatures >= 8){
                    $bac_committee_stat = 1;
                  }else{
                    $bac_committee_stat = "--";
                  }
    
                  $app = DB::table("project_titles as pt")
                      ->join("ppmps as p", "p.project_code", "=", "pt.id")
                      ->where("pt.project_category","=", $request->category)
                      ->where("p.app_type","=", $request->app_type)
                      ->where("pt.status","=", 4)  
                      ->where("pt.project_year","=", $request->year)
                      ->where("pt.endorse",1)
                      ->where(function ($query) {
                          $query->where("pt.univ_wide_status","=", 0)
                          ->orWhere("pt.univ_wide_status","=", 1)
                          ->orWhere("pt.univ_wide_status","=", 2)
                          ->orWhere("pt.univ_wide_status","=", 3);
                        })
                      ->whereNull("pt.deleted_at")
                      ->whereNull("p.deleted_at")
                      ->update([
                        'pt.univ_wide_status' => $bac_committee_stat,
                      ]);
              #end
            
              # this will created history_log
                (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  NULL,
                  'BAC Committee Status APP '.$request->app_type.': '.$bac_committee_stat.'Year: '.$request->year,
                  'Recommend or Not',
                  $request->ip(),
                );
              # end 
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
              ->where("users_id",'=',session('user_id'))
              ->where("Role",'=',3)
              ->update([
                'status' => $request->value,
              ]);
  
            if($signatories){
            
              #Bac Committee Status in Project titles table
                $sign = DB::table("signatories_app_non_cse")
                  ->where("Year","=",$request->year)
                  ->where("project_category", "=", $request->category)
                  ->where("campus",'=',session('campus'))
                  ->where("Role",'=',3)
                  ->where("status",'=',1)
                  ->get("status");
                  // dd($sign);
                  $signatures = 0;
                  foreach($sign as $bacStat) {
                    $signatures += $bacStat->status;
                  }
    
                  $bac_committee_stat = "0";
                  if($signatures >= 8){
                    $bac_committee_stat = 1;
                  }else{
                    $bac_committee_stat = "--";
                  }
    
                  $app = DB::table("project_titles as pt")
                      ->join("ppmps as p", "p.project_code", "=", "pt.id")
                      ->where("pt.project_category","=", $request->category)
                      ->where("p.app_type","=", $request->app_type)
                      ->where("pt.status","=", 4)  
                      ->where("pt.project_year","=", $request->year)
                      ->whereNull("pt.deleted_at")
                      ->whereNull("p.deleted_at")
                      ->where(function ($query) {
                          $query->where("pt.per_campus_status","=", 0)
                          ->orWhere("pt.per_campus_status","=", 1)
                          ->orWhere("pt.per_campus_status","=", 2)
                          ->orWhere("pt.per_campus_status","=", 3);
                        })
                      ->update([
                        'pt.per_campus_status' => $bac_committee_stat,
                      ]);
              #end
            
              # this will created history_log
                (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  NULL,
                  'BAC Committee Status APP '.$request->app_type.': '.$bac_committee_stat.'Year: '.$request->year,
                  'Recommend or Not',
                  $request->ip(),
                );
              # end 
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
        $count = $request->campusCheck;
        if($scope == 1){
          $scope = 1;
          $Categories = DB::table("ppmps as p")
          ->join("project_titles as pt", "p.project_code", "=", "pt.id")
          ->whereNull("p.deleted_at")
          ->whereNull("pt.deleted_at")
          ->where("pt.project_year","=",$request->year)
          ->where("p.app_type","=",$request->app_type)
          ->where("pt.project_category", "=", $request->category)
          ->where(function ($query) {
              $query->where("pt.per_campus_status","=", 0)
              ->orWhere("pt.per_campus_status","=", 1)
              ->orWhere("pt.per_campus_status","=", 2)
              ->orWhere("pt.per_campus_status","=", 3);
            })
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
            ->where(function ($query) {
                $query->where("pt.per_campus_status","=", 0)
                ->orWhere("pt.per_campus_status","=", 1)
                ->orWhere("pt.per_campus_status","=", 2)
                ->orWhere("pt.per_campus_status","=", 3);
              })
            ->where("pt.project_year","=",$request->year)
            ->where("p.status", "=", 4)
            ->where("pt.status", "=", 4)
            ->orderBy("p.department_id", "ASC")
            ->orderBy("p.project_code", "ASC")
            ->get();
            // dd($ppmps);

        }else{
          $scope = 0;
          $Categories = DB::table("ppmps as p")
            ->join("project_titles as pt", "p.project_code", "=", "pt.id")
            ->whereNull("p.deleted_at")
            ->where("pt.project_year","=",$request->year)
            ->where("p.app_type","=",$request->app_type)
            ->where("pt.project_category", "=", $request->category)
            ->where("pt.endorse",1)
            ->where(function ($query) {
                $query->where("pt.univ_wide_status","=", 0)
                ->orWhere("pt.univ_wide_status","=", 1)
                ->orWhere("pt.univ_wide_status","=", 2)
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
                $query->where("pt.univ_wide_status","=", 0)
                ->orWhere("pt.univ_wide_status","=", 1)
                ->orWhere("pt.univ_wide_status","=", 2)
                ->orWhere("pt.univ_wide_status","=", 3);
              })
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
            'BAC Committee Print APP '.$request->app_type.', Year: '.$request->year,
            'Print',
            $request->ip(),
          );
        # end 
        return view('pages.BACCommitte.print', compact('Categories','ppmps','signatures','campusinfo','prepared_by','recommending_approval','approved_by','scope'));
    }catch (\Throwable $th) {
        throw $th;
    }
  }

  /**
   * ! Torrexx Additionals
   * * Generate Request For Quotation & Notice (RFQ)
   * TODO 1: Enable generate RFQ
   */
  public function show_generate_rfq() {
    try {
       /** This will return table and page configs */
       $pageConfigs = ['pageHeader' => true];
       $breadcrumbs = [
       ["link" => "/", "name" => "Home"],
       ["name" => "Generate RFQ"]
       ];
       return view('pages.BACCommitte.generate-rfq',
          ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
        );
    } catch (\Throwable $th) {
        throw $th;
        return view('pages.error-500');
    }          
  }
}
