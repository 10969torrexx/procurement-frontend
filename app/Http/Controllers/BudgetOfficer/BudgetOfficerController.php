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
    public function ppmp_deadline_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Set PPMP Deadline"]
        ];
        // return view('pages.budgetofficer.index',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);

        $ppmp_deadlines = DB::table('ppmp_deadline')->where('campus',session('campus'))->whereNull('deleted_at')->get();
        // if(count($ppmp_deadlines)==0){
        //     session(['globalerror' => "Please set deadline first"]);
        // }else{
        //     Session::forget('globalerror');
        // }
            return view('pages.budgetofficer.ppmp_deadline_index',compact('ppmp_deadlines'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
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
          ["link" => "/", "name" => "Home"],["name" =>"Pending PPMPs"]
        ];
        
        $ppmp = DB::table("project_titles as pt")
                    ->select("pt.*","ab.allocated_budget","ab.remaining_balance","fs.fund_source","d.department_name")
                    ->join('ppmps as p','p.project_code','=','pt.id')
                    ->join('departments as d','pt.department_id','=','d.id')
                    ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
                    ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
                    ->where("pt.campus",session('campus'))
                    ->whereNull("pt.deleted_at")
                    ->whereNull("p.deleted_at")
                    //   ->whereRaw('pt.status = 2 or pt.status = 4 or pt.status = 5') 
                    ->where(function ($query) {
                        $query->where('pt.status', 2)
                            ->orWhere('pt.status', 4)
                            ->orWhere('pt.status', 5);
                    })
                    ->groupBy("pt.project_title")
                    ->where("p.is_supplemental","=", 0)
                    -> get();
              
        $item = DB::table("ppmps as p")
              ->select("pt.project_code as code","pt.id as pt_id","pt.project_title as title","p.*")
              ->join('project_titles as pt','pt.id','=','p.project_code')
              ->whereNull("p.deleted_at")
              ->where("p.campus",session('campus'))
              ->where("p.is_supplemental","=", 0)
              ->get();
        
        return view('pages.budgetofficer.view-ppmp', compact('ppmp','item'),
        [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs
        ]);
      } 

      public function showPPMP(Request $request){
        $aes = new AESCipher();
        $id = $aes->decrypt($request->project_code);
        $data = DB::table("ppmps as p")
                  ->select("pt.project_code as code","pt.status as projectStatus","pt.id as pt_id","pt.project_title as title","p.*")
                  ->join('project_titles as pt','pt.id','=','p.project_code')
                  ->whereNull("p.deleted_at")
                  ->whereNull("pt.deleted_at")
                  ->where("p.is_supplemental","=", 0) 
                  ->where('p.campus',session('campus'))
                  ->where(function ($query) {
                    $query->where('p.status', 2)
                       ->orWhere('p.status', 4)
                       ->orWhere('p.status', 5);
                    })
                  -> get();
        $pageConfigs = ['pageHeader' => true];
        // dd($data);
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],
          ["link" => "/budgetofficer/view_ppmp","name" =>"Pending PPMPs"],
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
        $count_disapproved = $request->count_disapproved;
        $estimated_price = $request->estimated_price;
        $sub_total = $request->sub_total;

        $ppmp = DB::table('project_titles as pt')
                ->select('ab.remaining_balance','ab.id','ab.allocated_budget')
                ->join('allocated__budgets as ab','pt.allocated_budget','ab.id')
                ->join('ppmps as p','p.project_code','=','pt.id')
                ->where('pt.id',$project_code)
                ->get();
                
        $allocated_budget = "";
        $remaining = "";
        $new_remaining = '';
        $allocated_budget_id = "";

        foreach($ppmp as $data){
            $remaining = $data->remaining_balance;
            $allocated_budget_id = $data->id;
            $allocated_budget = $data->allocated_budget;
        }

        $calc = ($remaining + $estimated_price);
        
        for($i = 0; $i < count($request->item_id);$i++){

            if($status[$i] == 4){
                    $changed = DB::table('ppmps as p')
                            ->where('id', $item_id[$i])
                            ->update([
                                'status' =>  $status[$i],
                                'remarks' =>  $remarks[$i]
                            ]);
                            
                    DB::table("allocated__budgets")
                            ->where("id", $allocated_budget_id)
                            ->update([
                                'remaining_balance' =>$calc,
                            ]);
            }else if($status[$i] == 5){
                    $changed = DB::table('ppmps as p')
                    ->where('id', $item_id[$i])
                    ->update([
                        'status' =>  $status[$i],
                        'remarks' =>  $remarks[$i]
                        ]
                    );

                    DB::table("allocated__budgets")
                        ->where("id", $allocated_budget_id)
                        ->update([
                            'remaining_balance' => $calc,
                        ]); 
                }  
        }
        if($changed){
            if($count_disapproved == 0){
                DB::table('project_titles')
                    ->where('id',$project_code)
                    ->update([
                        'status' =>  4,
                        ]
                    );
            }else{
                DB::table('project_titles')
                    ->where('id',$project_code)
                    ->update([
                        'status' =>  5,
                        ]
                );
            }
            return response()->json([
                'status' => 200, 
                ]); 
        }else{
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
        // return redirect("/view_ppmp");
    }

    
    public function addMandatoryExpenditure(Request $request){
        $department = $request->department;
        $fund_source = $request->fund_source;
        $year = $request->year;
        $expenditure = $request->expenditure;
        $price = $request->price;
        $expenditure_checker = DB::table('mandatory_expenditures')
                            ->where('department_id',$department)
                            ->where('expenditure_id',$expenditure)
                            ->where('fund_source_id',$fund_source)
                            ->where('campus',session('campus'))
                            ->where('year',$year)
                            ->get();
        if(count($expenditure_checker)>0){
            return response()->json([
                'status' => 400, 
                'message' => 'Expenditure Already Exist!',
                ]); 
        }else if(count($expenditure_checker) == 0){
            $expenditure = DB::table("mandatory_expenditures")
                    ->insert([
                        'department_id'=>  $department,
                        'fund_source_id'=>  $fund_source,
                        'year'=>  $year,
                        'expenditure_id'=>  $expenditure,
                        'price'=>  $price,
                        'campus' => session('campus'),
                        'created_at' => Carbon::now()
                    ]);
            if($expenditure){
                return response()->json([
                        'status' => 200, 
                        'message' => 'saved!',
                    ]);    
            } else{
                return response()->json([
                        'status' => 400, 
                        'message' => 'failed',
                    ]);    
            }
        }
    }
    public function editMandatoryExpenditure(Request $request)
    {
        // dd($request->all());
        $id = (new AESCipher())->decrypt($request->id);

        $department_ids = DB::table('departments')->select('id')->where('campus',session('campus'))->whereNull('deleted_at')->get();
        $fund_source_ids = DB::table('fund_sources')->select('id')->whereNull('deleted_at')->get();
        $years = DB::table('ppmp_deadline')->select('year','id')->where('campus',session('campus'))->whereNull('deleted_at')->get();
        $expenditure_ids = DB::table('mandatory_expenditures_list')->select('id')->whereNull('deleted_at')->get();

        $response = DB::table("mandatory_expenditures as me")
                        ->select("me.department_id","me.fund_source_id","me.expenditure_id","me.price","me.year","mel.expenditure")
                        ->join('mandatory_expenditures_list as mel','mel.id','=','me.expenditure_id')
                        ->where('me.id', $id)
                        ->where('campus',session('campus'))
                        ->whereNull('me.deleted_at')
                        ->get();
                //         $data = DB::
// dd($expenditure_ids);
            return response()->json([
                'status'=>200,
                'data'=> $response, 
                'department_ids'=> $department_ids,
                'fund_source_ids'=> $fund_source_ids,
                'years'=> $years,
                'expenditure_ids'=> $expenditure_ids,
                'id'=> (new AESCipher())->encrypt($id), 
            ]);
    }
    public function updateMandatoryExpenditure(Request $request)
    {
        // dd($request->all());
            $department = $request->department;
            $fund_source = $request->fund_source;
            $year = $request->year;
            $expenditure = $request->expenditure;
            $price = $request->price;
            $id = (new AESCipher())->decrypt($request->id);
            // dd($id);
            $response = DB::table('mandatory_expenditures')
                            ->where('id',$id)
                            ->update([
                                'department_id' => $department,
                                'fund_source_id' => $fund_source,
                                'expenditure_id' => $expenditure,
                                'year' => $year,
                                'price' => $price,
                                'updated_at' =>  Carbon::now()
                                ]
                            );

            if($response){
                return response()->json([
                        'status' => 200, 
                        'data' => $request->all(), 
                        'message' => 'Mandatory Expenditure Updated Successfully!',

                    ]);    
            } else{
                return response()->json([
                        'status' => 400, 
                        'message' => 'failed',
                    ]);    
            }
    }
    
    public function deleteMandatoryExpenditure(Request $request){

        $id = (new AESCipher())->decrypt($request->id);
        $response = DB::table('mandatory_expenditures')
                    ->where('id',$id)
                    ->update([
                        'deleted_at' =>  Carbon::now()
                        ]
                    );
                if($response)
                {
                    return response()->json([
                        'status'=>200,
                        'message'=>'Mandatory Expenditure Deleted Successfully.'
                    ]);
                }
                else
                {
                    return response()->json([
                        'status'=>404,
                        'message'=>'No Mandatory Expenditure Found.'
                    ]);
                }
    }


    public function mandatory_expenditures_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Mandatory Expenditures"]
        ];
        $mandatory_expenditures = DB::table('mandatory_expenditures')
                            ->select('mandatory_expenditures.*','d.department_name','me.expenditure', 'fs.fund_source')
                            ->join('departments as d', 'd.id', '=', 'mandatory_expenditures.department_id')
                            ->join('fund_sources as fs', 'fs.id', '=', 'mandatory_expenditures.fund_source_id')
                            ->join('mandatory_expenditures_list as me', 'me.id', '=', 'mandatory_expenditures.expenditure_id')
                            ->where('mandatory_expenditures.campus',session('campus'))
                            ->whereNull('mandatory_expenditures.deleted_at')
                            ->get();
        $ppmp_deadlines = DB::table('ppmp_deadline')->where('campus',session('campus'))->whereNull('deleted_at')->get();
            if(count($ppmp_deadlines)==0){
                session(['globalerror' => "Please set deadline first"]);
            }else{
                Session::forget('globalerror');
            }
            return view('pages.budgetofficer.mandatory-expenditures-index',compact('mandatory_expenditures'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
            ]);
    }

    public function getDepartments(Request $request)
    {
        try {
            $departments =  DB::table('departments')->where('campus',session('campus'))->whereNull('deleted_at')->get();
          return $departments;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function getFundSources(Request $request)
    {
        try {
            $fundsources =  DB::table('fund_sources')->whereNull('deleted_at')->get();
        //   dd($departments);
          return $fundsources;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function getMandatoryExpenditures(Request $request)
    {
        try {
            $expenditures =  DB::table('mandatory_expenditures_list')
                                // ->where('campus',session('campus'))
                                ->whereNull('deleted_at')
                                ->get();
        //   dd($expenditures);
          return $expenditures;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function get_years(Request $request)
    {
        try {
          $years = DB::table('ppmp_deadline')
                    ->select('year')
                    ->where('campus',session('campus'))
                    ->whereNull('deleted_at')
                    ->groupBy('year')
                    ->get();

          return $years;
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
    public function allocate_budget_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Allocate Budget"]
        ];
        $date = Carbon::now()->format('Y');
        $allocate_budget = DB::table('allocated__budgets')->select('departments.id', 'departments.department_name','allocated__budgets.*', 'fund_sources.fund_source')
                                    ->join('departments', 'departments.id', '=', 'allocated__budgets.department_id')
                                    ->join('fund_sources', 'fund_sources.id', '=', 'allocated__budgets.fund_source_id')
                                    ->whereNull('allocated__budgets.deleted_at')
                                    ->where("allocated__budgets.campus", session("campus"))
                                    ->get();
        $ppmp_deadline = DB::table('ppmp_deadline')->where('campus',session('campus'))->where('year',$date+1)->whereNull('deleted_at')->get();
        // $ppmp_deadlines = DB::table('ppmp_deadline')->where('campus',session('campus'))->whereNull('deleted_at')->get();
        if(count($ppmp_deadline)==0){
            session(['globalerror' => "Please set deadline first"]);
        }else{
            Session::forget('globalerror');
        }
        // dd($ppmp_deadline);
        // if(count($ppmp_deadline)==0){
        //     session(['globalerror' => "Please set a deadline first before allocating budget!"]);
        // }
            return view('pages.budgetofficer.allocatebudget',compact('ppmp_deadline','allocate_budget'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
            ]); 
    }

    public function allocate_budget(Request $request)
    {
        try {
            $type = (new AESCipher)->decrypt($request->type);
            $department_id = $request->department;
            $fund_source_id = $request->fund_source;
            $year = $request->year;
            $deadline_of_submission = $request->end_date;
            $allocated_budget = $request->budget;

            $total_expenditures = DB::table('mandatory_expenditures')
                                    ->where('department_id',$department_id)
                                    ->where('fund_source_id',$fund_source_id)
                                    ->where('year',$year)
                                    ->where('campus',session('campus'))
                                    ->whereNull('deleted_at')
                                    ->sum('price');
            $department_checker = DB::table('allocated__budgets')
                                    ->where('department_id',$department_id,'and')
                                    ->where('procurement_type',$type,'and')
                                    ->where('fund_source_id',$fund_source_id,'and')
                                    ->where('campus',session('campus'))
                                    ->whereNull('deleted_at')
                                    ->where('year',$year)
                                    ->get();
            if(count($department_checker) >= 1){
                return response()->json([
                        'status' => 400, 
                        'message' => 'Budget for the selected Department and Fund Source is already allocated!',
                        ]);  
            }else if(count($department_checker) == 0){
                $remaining_balance = $allocated_budget-$total_expenditures;

                $allocate_budget= DB::table('allocated__budgets')
                    ->insert([
                    'department_id' => $department_id,
                    'procurement_type' => $type,
                    'fund_source_id' => $fund_source_id,
                    'year' => $year,
                    'deadline_of_submission' => $deadline_of_submission,
                    'allocated_budget' => $allocated_budget,
                    'mandatory_expenditures' => $total_expenditures,
                    'remaining_balance' => $remaining_balance,
                    'campus' => session("campus"),
                    'created_at' => Carbon::now()
                ]);
                if($allocate_budget){
                    return response()->json([
                            'status' => 200, 
                            'message' => 'Budget Allocated Successfully!',
                        ]);    
                } else{
                    return response()->json([
                            'status' => 400, 
                            'message' => 'Something went wrong!',
                        ]);    
                } 
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400, 
                'message' => 'Failed'.$th
            ]);    
        }
    }
    public function edit_allocated_budget(Request $request)
    {
        $id = (new AESCipher())->decrypt($request->id);
// dd($id);
        // $departments = DB::table('departments')->get('departments.id');
        // $fund_sources = DB::table('fund_sources')->get('fund_sources.id');
        // $allocated_budget = DB::table('allocated__budgets')->where('id', $id)->get();

        $response = DB::table("allocated__budgets as ab")
                        ->select("ab.procurement_type","ab.allocated_budget","fs.fund_source","fs.id as fund_source_id","d.department_name","d.id as department_id","ab.year")
                        ->join('fund_sources as fs','ab.fund_source_id','=','fs.id')
                        ->join('departments as d','ab.department_id','=','d.id')
                        ->whereNull("ab.deleted_at") 
                        ->where("ab.id","=", $id)
                        ->get();
        $department_ids = DB::table('departments')->select('id')->whereNull('deleted_at')->get();
        $fund_source_ids = DB::table('fund_sources')->select('id')->whereNull('deleted_at')->get();
        $years = DB::table('ppmp_deadline')->select('year','id')->whereNull('deleted_at')->get();
        // $type = DB::table('ppmp_deadline')->select('year','id')->whereNull('deleted_at')->get();
        // dd($response);
        if($response){
            return response()->json([
                    'status' => 200, 
                    'data'=> $response,
                    'department_ids'=> $department_ids,
                    'fund_source_ids'=> $fund_source_ids,
                    'years'=> $years,
                ]);    
        } else{
            return response()->json([
                    'status' => 400, 
                    'message' => 'Something went wrong!',
                ]);    
        } 

        // $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/budget/edit/".$id1,[
        //     'id' => $id1,
        //     'message' => $id1,

        // ])->json();
        // //   dd($response);

        // return $response;
    }
    public function save_ppmp_deadline(Request $request)
    {
        // dd($request->all());
        try {
            $Type = (new AESCipher)->decrypt($request->type);
            $Year = (new AESCipher)->decrypt($request->year);
            $StartDate = $request->start_date;
            $EndDate = $request->end_date;
            // return $Year;
            $deadlinechecker = DB::table('ppmp_deadline')
                                ->where('procurement_type',$Type)
                                ->where('year',$Year)
                                ->where('campus',session('campus'))
                                ->whereNull('deleted_at')
                                ->get();
            if(count($deadlinechecker) == 1){
                return response()->json([
                        'status' => 400, 
                        'message' => 'Deadline for '.$Type.' for the Year '.$Year.' is already set!',
                        ]);  
            }else if(count($deadlinechecker) == 0){
                $deadline= DB::table('ppmp_deadline')
                                    ->insert([
                                        'procurement_type'=>  $Type,
                                        'year'=>  $Year,
                                        'campus'=>  session('campus'),
                                        'start_date'=>  $StartDate,
                                        'end_date'=>  $EndDate,
                                        'created_at' => Carbon::now()
                                    ]);
                if($deadline){
                    return response()->json([
                            'status' => 200, 
                            'message' => 'Deadline Set Successfully!',
                        ]);    
                } else{
                    return response()->json([
                            'status' => 400, 
                            'message' => 'Failed',
                        ]);    
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400, 
                'message' => 'Failed'.$th
            ]);    
        }
    }

    public function delete_deadline(Request $request){

        $id = (new AESCipher())->decrypt($request->id);
        $deadline = DB::table('ppmp_deadline')
                    ->where('id',$id)
                    ->update([
                        'deleted_at' =>  Carbon::now()
                        ]
                    );
                if($deadline)
                {
                    return response()->json([
                        'status'=>200,
                        'message'=>'Deadline Deleted Successfully.'
                    ]);
                }
                else
                {
                    return response()->json([
                        'status'=>404,
                        'message'=>'No Deadline Found.'
                    ]);
                }
    }
   
    public function edit_deadline(Request $request)
    {
        // dd($request->all());
        $id = (new AESCipher())->decrypt($request->id);
        $deadline = DB::table('ppmp_deadline')->where('id', $id)->get();
        // dd($deadline);
            return response()->json([
                'status'=>200,
                'deadline'=> $deadline, 
                'id'=> (new AESCipher())->encrypt($id), 
            ]);
    }

    public function update_deadline(Request $request)
    {
        // dd($request->all());
            $update_type = (new AESCipher())->decrypt($request->update_type);
            $update_year = (new AESCipher())->decrypt($request->update_year);
            $update_start_date = $request->update_start_date;
            $update_end_date = $request->update_end_date;
            $updateid = $request->updateid;
            $id = (new AESCipher())->decrypt($updateid);
           
            $deadlinechecker = DB::table('ppmp_deadline')
                                ->where('procurement_type',$update_type)
                                ->where('year',$update_year)
                                ->where('campus',session('campus'))
                                ->where('start_date',$update_start_date)
                                ->where('end_date',$update_end_date)
                                ->whereNull('deleted_at')
                                ->where('id',$id)
                                ->get();              
            $deadlinechecker1 = DB::table('ppmp_deadline')
                                ->where('procurement_type',$update_type)
                                ->where('year',$update_year)
                                ->where('campus',session('campus'))
                                ->whereNull('deleted_at')
                                ->get();
            $deadlinechecker2 = DB::table('ppmp_deadline')
                                ->where('procurement_type',$update_type)
                                ->where('year',$update_year)
                                ->where('campus',session('campus'))
                                ->where('id',$id)
                                ->whereNull('deleted_at')
                                ->get();
            if(count($deadlinechecker) == 1){
                return response()->json([
                        'status' => 400, 
                        'message' => 'No Changes Made!',
                    ]);  
            }else if(count($deadlinechecker1) == 1 && count($deadlinechecker2) == 0){
                return response()->json([
                        'status' => 400, 
                        'message' => 'Deadline for '.$update_type.' for the Year '.$update_year.' is already set!',
                    ]); 
            }else if(count($deadlinechecker1) == 0 || count($deadlinechecker2) == 1){
                $response = DB::table('ppmp_deadline')
                        ->where('id',$id)
                        ->update([
                            'procurement_type' => $update_type,
                            'year' => $update_year,
                            'start_date' => $update_start_date,
                            'end_date' => $update_end_date,
                            'updated_at' =>  Carbon::now()
                            ]
                        );
                if( $response)
                {
                    return response()->json([
                    'status' => 200, 
                    'message' => 'Deadline Updated Successfully!',
                ]);    
                }
                else{
                    return response()->json([
                    'status' => 400, 
                    'message' => 'Please select date!',
                    ]); 
                }
            }
    }
    public function get_DeadlineByYear(Request $request)
    {
        try {
            $year = $request->year;
            $response = DB::table('ppmp_deadline')
                        ->select('end_date')
                        ->where('year', $year)
                        ->get();
            if($response){
                return response()->json([
                    'status' => 200,
                    'deadline' =>$response,
                ]);
            }
            return response()->json([
                'status' => 400,
                'message' => 'Server Error'
            ]);
         
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
    public function get_procurement_type(Request $request)
    {
        try {
        //   dd($request->all());

            $type = (new AESCipher())->decrypt($request->type);
        // $response = Http::withToken(env('Auth_HRMIS_Token'))->post(env('APP_HRMIS_API'). "/api/auth/employeelist", [
        //     'department' => $department,
        //   ])->json();
            $response = DB::table('ppmp_deadline')
                        ->select('year')
                        ->where('campus', session('campus'))
                        ->where('procurement_type', $type)
                        ->groupBy('year')
                        ->get();
            // dd($response);
            // if(count($response)==0){
            //     session(['globalerror' => "Please set deadline first for ".$type]);
            // }else{
            //     Session::forget('globalerror');
            // }
            if($response){
                return response()->json([
                    'status' => 200,
                    'years' =>$response,
                ]);
            }
            return response()->json([
                'status' => 400,
                'message' => 'Server Error'
            ]);
         
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
    public function my_par(){
        return view('pages.page-maintenance');
    }
    public function my_ics(){
        return view('pages.page-maintenance');
    }
}
