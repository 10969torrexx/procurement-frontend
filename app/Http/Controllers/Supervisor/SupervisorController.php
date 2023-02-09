<?php

namespace App\Http\Controllers\Supervisor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use App\Http\Controllers\HistoryLogController;
use DB;
use Carbon\Carbon;

class SupervisorController extends Controller
{
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
    
    public function show_Traditional(Request $request){
      $aes = new AESCipher();
      $id = $aes->decrypt($request->project_code);
      $data = DB::table("ppmps as p")
                ->select("pt.project_code as code","pt.status as projectStatus","pt.id as pt_id","pt.project_title as title","p.*","pt.year_created","m.mode_of_procurement as procurementName")
                ->join('project_titles as pt','pt.id','=','p.project_code')
                ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
                ->where('p.project_code','=',$id)
                ->whereNull("p.deleted_at")
                ->where("pt.project_category","=", 1)
                ->where("p.status","!=", 0)  
                // ->orwhere("p.status","=", 2)  
                // ->orwhere("p.status","=", 3)   
                -> get();
      // $title = $data->title;
      // dd($data);
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["link" => "/supervisor","name" =>"Supervisor"],["name" =>"PPMP"]
      ];
      return view('pages.supervisor.traditional.supervisor_check_ppmp',compact('data'),
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

    public function show_Supplemental(Request $request){
      $aes = new AESCipher();
      $id = $aes->decrypt($request->project_code);
      $data = DB::table("ppmps as p")
                ->select("pt.project_code as code","pt.status as projectStatus","pt.id as pt_id","pt.project_title as title","p.*","pt.year_created","m.mode_of_procurement as procurementName")
                ->join('project_titles as pt','pt.id','=','p.project_code')
                ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
                ->where('p.project_code','=',$id)
                ->whereNull("p.deleted_at")
                ->where("pt.project_category","=", 2)
                ->where("p.status","!=", 0)  
                // ->orwhere("p.status","=", 2)  
                // ->orwhere("p.status","=", 3)   
                -> get();
      // $title = $data->title;
      // dd($data);
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["link" => "/supervisor/supplemental","name" =>"Supervisor"],["name" =>"PPMP"]
      ];
      return view('pages.supervisor.supplemental.supervisor_check_ppmp',compact('data'),
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

    public function show_Indicative(Request $request){ 
      $aes = new AESCipher();
      $id = $aes->decrypt($request->project_code);
      $data = DB::table("ppmps as p")
                ->select("pt.project_code as code","pt.status as projectStatus","pt.id as pt_id","pt.project_title as title","p.*","pt.year_created","m.mode_of_procurement as procurementName")
                ->join('project_titles as pt','pt.id','=','p.project_code')
                ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
                ->where('p.project_code','=',$id)
                ->whereNull("p.deleted_at")
                ->where("pt.project_category","=", 2)
                ->where("p.status","!=", 0)  
                // ->orwhere("p.status","=", 2)  
                // ->orwhere("p.status","=", 3)   
                -> get();
      // $title = $data->title;
      // dd($data);
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["link" => "/supervisor/indicative","name" =>"Supervisor"],["name" =>"PPMP"]
      ];
      return view('pages.supervisor.indicative.supervisor_check_ppmp',compact('data'),
      [
        'pageConfigs'=>$pageConfigs,
        'breadcrumbs'=>$breadcrumbs
      ]);
    }

    public function status(Request $request){
      // dd( $request->all());
      $item_id = $request->item_id;
      $project_code = $request->project_code;
      $status = $request->status;
      $remarks = $request->remarks;

      $response = DB::table("ppmps")
              ->where("id", $item_id)
              ->update([
                  'status' => $status,
                  'remarks' => $remarks
              ]);

              # this will created history_log
                (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  NULL,
                  'Status for Supervisor, id: '.$item_id,
                  'Status',
                  $request->ip(),
                );
              # end 
        
      if( $response)
      {
          return response()->json([
          'status' => 200, 
          // 'message' => $timeline,
      ]);    
      }
      else{
          return response()->json([
          'status' => 400, 
          // 'message' => 'error',
          ]); 
      }
    }

    public function done(Request $request){
      //  dd($request->all());
      $ppmp = DB::table("project_titles as pt")
            ->select("p.*","pt.*","ab.id as allocated_id")
            ->join('ppmps as p','p.project_code','=','pt.id')
            ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
            ->join('fund_sources as fs','fs.id','=','pt.fund_source')
            ->where("pt.id",$request->project_id)
            ->get();
            
          $allocated_id = "";
          foreach($ppmp as $ppmp){
            $allocated_id = $ppmp->allocated_id;
          }

          $checkppmp = DB::table("allocated__budgets")
            ->where("id", $allocated_id)
            ->get("remaining_balance");

          $remaining = 0 ;
          foreach($checkppmp as $checkppmp){
                  $remaining = $checkppmp->remaining_balance;
          }
          // dd($balance);
          $calc = $remaining + $request->balance;
          $updateppmp = DB::table("allocated__budgets")
                ->where("id", $allocated_id)
                ->update([
                    'remaining_balance' =>$calc,
                ]);
        #end

        #creation of project_code of project_titles table
          $status = DB::table("project_titles")
          ->where(function ($query) {
            $query->where('status', 2)
               ->orWhere('status', 4)
               ->orWhere('status', 5);
            })
          ->where('department_id',  session('department_id'))
          ->where('campus', session('campus'))
          ->where('year_created', $request->year_created)
          ->whereNull('deleted_at')
          ->get();
            
          if(empty($status)){
            # this will get the project based on project id
              $pt_year_created = DB::table('project_titles')
                    ->where('id', $request->project_id)
                    ->whereNull('deleted_at')
                    ->first();

              $code = DB::table('project_titles')
                ->where('id',$request->project_id)
                ->update([
                    'project_code'  => ""
                ]);
            # end

          }else{
            # this will get the project based on project id
              $pt_year_created = DB::table('project_titles')
                    ->where('id', $request->project_id)
                    ->whereNull('deleted_at')
                    ->first();

              $code = DB::table('project_titles')
                ->where('id',$request->project_id)
                ->update([
                    'project_code'  => $pt_year_created->year_created . '-'. (count($status) + 1)
                ]);
            # end
          }
        #end

        #for the status of the project_titles
        $project_stats = DB::table("project_titles as pt")
            ->join('ppmps as p','p.project_code','=','pt.id')
            ->where("p.project_code",$request->project_id)
            ->where("p.status",3)
            ->get();

          if(count($project_stats)>0){
            $projectStatus = DB::table("project_titles")
            ->where("id", $request->project_id)
            ->update([
                'status' =>  3
            ]);
          }else{
            $projectStatus = DB::table("project_titles")
            ->where("id", $request->project_id)
            ->update([
                'status' =>  $request->status,
            ]);
          }

            
        #endhere project_titles
        $fortimelinecheck = DB::table('project_titles')
                  ->where('id', $request->project_id)
                  ->whereNull('deleted_at')
                  ->first();

          if($fortimelinecheck->status == 2 ){
            $timeline = DB::table("project_timeline")
            ->insert([
            'employee_id'=>session('employee_id'),
            'department_id'=>session('department_id'),
            'role'=>session('role'),
            'project_id'=>$request->project_id,
            'status'=>2,
            'campus'=>session('campus'),
            'remarks'=>"Your PPMP has been approved by the Immediate Supervisor",
            'created_at' => Carbon::now()
            ]);

          }else  if($fortimelinecheck->status == 3 ){
            $timeline = DB::table("project_timeline")
            ->insert([
            'employee_id'=>session('employee_id'),
            'department_id'=>session('department_id'),
            'role'=>session('role'),
            'project_id'=>$request->project_id,
            'status'=>3,
            'campus'=>session('campus'),
            'remarks'=>"Your PPMP needs to be revised",
            'created_at' => Carbon::now()
            ]);  
          }

            # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'Status and projectCode for this id: '.$request->project_id,
                'Status adn Generate',
                $request->ip(),
              );
            # end 

          if( $timeline){
            return response()->json([
            'status' => 200, 
              // 'message' => $timeline,
            ]);    
            // return redirect()->route('/property');
          }
          else{
            return response()->json([
            'status' => 400, 
            // 'message' => 'error',
            ]); 
          }

          
      // }
    }

    public function accept_reject_all(Request $request){
      // dd($request->all());
      $budget = DB::table("project_titles as pt")
              ->select("ab.remaining_balance","pt.year_created","pt.project_category")
              ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
              ->whereNull("pt.deleted_at")
              ->whereNull("ab.deleted_at")
              ->where("pt.id",$request->id)
              ->get();

      if($request->value == 2){
        $itemcheck = DB::table("project_titles as pt")
                ->select("p.estimated_price")
                ->join('ppmps as p','p.project_code','=','pt.id')
                ->whereNull("pt.deleted_at")
                ->whereNull("p.deleted_at")
                ->where(function ($query) {
                  $query->where('p.status', 3);

                  })
                ->where("pt.id",$request->id)
                ->get();

          $itemcheckpending = DB::table("project_titles as pt")
                ->select("p.estimated_price")
                ->join('ppmps as p','p.project_code','=','pt.id')
                ->whereNull("pt.deleted_at")
                ->whereNull("p.deleted_at")
                ->where(function ($query) {
                  $query->where('p.status', 1);

                  })
                ->where("pt.id",$request->id)
                ->get();
      }else{
        $itemcheck = DB::table("project_titles as pt")
                ->select("p.estimated_price")
                ->join('ppmps as p','p.project_code','=','pt.id')
                ->whereNull("pt.deleted_at")
                ->whereNull("p.deleted_at")
                ->where(function ($query) {
                  $query->where('p.status', 1)
                     ->orWhere('p.status', 2)
                     ->orWhere('p.status', 6);
  
                  })
                ->where("pt.id",$request->id)
                ->get();
      }
      // dd($itemcheck);
      $itemtotal = 0;
      // $project_id = "";
      foreach($itemcheck as $itemcheck){
        $itemtotal += $itemcheck->estimated_price;
      }

      if($itemtotal > 0 || !empty($itemcheckpending)){
        $total = 0;
        $projectCat = "";
        foreach($budget as $budget){
          $total = $budget->remaining_balance;
          $projectCat = $budget->project_category;
        }
           
        if($request->value == 2){
          $compute = $total - $itemtotal;
        }else{
          $compute = $total + $itemtotal;
        }
        // dd($compute);
        // dd($ppmp);

        #creation of project_code of project_titles table
          $status = DB::table("project_titles")
              ->where(function ($query) {
                $query->where('status', 2)
                  ->orWhere('status', 4)
                  ->orWhere('status', 5);
                })
              ->where('department_id',  session('department_id'))
              ->where('campus', session('campus'))
              ->where('project_category',"=", $projectCat)
              ->where('year_created',$budget->year_created)
              ->whereNull('deleted_at')
              ->get();

          $pt_year_created = DB::table('project_titles')
                ->where('id', $request->id)
                ->whereNull('deleted_at')
                ->first();
            
          if(empty($status)){
            # this will get the project based on project id
            $project_code = "" ;
            # end

          }else{
            # this will get the project based on project id
            $project_code = $pt_year_created->year_created . '-'. (count($status) + 1);
            # end
          }
        #end
        
        $ppmp = DB::table("project_titles as pt")
              ->join('ppmps as p','p.project_code','=','pt.id')
              ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
              ->join('fund_sources as fs','fs.id','=','pt.fund_source')
              ->where("pt.id",$request->id)
              ->update([
                'pt.status' => $request->value,
                'pt.project_code' => $project_code,
                'p.status' => $request->value,
                'p.remarks' => $request->remarks,
                'ab.remaining_balance' => $compute,
              ]);

            if($request->value == 2 ){
              $timeline = DB::table("project_timeline")
              ->insert([
              'employee_id'=>session('employee_id'),
              'department_id'=>session('department_id'),
              'role'=>session('role'),
              'project_id'=>$request->id,
              'status'=>2,
              'campus'=>session('campus'),
              'remarks'=>"Your PPMP has been approved by the Immediate Supervisor",
              'created_at' => Carbon::now()
              ]);

            }else{
              $timeline = DB::table("project_timeline")
              ->insert([
              'employee_id'=>session('employee_id'),
              'department_id'=>session('department_id'),
              'role'=>session('role'),
              'project_id'=>$request->id,
              'status'=>3,
              'campus'=>session('campus'),
              'remarks'=>"Your PPMP needs to be revised",
              'created_at' => Carbon::now()
              ]);  
            }

            
          # this will created history_log
            (new HistoryLogController)->store(
              session('department_id'),
              session('employee_id'),
              session('campus'),
              NULL,
              'Status from Supervisor for this id: '.$request->id,
              'Status',
              $request->ip(),
            );
          # end 

        if($ppmp)
        {
            return response()->json([
            'status' => 200, 
            // 'message' => $timeline,
        ]);    
        }
        else{
            return response()->json([
            'status' => 400, 
            // 'message' => 'error',
            ]); 
        }
        
      }else{
        // dd("akgsdk");
        return response()->json([
        'status' => 500, 
        ]); 
      }
    }
}
