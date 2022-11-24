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
        // dd(session("department_id"));
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
        // dd($data);   
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
        
        // dd($request->all());  
        $aes = new AESCipher();
        $item_id = $request->item_id;
        $project_code = $request->project_code;
        $status = $request->status;
        $remarks = $request->remarks;
        // $employee_id = $aes->decrypt($request->employee_id);
        // $department_id = $aes->decrypt($request->department_id);
        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/supervisor-ppmp-approved",[
          'item_id' => $item_id,
          'status' => $status,
          'project_code' => $project_code,
          'remarks' => $remarks,
          // 'employee'=> session('employee_id'),
          // 'role'=> session('role'),
          // 'department'=> session('department_id'),
          // 'campus'=>session('campus'),
        ])->json();
      // dd($response); 
            return $response; 
      }

      public function done(Request $request){
        // dd($request->all());  
        
        $status = DB::table("project_titles")
                ->where("status",2)
                ->where('department_id',  session('department_id'))
                ->whereNull('deleted_at')
                ->get();

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

            $stat = DB::table("project_titles")
                  ->where("id", $request->project_id)
                  ->update([
                      'status' => $request->status
                  ]);

        if($pt_year_created->status == 2 ){
          $timeline = DB::table("project_timeline")
          ->insert([
          'employee_id'=>session('employee_id'),
          'department_id'=>session('department_id'),
          'role'=>session('role'),
          'project_id'=>$request->project_id,
          'status'=>3,
          'campus'=>session('campus'),
          'remarks'=>"Your PPMP has been approved by the Immediate Supervisor",
          'created_at' => Carbon::now()
          ]);
        }else  if($pt_year_created->status == 3 ){
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

        if( $timeline)
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
}
