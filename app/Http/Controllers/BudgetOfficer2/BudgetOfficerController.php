<?php

namespace App\Http\Controllers\BudgetOfficer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Deadline;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use DB;
use Carbon\Carbon;

class BudgetOfficerController extends Controller
{
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Set PPMP Deadline"]
        ];
        // return view('pages.budgetofficer.index',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);

        $ppmp_deadline =  Http::withToken(session('token'))->get(env('APP_API'). "/api/ppmp_deadline/index")->json();
        //    dd($ppmp_deadline);
            $error="";
            if($ppmp_deadline['status']==400){
                $error=$ppmp_deadline['message'];
            }

            return view('pages.budgetofficer.index',compact('ppmp_deadline'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                'error' => $error,
            ]);
    }
    public function try(){
        // $ppmp_deadline =  Http::withToken(session('token'))->get(env('APP_API'). "/api/ppmp_deadline/index")->json();
        //     dd($ppmp_deadline);
            // $timeline = DB::table('project_timeline')
    }
    public function PPMPindex(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" =>"PPMPs"]
        ];
        
        $ppmp = DB::table("project_titles as pt")
              ->select("pt.*","ab.allocated_budget","ab.remaining_balance","fs.fund_source","d.department_name")
              // ->selectRaw("Sum(p.estimated_price) as Total")
              ->join('ppmps as p','p.project_code','=','pt.id')
              ->join('departments as d','pt.department_id','=','d.id')
              ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
              ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
              ->whereNull("pt.deleted_at")
            //   ->where("pt.status","=", 2 ,"or", 4,"or", 5)  
              ->whereRaw('pt.status = 2 or pt.status = 4 or pt.status = 5')  

            //   ->where("pt.department_id","=", session("department_id"))  
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
        // dd($ppmp);
        return view('pages.budgetofficer.view-ppmp', compact('ppmp','item'),
        [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs
        ]);
      }

      public function showPPMP(Request $request){
            // dd($request->all());  
        $aes = new AESCipher();
        $id = $aes->decrypt($request->project_code);
        $data = DB::table("ppmps as p")
                  ->select("pt.project_code as code","pt.status as projectStatus","pt.id as pt_id","pt.project_title as title","p.*")
                  ->join('project_titles as pt','pt.id','=','p.project_code')
                  ->whereNull("p.deleted_at")
                  ->where("p.is_supplemental","=", 0)
                //   ->where("p.status","=", 1)  
                //   ->where("p.status","=", 2, 'or')  
                //   ->where("p.status","=", 4, 'or')   
                //   ->where("p.status","=", 5, 'or')   
                  ->where('p.project_code','=',$id,'and', "p.status","=", 2 ,"or", 4,"or", 5)  
                //   ->where("p.status","=", 4)   
                //   ->where("p.status","=", 5)
                  -> get();
        // $title = $data->title;
        // dd($data);   
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],
          ["link" => "/budgetofficer/view_ppmp","name" =>"Supervisor"],
          ["name" =>"PPMP"]
        ];
            return view('pages.budgetofficer.check_ppmp',compact('data'),
            [
              'pageConfigs'=>$pageConfigs,
              'breadcrumbs'=>$breadcrumbs
            ]);
          }

          public function status(Request $request){
            // dd($request->all());
            
            $item_id = $request->item_id;
            $project_code = $request->project_code;
            $status = $request->status;
            $remarks = $request->remarks;
            if($status == 4){
                $changed = DB::table('ppmps as p')
                        ->where('id', $item_id)
                        ->update(['status' =>  $status]
                        );
            }else if($status == 5){

                $changed = DB::table('ppmps as p')
                ->where('id', $item_id)
                ->update([
                    'status' =>  $status,
                    'remarks' =>  $remarks
                    ]
                );
        
                // DB::table("project_timeline")
                // ->insert([
                // 'employee_id'=>session('employee_id'),
                // 'department_id'=>session('department_id'),
                // 'role'=>session('role'),
                // 'project_id'=>$project_code,
                // 'status'=>5,
                // 'campus'=>session('campus'),
                // 'remarks'=>"Your PPMP needs to be revised",
                // 'created_at' => Carbon::now()
                // ]);
            }
                $proTitle = DB::table('ppmps as p')
                            ->where('project_code',$project_code)
                            ->where('status',5)
                            ->get();
                $count = count($proTitle);

                if($count!=0)
                { 
                    $stat = DB::table('project_titles')
                            ->where('id',$project_code)
                            ->update([
                                'status' =>  5,
                                ]
                            );
                }
                else{
                    // DB::table("project_timeline")
                    // ->insert([
                    // 'employee_id'=>session('employee_id'),
                    // 'department_id'=>session('department_id'),
                    // 'role'=>session('role'),
                    // 'project_id'=>$project_code,
                    // 'status'=>4,
                    // 'campus'=>session('campus'),
                    // 'remarks'=>"Your PPMP has been approved by the Budget Officer",
                    // 'created_at' => Carbon::now()
                    // ]); 
                   
                    $stat = DB::table('project_titles')
                    ->where('id',$project_code)
                    ->update([
                        'status' =>  4,
                        ]
                    );
                }
                if($changed)
            {
                return response()->json([
                'status' => 200, 
            ]); 
            }
            else{
                return response()->json([
                'status' => 400, 
                ]); 
            }
          }
          public function timeline(Request $request){
            // dd($request->all());

            $project_code = $request->project_code;
            $count_disapproved = $request->count_disapproved;
            if($count_disapproved == 0){
                $project_timeline = DB::table("project_timeline")
                    ->insert([
                    'employee_id'=>session('employee_id'),
                    'department_id'=>session('department_id'),
                    'role'=>session('role'),
                    'project_id'=>$project_code,
                    'status'=>4,
                    'campus'=>session('campus'),
                    'remarks'=>"Your PPMP has been approved by the Budget Officer",
                    'created_at' => Carbon::now()
                    ]); 
            }else if($count_disapproved > 0){
                $project_timeline = DB::table("project_timeline")
                    ->insert([
                    'employee_id'=>session('employee_id'),
                    'department_id'=>session('department_id'),
                    'role'=>session('role'),
                    'project_id'=>$project_code,
                    'status'=>5,
                    'campus'=>session('campus'),
                    'remarks'=>"Your PPMP needs to be revised",
                    'created_at' => Carbon::now()
                    ]);
            }
            if($project_timeline)
            {
                return response()->json([
                'status' => 200, 
            ]); 
            }
            else{
                return response()->json([
                'status' => 400, 
                ]); 
            }
          }

    public function addMandatoryExpenditure(Request $request){
        // dd($request->all());
        $department = $request->department;
        $fund_source = $request->fund_source;
        $year = $request->year;
        $expenditure = $request->expenditure;
        $price = $request->price;

        $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/expenditure/addMandatoryExpenditure",[
            'department' => $department,
            'fund_source' => $fund_source,
            'year' => $year,
            'expenditure' => $expenditure,
            'price' => $price,
            ])->json();
            // dd($response);
            return $response;
    }
    public function editMandatoryExpenditure(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $id1 = (new AESCipher())->decrypt($id);

        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/expenditure/editMandatoryExpenditure/".$id1,[
            'id' => $id1,
        ])->json();
        // dd($response);
        return $response;
    }
    public function updateMandatoryExpenditure(Request $request)
    {
        // dd($request->all());
            $expenditure = $request->expenditure;
            $price = $request->price;
            $id = (new AESCipher())->decrypt($request->id);
            // dd($id);
            $response = Http::withToken(session('token'))->put(env('APP_API'). "/api/expenditure/updateMandatoryExpenditure/".$id,[
            'expenditure' => $expenditure,
            'price' => $price,
            'id' => $id,
            ])->json();
            // dd($response);
            return $response;
    }
    public function deleteMandatoryExpenditure(Request $request){
        $id = (new AESCipher())->decrypt($request->id);
        $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/expenditure/deleteMandatoryExpenditure/".$id,[
            'id' => $id,
        ])->json();
        return $response;
    }
    public function mandatory_expenditures(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Mandatory Expenditures"]
        ];
        // return view('pages.budgetofficer.index',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);

        $mandatory_expenditures =  Http::withToken(session('token'))->get(env('APP_API'). "/api/mandatory_expenditures/getMandatoryExpenditures")->json();
            // dd($mandatory_expenditures);
            $error="";
            if($mandatory_expenditures['status']==400){
                $error=$mandatory_expenditures['message'];
            }
        
            return view('pages.budgetofficer.mandatory-expenditures-index',compact('mandatory_expenditures'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                'error' => $error,
            ]);
    }

    
    public function allocate_budget(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Allocate Budget"]
        ];
        
        return view('pages.budgetofficer.index',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);
    }

    public function store(Request $request)
    {
            $Year = (new AESCipher)->decrypt($request->year);
            $StartDate = $request->start_date;
            $EndDate = $request->end_date;
            $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/ppmp_deadline/store",[
            'year' => $Year,
            'start_date' => $StartDate,
            'end_date' => $EndDate,
            ])->json();
            // dd($response);
            return $response;
    }

    public function delete(Request $request){
        $id = $request->id;
        $id1 = (new AESCipher())->decrypt($id);
        $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/ppmp_deadline/delete/".$id1,[
            'id' => $id1,
        ])->json();
        if($response){
            if($response['status'] == 200){
                return response()->json([
                'status' => 200, 
                ]);    
            }elseif($response['status'] == 404){
                return response()->json([
                    'status' => 404, 
                ]);  
            }
        }
    }
   
    public function edit(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $id1 = (new AESCipher())->decrypt($id);

        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/ppmp_deadline/edit/".$id1,[
            'id' => $id1,
        ])->json();
        // dd($response);
        return $response;
    }

    public function update(Request $request)
    {
            $update_year = $request->update_year;
            $update_start_date = $request->update_start_date;
            $update_end_date = $request->update_end_date;
            $updateid = $request->updateid;
            $id = (new AESCipher())->decrypt($updateid);
            
            $response = Http::withToken(session('token'))->put(env('APP_API'). "/api/ppmp_deadline/update/".$id,[
            'updateid' => $id,
            'update_year' => $update_year,
            'update_start_date' => $update_start_date,
            'update_end_date' => $update_end_date,
            ])->json();
            // dd($response);
            return $response;
    }

    public function show($id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
}
