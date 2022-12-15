<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
use Carbon\Carbon;

class SupervisorController extends Controller
{
    public function index(){
      
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
              ->where("p.is_supplemental","=", 0)
              ->groupBy("pt.project_title")
              -> get();

              
        $item = DB::table("ppmps as p")
              ->select("pt.project_code as code","pt.id as pt_id","pt.project_title as title","p.*")
              ->join('project_titles as pt','pt.id','=','p.project_code')
              ->whereNull("p.deleted_at")
              ->where("pt.campus",session('campus'))
              ->where("p.is_supplemental","=", 0)
              -> get();

        // $ppmp =  Http::withToken(session('token'))->get(env('APP_API'). "/api/supervisor/index")->json();
        // dd($ppmp);
        return view('pages.supervisor.ppmp', compact('ppmp','item'),
        [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs
        ]);
    }

    public function show(Request $request){
      $aes = new AESCipher();
      $id = $aes->decrypt($request->project_code);
      $data = DB::table("ppmps as p")
                ->select("pt.project_code as code","pt.status as projectStatus","pt.id as pt_id","pt.project_title as title","p.*","pt.year_created","m.mode_of_procurement as procurementName")
                ->join('project_titles as pt','pt.id','=','p.project_code')
                ->join('mode_of_procurement as m','m.id','=','p.mode_of_procurement')
                ->where('p.project_code','=',$id)
                ->whereNull("p.deleted_at")
                ->where("p.is_supplemental","=", 0)
                ->where("p.status","!=", 0)  
                // ->orwhere("p.status","=", 2)  
                // ->orwhere("p.status","=", 3)   
                -> get();
      // $title = $data->title;
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
      // dd($ppmp);
      // if(empty($ppmp)){
      //   $status = DB::table("project_titles")
      //         ->where("status",2)
      //         ->where('department_id',  session('department_id'))
      //         ->where('campus', session('campus'))
      //         ->whereNull('deleted_at')
      //         ->get();

      //   # this will get the project based on project id
      //   if(empty($status)){
      //     # this will get the project based on project id
      //       $pt_year_created = DB::table('project_titles')
      //             ->where('id', $request->project_id)
      //             ->whereNull('deleted_at')
      //             ->first();

      //       $code = DB::table('project_titles')
      //         ->where('id',$request->project_id)
      //         ->update([
      //             'project_code'  => ""
      //         ]);
      //     # end

      //   }else{
      //     # this will get the project based on project id
      //     $pt_year_created = DB::table('project_titles')
      //           ->where('id', $request->project_id)
      //           ->whereNull('deleted_at')
      //           ->first();

      //     $code = DB::table('project_titles')
      //       ->where('id',$request->project_id)
      //       ->update([
      //           'project_code'  => $pt_year_created->year_created . '-'. (count($status) + 1)
      //       ]);
      //     # end
      //   }
      //   #end

      //   #for the status of the project_titles
      //     $statuschecker = DB::table("project_titles as pt")
      //       ->select("p.*","pt.*","ab.id as allocated_id")
      //       ->join('ppmps as p','p.project_code','=','pt.id')
      //       ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
      //       ->join('fund_sources as fs','fs.id','=','pt.fund_source')
      //       ->where("p.project_code",$request->project_id)
      //       ->where("p.status",3)
      //       ->where('p.campus', session('campus'))
      //       ->get();

      //     if(count($statuschecker)>0){
      //       $projectStatus = DB::table("project_titles")
      //       ->where("id", $request->project_id)
      //       ->update([
      //           'status' => 3
      //       ]);
      //     }else{
      //       $projectStatus = DB::table("project_titles")
      //       ->where("id", $request->project_id)
      //       ->update([
      //           'status' => 2
      //       ]);

      //     }
      //   //endhere project_titles

      //   #for Project Timeline
      //     if($pt_year_created->status == 2 ){
      //       $timeline = DB::table("project_timeline")
      //       ->insert([
      //       'employee_id'=>session('employee_id'),
      //       'department_id'=>session('department_id'),
      //       'role'=>session('role'),
      //       'project_id'=>$request->project_id,
      //       'status'=>2,
      //       'campus'=>session('campus'),
      //       'remarks'=>"Your PPMP has been approved by the Immediate Supervisor",
      //       'created_at' => Carbon::now()
      //       ]);
      //     }else  if($pt_year_created->status == 3 ){
      //       $timeline = DB::table("project_timeline")
      //       ->insert([
      //       'employee_id'=>session('employee_id'),
      //       'department_id'=>session('department_id'),
      //       'role'=>session('role'),
      //       'project_id'=>$request->project_id,
      //       'status'=>3,
      //       'campus'=>session('campus'),
      //       'remarks'=>"Your PPMP needs to be revised",
      //       'created_at' => Carbon::now()
      //       ]);
      //     }
      //   #end

      //     if( $projectStatus){
      //       return response()->json([
      //       'status' => 200, 
      //         // 'message' => $timeline,
      //       ]);    
      //       // return redirect()->route('/property');
      //     }
      //     else{
      //       return response()->json([
      //       'status' => 400, 
      //       // 'message' => 'error',
      //       ]); 
      //     }
      // }else{
        // $balance = 0;
        #return the Estimated Price to the allocated budget table('remaining_balance')
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
              ->select("ab.remaining_balance")
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

      if($itemtotal > 0){
        $total = 0;
        foreach($budget as $budget){
          $total = $budget->remaining_balance;
        }
           
        if($request->value == 2){
          $compute = $total - $itemtotal;
        }else{
          $compute = $total + $itemtotal;
        }
        // dd($compute);
        $ppmp = DB::table("project_titles as pt")
              ->join('ppmps as p','p.project_code','=','pt.id')
              ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
              ->join('fund_sources as fs','fs.id','=','pt.fund_source')
              ->where("pt.id",$request->id)
              ->update([
                'pt.status' => $request->value,
                'p.status' => $request->value,
                'p.remarks' => $request->remarks,
                'ab.remaining_balance' => $compute,
              ]);
        // dd($ppmp);

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
