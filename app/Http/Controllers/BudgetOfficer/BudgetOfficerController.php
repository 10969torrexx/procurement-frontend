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
use App\Http\Controllers\HistoryLogController;
use App\Http\Controllers\Department\DepartmentPagesController;
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
            //   ->whereRaw('pt.status = 2 or pt.status = 4 or pt.status = 5') 
              ->where(function ($query) {
                $query->where('pt.status', 2)
                   ->orWhere('pt.status', 4)
                   ->orWhere('pt.status', 5);
             })
              ->where("pt.project_category", 1)
              ->groupBy("pt.project_title")
              ->get();
  
            //   dd($ppmp);

        $item = DB::table("ppmps as p")
              ->select("pt.project_code as code","pt.id as pt_id","pt.project_title as title","p.*")
              ->join('project_titles as pt','pt.id','=','p.project_code')
              ->whereNull("p.deleted_at")
              ->where("p.campus",session('campus'))
            //   ->where("p.is_supplemental","=", 0)
              ->get();
        
        return view('pages.budgetofficer.view-ppmp', compact('ppmp','item'),
        [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs
        ]);
    } 

    public function IndicativeIndex(){
        
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" =>"Pending Indicatives"]
        ];
        
        $ppmp = DB::table("project_titles as pt")
              ->select("pt.*","ab.allocated_budget","ab.remaining_balance","fs.fund_source","d.department_name")
              ->join('ppmps as p','p.project_code','=','pt.id')
              ->join('departments as d','pt.department_id','=','d.id')
              ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
              ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
              ->where("pt.campus",session('campus'))
              ->whereNull("pt.deleted_at")
            //   ->whereRaw('pt.status = 2 or pt.status = 4 or pt.status = 5') 
              ->where(function ($query) {
                $query->where('pt.status', 2)
                   ->orWhere('pt.status', 4)
                   ->orWhere('pt.status', 5);
             })
              ->where("pt.project_category", 0)
              ->groupBy("pt.project_title")
              ->get();
  
            //   dd($ppmp);

        $item = DB::table("ppmps as p")
              ->select("pt.project_code as code","pt.id as pt_id","pt.project_title as title","p.*")
              ->join('project_titles as pt','pt.id','=','p.project_code')
              ->whereNull("p.deleted_at")
              ->where("p.campus",session('campus'))
            //   ->where("p.is_supplemental","=", 0)
              ->get();
        
        return view('pages.budgetofficer.view-ppmp', compact('ppmp','item'),
        [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs
        ]);
    } 

    public function SupplementalIndex(){
        
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" =>"Pending Supplementals"]
        ];
        
        $ppmp = DB::table("project_titles as pt")
              ->select("pt.*","ab.allocated_budget","ab.remaining_balance","fs.fund_source","d.department_name")
              ->join('ppmps as p','p.project_code','=','pt.id')
              ->join('departments as d','pt.department_id','=','d.id')
              ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
              ->join('fund_sources as fs','fs.id','=','ab.fund_source_id')
              ->where("pt.campus",session('campus'))
              ->whereNull("pt.deleted_at")
            //   ->whereRaw('pt.status = 2 or pt.status = 4 or pt.status = 5') 
              ->where(function ($query) {
                $query->where('pt.status', 2)
                   ->orWhere('pt.status', 4)
                   ->orWhere('pt.status', 5);
             })
              ->where("pt.project_category", 2)
              ->groupBy("pt.project_title")
              ->get();
  
            //   dd($ppmp);

        $item = DB::table("ppmps as p")
              ->select("pt.project_code as code","pt.id as pt_id","pt.project_title as title","p.*")
              ->join('project_titles as pt','pt.id','=','p.project_code')
              ->whereNull("p.deleted_at")
              ->where("p.campus",session('campus'))
            //   ->where("p.is_supplemental","=", 0)
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

                  ->where('p.project_code','=',$id)  

                  ->where(function ($query) {
                    $query->where('p.status', 2)
                       ->orWhere('p.status', 4)
                       ->orWhere('p.status', 5);
                    })

                  -> get();

        // dd($id);
        $category = DB::table('project_titles')
        ->where('id',$id)
        ->get();
        // dd($category);
        foreach($category as $cat){
            $category = $cat->project_category;
        }
        // dd($category);

        if($category == 0){
            (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$id,'Viewed Indicative','View',$request->ip());

            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
              ["link" => "/", "name" => "Home"],
              ["link" => "/budgetofficer/view_indicative","name" =>"Pending Indicatives"],
              ["name" =>"Indicatives"]
            ];
        }
        if($category == 1){
            (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$id,'Viewed PPMPs','View',$request->ip());

            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
              ["link" => "/", "name" => "Home"],
              ["link" => "/budgetofficer/view_ppmp","name" =>"Pending PPMPs"],
              ["name" =>"PPMP"]
            ];
        }
        if($category == 2){
            (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$id,'Viewed Supplemental','View',$request->ip());
            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
              ["link" => "/", "name" => "Home"],
              ["link" => "/budgetofficer/view_supplemental","name" =>"Pending Supplementals"],
              ["name" =>"Supplementals"]
            ];
            
        }
        return view('pages.budgetofficer.check_ppmp',compact('data','category'),
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
        (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$item_id,'Accepted an Item','Accept',$request->ip());

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
        (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$item_id,'Rejected an Item','Reject',$request->ip());

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
        (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$request->id,'Accepted PPMP','Accept',$request->ip());

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
        (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$request->id,'Rejected PPMP','Reject',$request->ip());

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

    public function accept_reject_all(Request $request){
        // dd($request->all());
        $budget = DB::table("project_titles as pt")
                ->select("ab.remaining_balance")
                ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
                ->whereNull("pt.deleted_at")
                ->whereNull("ab.deleted_at")
                ->where("pt.id",$request->id)
                ->get();
  
        if($request->value == 4){
          $itemcheck = DB::table("project_titles as pt")
                  ->select("p.estimated_price")
                  ->join('ppmps as p','p.project_code','=','pt.id')
                  ->whereNull("pt.deleted_at")
                  ->whereNull("p.deleted_at")
                  ->where(function ($query) {
                    $query->where('p.status', 5);
  
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
                    $query->where('p.status', 2)
                       ->orWhere('p.status', 4);
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
          foreach($budget as $budget){
            $total = $budget->remaining_balance;
          }
             
          if($request->value == 4){
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
  
              if($request->value == 4 ){
        (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$request->id,'Accepted PPMP','Accept',$request->ip());
                
                $timeline = DB::table("project_timeline")
                ->insert([
                'employee_id'=>session('employee_id'),
                'department_id'=>session('department_id'),
                'role'=>session('role'),
                'project_id'=>$request->id,
                'status'=>4,
                'campus'=>session('campus'),
                'remarks'=>"Your PPMP has been approved by the Budget Officer",
                'created_at' => Carbon::now()
                ]);
  
              }else{
        (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$request->id,'Rejected PPMP','Reject',$request->ip());

                $timeline = DB::table("project_timeline")
                ->insert([
                'employee_id'=>session('employee_id'),
                'department_id'=>session('department_id'),
                'role'=>session('role'),
                'project_id'=>$request->id,
                'status'=>5,
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
                            ->whereNull('deleted_at')
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

            (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),null,'Added Mandatory Expenditure','Add',$request->ip());

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

    public function editMandatoryExpenditure(Request $request){
        // dd($request->all());
        $id = (new AESCipher())->decrypt($request->id);

        $department_ids = DB::table('departments')->select('id')->where('campus',session('campus'))->whereNull('deleted_at')->orderBy('department_name')->get();
        $fund_source_ids = DB::table('fund_sources')->select('id')->whereNull('deleted_at')->orderBy('fund_source')->get();
        $years = DB::table('ppmp_deadline')->select('year','id')->where('campus',session('campus'))->whereNull('deleted_at')->orderBy('year')->get();
        $expenditure_ids = DB::table('mandatory_expenditures_list')->select('id')->whereNull('deleted_at')->orderBy('expenditure')->get();

        $response = DB::table("mandatory_expenditures as me")
                        ->select("me.department_id","me.fund_source_id","me.expenditure_id","me.price","me.year","mel.expenditure")
                        ->join('mandatory_expenditures_list as mel','mel.id','=','me.expenditure_id')
                        ->where('me.id', $id)
                        ->where('campus',session('campus'))
                        ->whereNull('me.deleted_at')
                        ->get();
                        // $data = DB::

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
    
    public function updateMandatoryExpenditure(Request $request){
        // dd($request->all());
            $department = $request->department;
            $fund_source = $request->fund_source;
            $year = $request->year;
            $expenditure = $request->expenditure;
            $price = $request->price;
            $id = (new AESCipher())->decrypt($request->id);
            // dd($id);

            $check = DB::table('mandatory_expenditures')
            ->where('id', $id)
            ->where('department_id', $department)
            ->where('fund_source_id', $fund_source)
            ->where('expenditure_id', $expenditure)
            ->where('price', $price)
            ->where('year', $year)
            ->whereNull('deleted_at')
            ->get();

            $check1 = DB::table('mandatory_expenditures')
                    ->where('id', $id)
                    ->where('department_id', $department)
                    ->where('fund_source_id', $fund_source)
                    ->where('expenditure_id', $expenditure)
                    // ->where('price', $price)
                    ->where('year', $year)
                    ->whereNull('deleted_at')
                    ->get();
            $check2 = DB::table('mandatory_expenditures')
                    ->where('department_id', $department)
                    ->where('fund_source_id', $fund_source)
                    ->where('expenditure_id', $expenditure)
                    // ->where('price', $price)
                    ->where('year', $year)
                    ->whereNull('deleted_at')
                    ->get();
            if(count($check) == 1){
                return response()->json([
                    'status' => 400, 
                    'message' => 'No Changes Made!',
                ]); 
            }else if((count($check) == 0 && count($check2) == 0) || count($check1) == 1){
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

                (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$id,'Updated Mandatory Expenditure','Update',$request->ip());

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
            }else if(count($check1) == 0 && count($check2) == 1){
                return response()->json([
                    'status' => 400, 
                    'message' => 'Mandatory Expenditure Already Exist!',
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
            (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$id,'Deleted Mandatory Expenditure','Delete',$request->ip());

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

    public function getDepartments(Request $request){
        try {
            $departments =  DB::table('departments')
            ->where('campus',session('campus'))
            ->whereNull('deleted_at')
            ->orderBy('department_name')
            ->get();
          return $departments;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function getFundSources(Request $request){
        try {
            $fundsources =  DB::table('fund_sources')
            ->whereNull('deleted_at')
            ->orderBy('fund_source')
            ->get();
        //   dd($departments);
          return $fundsources;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function getMandatoryExpenditures(Request $request){
        try {
            $expenditures =  DB::table('mandatory_expenditures_list')
                                // ->where('campus',session('campus'))
                                ->whereNull('deleted_at')
                                ->orderBy('expenditure')
                                ->get();
        //   dd($expenditures);
          return $expenditures;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    
    public function get_years(Request $request){
        try {
          $years = DB::table('ppmp_deadline')
                    ->select('year')
                    ->where('campus',session('campus'))
                    ->whereNull('deleted_at')
                    ->groupBy('year')
                    ->orderBy('year')
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
        $allocate_budget = DB::table('allocated__budgets')
                            ->select('departments.id', 'departments.department_name','allocated__budgets.*', 'fund_sources.fund_source')
                            ->join('departments', 'departments.id', '=', 'allocated__budgets.department_id')
                            ->join('fund_sources', 'fund_sources.id', '=', 'allocated__budgets.fund_source_id')
                            ->whereNull('allocated__budgets.deleted_at')
                            ->where("allocated__budgets.campus", session("campus"))
                            ->orderBy("departments.department_name")
                            ->get();
        $ppmp_deadline = DB::table('ppmp_deadline')->where('campus',session('campus'))->where('year',$date)->whereNull('deleted_at')->get();
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

    public function allocate_budget(Request $request){
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
                    'deadline_of_submission' => null,
                    'allocated_budget' => $allocated_budget,
                    'mandatory_expenditures' => $total_expenditures,
                    'remaining_balance' => $remaining_balance,
                    'campus' => session("campus"),
                    'created_at' => Carbon::now()
                ]);
            (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),null,'Allocated Budget','Allocate',$request->ip());

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

    public function delete_allocated_budget(Request $request){

      $id = (new AESCipher())->decrypt($request->id);
      $deadline = DB::table('allocated__budgets')
                  ->where('id',$id)
                  ->update([
                      'deleted_at' =>  Carbon::now()
                      ]
                  );
            (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$id,'Deleted Allocated Budget','Delete',$request->ip());

              if($deadline)
              {
                  return response()->json([
                      'status'=>200,
                      'message'=>'Allocated Budget Deleted Successfully.'
                  ]);
              }
              else
              {
                  return response()->json([
                      'status'=>404,
                      'message'=>'Not Found.'
                  ]);
              }
    }

    public function edit_allocated_budget(Request $request){
        $id = (new AESCipher())->decrypt($request->id);
        $response = DB::table("allocated__budgets as ab")
                        ->select("ab.procurement_type","ab.mandatory_expenditures","ab.allocated_budget","fs.fund_source","fs.id as fund_source_id","d.department_name","d.id as department_id","ab.year")
                        ->join('fund_sources as fs','ab.fund_source_id','=','fs.id')
                        ->join('departments as d','ab.department_id','=','d.id')
                        ->whereNull("ab.deleted_at") 
                        ->where("ab.id","=", $id)
                        ->get();
        $department_ids = DB::table('departments')
                            ->select('id')
                            ->where('campus',session('campus'))
                            ->whereNull('deleted_at')
                            ->orderBy('department_name')
                            ->get();
        $fund_source_ids = DB::table('fund_sources')
                            ->select('id')
                            ->whereNull('deleted_at')
                            ->orderBy('fund_source')
                            ->get();
        $years = DB::table('ppmp_deadline')
                    ->select('year','id')
                    ->where('campus',session('campus'))
                    ->whereNull('deleted_at')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
        // $type = DB::table('ppmp_deadline')->select('year','id')->whereNull('deleted_at')->get();
        // dd($years);
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
    }

    public function update_allocated_budget(Request $request){
        $update_type = (new AESCipher())->decrypt($request->update_type);
        $update_department = $request->update_department;
        $update_fund_source = $request->update_fund_source;
        $update_budget = $request->update_budget;
        $update_year = $request->update_year;
        $update_mandatory_expenditures = $request->update_mandatory_expenditures;
        $id = (new AESCipher())->decrypt($request->updateid);

        $remaining_balance = $update_budget - $update_mandatory_expenditures;

        $check = DB::table('allocated__budgets')
                    ->where('id', $id)
                    ->where('procurement_type', $update_type)
                    ->where('department_id', $update_department)
                    ->where('fund_source_id', $update_fund_source)
                    ->where('allocated_budget', $update_budget)
                    ->where('year', $update_year)
                    ->whereNull('deleted_at')
                    ->get();

        $check1 = DB::table('allocated__budgets')
                ->where('id', $id)
                ->where('procurement_type', $update_type)
                ->where('department_id', $update_department)
                ->where('fund_source_id', $update_fund_source)
                ->whereNull('deleted_at')
                ->get();
        $check2 = DB::table('allocated__budgets')
                ->where('procurement_type', $update_type)
                ->where('department_id', $update_department)
                ->where('fund_source_id', $update_fund_source)
                ->whereNull('deleted_at')
                ->get();

        if(count($check) == 1){
            return response()->json([
                'status' => 400, 
                'message' => 'No Changes Made!',
            ]); 
        }else if((count($check) == 0 && count($check2) == 0) || count($check1) == 1){
            $response = DB::table('allocated__budgets')
                            ->where('id',$id)
                            ->update([
                                'procurement_type'  => $update_type,
                                'department_id'  => $update_department,
                                'fund_source_id' =>  $update_fund_source,
                                'allocated_budget' =>  $update_budget,
                                'mandatory_expenditures' =>  $update_mandatory_expenditures,
                                'remaining_balance' =>  $remaining_balance,
                                'updated_at' =>  Carbon::now()
                            ]);
            (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$id,'Updated Allocated Budget','Update',$request->ip());

            if($response){
                return response()->json([
                    'status' => 200, 
                    'data' => $request->all(), 
                    'message' => 'Allocated Budget Updated Successfully!',
    
                ]);    
            } else{
                return response()->json([
                    'status' => 400, 
                    'message' => 'failed',
                ]);    
            }
        }else if(count($check1) == 0 && count($check2) == 1){
            return response()->json([
                'status' => 400, 
                'message' => 'Allocated Budget Already Exist!',
            ]); 
        }
    }

    public function save_ppmp_deadline(Request $request){
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
                
                (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),null,'Set '.(new GlobalDeclare)->project_category($Type).' Deadline','Save',$request->ip());
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
                (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$id,'Deleted Deadline','Delete',$request->ip());
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
   
    public function edit_deadline(Request $request){
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

    public function update_deadline(Request $request){
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
                (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),$id,'Updated Deadline','Update',$request->ip());

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

    public function get_DeadlineByYear(Request $request){
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

    public function get_procurement_type(Request $request){
        try {
            $type = (new AESCipher())->decrypt($request->type);
            $response = DB::table('ppmp_deadline')
                        ->select('year')
                        ->where('campus', session('campus'))
                        ->where('procurement_type', $type)
                        ->groupBy('year')
                        ->get();
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

    public function signed_ppmps_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Signed PPMP"]
        ];
        /** Torrexx Additionals
         * ! show upload ppmp from DepartmentPagesController
         * ? TODO enable access to DepartmentPagesController@show_upload_ppmp()
         * ? KEY import department pages controller
         */

         try {
            # get uplpaded ppmp
            $response = \DB::table('signed_ppmp')
                ->where('employee_id', session('employee_id'))
                ->where('department_id', session('department_id'))
                ->where('campus', session('campus'))
                ->whereNull('deleted_at')
                ->get();
            # return page
            return view('pages.budgetofficer.signed-ppmp', compact('response'));
        } catch (\Throwable $th) {
            //throw $th;
            return view('pages.error-500');
        }
    }

    public function my_par(){
        return view('pages.page-maintenance');
    }
    public function my_ics(){
        return view('pages.page-maintenance');
    }
}
