<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
              ["link" => "/", "name" => "Home"],["name" => "Add User"]
            ];
            $users = DB::table('users as u')
                        ->select('u.*','d.department_name')
                        ->join('departments as d', 'd.id', '=', 'u.department_id')
                        ->whereNotNull('u.email')
                        ->whereNull('u.deleted_at')
                        ->orderBy('u.name')
                        ->get();
            // dd($users);
            
            return view('pages.superadmin.index',compact('users'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]);
    }

    public function departments_index()
    {
            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
              ["link" => "/", "name" => "Home"],["name" => "Add Department"]
            ];
            $departments = DB::table('departments as d')
                            ->select('d.department_name','d.campus','d.description','d.id','u.name as department_head','us.name as immediate_supervisor')
                            ->join('users as u', 'u.id', '=', 'd.department_head')
                            ->join('users as us', 'us.id', '=', 'd.immediate_supervisor')
                            ->whereNull('u.deleted_at')
                            ->orderBy('d.department_name')
                            ->get();

            // $departments =  Http::withToken(session('token'))->get(env('APP_API'). "/api/departments/getDepartments")->json();
            // dd($departments);
            // $error="";
            // if($departments['status']==400){
            //     $error=$departments['message'];
            // }

            return view('pages.superadmin.departments',compact('departments'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]);

    }
    public function getDepartmentsByCampus(Request $request)
    {
        // dd($request->campus);
        try {
            $campus = (new AESCipher())->decrypt($request->campus);
            $departments =  DB::table('departments')
                                ->where('campus',$campus)
                                ->whereNull('deleted_at')  
                                ->orderBy('department_name')
                                ->get();
            // dd($departments);
            return $departments;
            
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function departments()
    {
            $departments =  Http::withToken(session('token'))->get(env('APP_API'). "/api/departments/getDepartments")->json();
            // dd($departments);
            return $departments;

    }
    public function fund_sources_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Fund Sources"]
        ];

        $fund_sources = DB::table('fund_sources')
                            ->whereNull('deleted_at')
                            ->orderBy('fund_source')
                            ->get();

        return view('pages.superadmin.fund-sources-index',compact('fund_sources'), [
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
            // 'error' => $error,
        ]);
    }
    public function addFundSource(Request $request){
        // dd($request->all());
        $fundsource = $request->fundsource;

        $check = DB::table('fund_sources')
                    ->where('fund_source', $fundsource)
                    ->whereNull('deleted_at')
                    ->get();
        if(count($check)==0){
            $response= DB::table('fund_sources')
                    ->insert([
                        'fund_source' => $fundsource,
                        'created_at' => Carbon::now()
                    ]);
            if($response){
                return response()->json([
                    'status' => 200, 
                    'message' => 'Fund Source Saved Successfully!',
                ]);    
            } else{
                return response()->json([
                    'status' => 400, 
                    'message' => 'Something Went Wrong',
                ]);    
            }
        }else{
            return response()->json([
                'status' => 400, 
                'message' => 'Fund Source Already Exist!',
            ]);    
        }
    }
    public function editFundSource(Request $request)
    {
        $id = (new AESCipher())->decrypt($request->id);

        $fund_source = DB::table('fund_sources')->where('id', $id)->get();
        return response()->json([
            'status'=>200,
            'fund_source'=> $fund_source, 
            'id'=> $request->id, 
        ]);
    }
    public function updateFundSource(Request $request){
            $fundsource = $request->fundsource;
            $id = (new AESCipher())->decrypt($request->id);
            $check = DB::table('fund_sources')
                        ->where('fund_source', $fundsource)
                        ->whereNull('deleted_at')
                        ->get();
            $check1 = DB::table('fund_sources')
                        ->where('fund_source', $fundsource)
                        ->where('id', $id)
                        ->whereNull('deleted_at')
                        ->get();
            if(count($check) == 1 && count($check1) == 0){
                return response()->json([
                    'status' => 400, 
                    'message' => 'Fund Source Already Exist!',
                ]); 
            }else if(count($check1) == 1){
                return response()->json([
                    'status' => 400, 
                    'message' => 'No Changes Made!',
                ]); 
            }else if(count($check) == 0 && count($check1) == 0){
                $response = DB::table('fund_sources')
                                ->where('id',$id)
                                ->update([
                                    'fund_source' => $fundsource,
                                    'updated_at' =>  Carbon::now()
                                ]);
                        if($response){
                            return response()->json([
                                    'status' => 200, 
                                    'message' => 'Fund Source Updated Successfully!',
                                ]);    
                        } else{
                            return response()->json([
                                    'status' => 400, 
                                    'message' => 'Failed!',
                                ]);    
                        }
            }
    }
    public function deleteFundSource(Request $request){
        $id = (new AESCipher())->decrypt($request->id);
        $fund_source = DB::table('fund_sources')->where('id',$id)->delete();
        if($fund_source)
        {
            return response()->json([
                'status'=>200,
                'message'=>'Fund Source Deleted Successfully!'
            ]);
        }
        else
        {
            return response()->json([
                'status'=>400,
                'message'=>'No Account Found!'
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
                            ->whereNull('mandatory_expenditures.deleted_at')
                            ->get();
        
            return view('pages.budgetofficer.mandatory-expenditures-index',compact('mandatory_expenditures'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
            ]);
    }
    public function addMandatoryExpenditure(Request $request){
        // dd($request->all());
        $expenditure = $request->expenditure;
        $price = $request->price;

        $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/expenditure/add_MandatoryExpenditure",[
            'expenditure' => $expenditure,
            'price' => $price,
            ])->json();
            // dd($response);
            return $response;
    }
    public function editMandatoryExpenditure(Request $request)
    {
        // dd($request->all());
        $id = (new AESCipher())->decrypt($request->id);

        $response = DB::table('mandatory_expenditures')->where('id', $id)->get();
        dd($response);
        return response()->json([
            'status'=>200,
            'expenditure'=> $response, 
            'id'=> $request->id, 
        ]);
        
        // $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/expenditure/edit_MandatoryExpenditure/".$id1,[
        //     'id' => $id1,
        // ])->json();
        // dd($response);
        // return $response;
    }
    public function updateMandatoryExpenditure(Request $request)
    {
        // dd($request->all());
            $expenditure = $request->expenditure;
            $price = $request->price;
            $id = (new AESCipher())->decrypt($request->id);
            // dd($id);
            $response = Http::withToken(session('token'))->put(env('APP_API'). "/api/expenditure/update_MandatoryExpenditure/".$id,[
            'expenditure' => $expenditure,
            'price' => $price,
            'id' => $id,
            ])->json();
            // dd($response);
            return $response;
    }
    public function deleteMandatoryExpenditure(Request $request){
        $id = (new AESCipher())->decrypt($request->id);
        $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/expenditure/delete_MandatoryExpenditure/".$id,[
            'id' => $id,
        ])->json();
        return $response;
    }
    public function setDeadlineIndex(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Set PPMP Deadline"]
        ];
        // return view('pages.budgetofficer.index',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);

        $ppmp_deadlines = DB::table('ppmp_deadline')->whereNull('deleted_at')->get();

            return view('pages.budgetofficer.ppmp_deadline_index',compact('ppmp_deadlines'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]);
    }
    public function set_deadline(Request $request)
    {
            $Type = (new AESCipher)->decrypt($request->type);
            $Year = (new AESCipher)->decrypt($request->year);
            $StartDate = $request->start_date;
            $EndDate = $request->end_date;
            $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/ppmpDeadline/store",[
            'type' => $Type,
            'year' => $Year,
            'start_date' => $StartDate,
            'end_date' => $EndDate,
            ])->json();
            // dd($response);
            return $response;
    }
    public function edit_deadline(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $id1 = (new AESCipher())->decrypt($id);

        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/ppmpDeadline/edit/".$id1,[
            'id' => $id1,
        ])->json();
        // dd($response);
        return $response;
    }
    public function update_deadline(Request $request)
    {
            $update_type = $request->update_type;
            $update_year = $request->update_year;
            $update_start_date = $request->update_start_date;
            $update_end_date = $request->update_end_date;
            $updateid = $request->updateid;
            $id = (new AESCipher())->decrypt($updateid);
            
            $response = Http::withToken(session('token'))->put(env('APP_API'). "/api/ppmpDeadline/update/".$id,[
            'updateid' => $id,
            'update_type' => $update_type,
            'update_year' => $update_year,
            'update_start_date' => $update_start_date,
            'update_end_date' => $update_end_date,
            ])->json();
            // dd($response);
            return $response;
    }
    public function delete_deadline(Request $request){
        $id = $request->id;
        $id1 = (new AESCipher())->decrypt($id);
        $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/ppmpDeadline/delete/".$id1,[
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
    public function allocateBudgetIndex(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Allocate Budget"]
        ];
        // return view('pages.budgetofficer.allocatebudget',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);

        
        $ppmp_deadline =  Http::withToken(session('token'))->get(env('APP_API'). "/api/allocateBudget/allocated_budget")->json();
            dd($ppmp_deadline);
            $error="";
            if($ppmp_deadline['status']==400){
                $error=$ppmp_deadline['message'];
            }

            return view('pages.superadmin.allocatebudget',compact('ppmp_deadline'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                'error' => $error,
                'allocated_budgets' => $ppmp_deadline['data'],
            ]); 
    }
    public function PurchaseRequestIndex(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Purchase Request"]
        ];
        // return view('pages.budgetofficer.allocatebudget',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);

        
        $items =  Http::withToken(session('token'))->get(env('APP_API'). "/api/purchase_request/getItems")->json();
            // dd($items);
            $error="";
            if($items['status']==400){
                $error=$items['message'];
            }

            return view('pages.superadmin.purchase-request-index',compact('items'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                'error' => $error,
            ]); 
    }
    public function getYears(Request $request)
    {
        try {
            // $department = $request->department;
        // $response = Http::withToken(env('Auth_HRMIS_Token'))->post(env('APP_HRMIS_API'). "/api/auth/employeelist", [
        //     'department' => $department,
        //   ])->json();
          $response =  Http::withToken(session('token'))->get(env('APP_API'). "/api/years/get_years")->json();

        //   dd($response);
          return $response;
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
    public function getDeadlineByYear(Request $request)
    {
        try {
        //   dd($request->all());

            $year = $request->year;
        // $response = Http::withToken(env('Auth_HRMIS_Token'))->post(env('APP_HRMIS_API'). "/api/auth/employeelist", [
        //     'department' => $department,
        //   ])->json();
          $response =  Http::withToken(session('token'))->get(env('APP_API'). "/api/years/get_DeadlineByYear",[
            'year' => $year,
          ])->json();

        //   dd($response);
          return $response;
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
    public function getYearByType(Request $request)
    {
        // dd($request->all());
        try {
            $type = (new AESCipher)->decrypt($request->type);
            $response =  Http::withToken(session('token'))->get(env('APP_API'). "/api/years/get_YearByType",[
                'type' => $type,
            ])->json();
        //   dd($response);
            return $response;
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
    public function get_Departments(Request $request)
    {
        // dd($request->all());
        try {
            $departments =  Http::withToken(session('token'))->get(env('APP_API'). "/api/department/get_Departments")->json();
        //   dd($departments);
          return $departments;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function get_FundSources(Request $request)
    {
        try {
            $fundsources =  Http::withToken(session('token'))->get(env('APP_API'). "/api/fundsource/get_FundSources")->json();
        //   dd($departments);
          return $fundsources;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function get_MandatoryExpenditures(Request $request)
    {
        try {
            $expenditures =  Http::withToken(session('token'))->get(env('APP_API'). "/api/expenditures/get_MandatoryExpenditures")->json();
        //   dd($expenditures);
          return $expenditures;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function save_allocate_budget(Request $request){
        //   dd($request->all());
              $year = $request->year;
              $type = (new AESCipher)->decrypt($request->type);
              $deadline_of_submission = $request->end_date;
              $department = $request->department;
              $fund_source = $request->fund_source;
              $budget = $request->budget;
              $mandatory_expenditures = $request->mandatory_expenditures;
            //   $mandatory_expenditures = serialize($mandatory_expenditures_array);
            //   $mandatory_expenditures1 = unserialize($mandatory_expenditures);
            //   dd($mandatory_expenditures1);
              $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/budget/save_allocate_budget",[
              'year' => $year,
              'type' => $type,
              'deadline_of_submission' => $deadline_of_submission,
              'department_id' => $department,
              'fund_source_id' => $fund_source,
              'allocated_budget' => $budget,
              'mandatory_expenditures' => $mandatory_expenditures,
              ])->json();
            //   dd($response);
    
            return $response;
      }
      public function delete_allocated_budget(Request $request){
        // dd($request->all());
  
      $id = $request->id;
      $id1 = (new AESCipher())->decrypt($id);
      $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/budget/delete_allocated_budget/".$id1,[
          'id' => $id1,
      ])->json();
      // dd ($response);
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
  
    public function edit_allocated_budget(Request $request)
    {
        $id = $request->id;
        $id1 = (new AESCipher())->decrypt($id);
      //   dd($id1);
  
        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/budget/edit_allocated_budget/".$id1,[
            'id' => $id1,
            'message' => $id1,
  
        ])->json();
        // dd($response);
  
        return $response;
    }
  
    public function update_allocated_budget(Request $request)
      {
              $update_type = (new AESCipher)->decrypt($request->update_type);
              $update_department = $request->update_department;
              $update_fund_source = $request->update_fund_source;
              $update_budget = $request->update_budget;
              $update_mandatory_expenditures = $request->update_mandatory_expenditures;
              $updateid = $request->updateid;
              $id = $updateid;
              
              $response = Http::withToken(session('token'))->put(env('APP_API'). "/api/budget/update_allocated_budget/".$id,[
              'updateid' => $id,
              'update_type' => $update_type,
              'update_department' => $update_department,
              'update_fund_source' => $update_fund_source,
              'update_budget' => $update_budget,
              'update_mandatory_expenditures' => $update_mandatory_expenditures,
              ])->json();
              // dd($response);
              return $response;
          
      }
      public function getUsers(Request $request)
    {
        // dd($request->all());
        try {
            $campus = (new AESCipher)->decrypt($request->campus);
            $response = DB::table('users')
                            ->where('campus',$campus)
                            ->whereNull('deleted_at')
                            ->whereNotNull('employee_id')
                            ->orderBy('name')
                            ->get();
          return $response;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function getDepartmentHeads(Request $request)
    {
        // dd($request->all());
        try {
            $campus = (new AESCipher)->decrypt($request->campus);
            $response = DB::table('users')
            ->where('campus',$campus)
            ->whereNotNull('employee_id')
            ->get();
            // $response =   Http::withToken('800|4fVkxADAZJkcqHZR94yYLLq5itCN3kXCXwoQAevA')->post("http://192.168.0.11/hrmis/api/department/departmentheadslist", [ 
            //     'campus' =>  $campus 
            //     ])->json();
        //   dd($response);
          return $response;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function getSupervisors(Request $request)
    {
        // dd($request->all());
        try {
            $campus = $request->campus;
            $response =   Http::withToken('800|4fVkxADAZJkcqHZR94yYLLq5itCN3kXCXwoQAevA')->post("http://192.168.0.11/hrmis/api/department/departmentsupervisorlist", [ 
                'campus' =>  $campus 
                ])->json();
        //   dd($response);
          return $response;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    #THIS WILL PASS THE CAMPUS VALUE TO HRMIS API TO GET THE EMPLOYEE LIST BELONG TO THAT CAMPUS
    public function pass(Request $request)
    {
        // try {
        //     $campus = $request->campus;
        // $response = Http::withToken(env('Auth_HRMIS_Token'))->post(env('APP_HRMIS_API'). "/api/auth/employeelist", [
        //     'campus' => $campus,
        //   ])->json();
        // //   dd($response);
        //   return $response;
        // } catch (\Throwable $th) {
        //     dd($th);
        // }

        $campus = (new AESCipher)->decrypt($request->campus);
        $response = DB::connection("hrmis")
            ->table("employee")
            ->whereNull("deleted_at")
            ->where("Campus", $campus)
            ->orderBy("LastName")
            ->orderBy("FirstName")
            ->get();
       
        return response()->json(["data"=>$response]);
        
    }

    public function store(Request $request){
            // dd( $request->all());
        
            $employee_info = $request->employee;
            $info = explode('*',$employee_info);
            // dd( $info);
            
            $middle_name = $info[1];
            // dd( $middle_initial);

            $first_name = $info[0];
            $middle_initial = substr($middle_name, 0, 1).'.';
            $last_name = $info[2];

            $full_name = $first_name.' '.$middle_initial.' '.$last_name;
            // dd( $full_name);
            $email = $info[3];
            // $department_id = $info[4];
            $employee_id = $info[5];

            $campus = (new AESCipher())->decrypt($request->campus);
            $role = (new AESCipher())->decrypt($request->role);
            $department = $request->department;

            $check = DB::table('users')
                        ->where('email', $email)
                        ->where('campus', $campus)
                        ->get();
            if(count($check)==0){
                $response= DB::table('users')
                ->insert([
                    'campus' => $campus,
                    'role' => $role,
                    'name' => $full_name,
                    'email' => $email,
                    'department_id' => $department,
                    'employee_id' => $employee_id,
                    'created_at' => Carbon::now()
                ]);
                if($response){
                    return response()->json([
                        'status' => 200, 
                        'message' => 'User Saved Successfully!',
                    ]);    
                } else{
                    return response()->json([
                        'status' => 400, 
                        'message' => 'Something Went Wrong',
                    ]);    
                }
            }else{
                return response()->json([
                    'status' => 400, 
                    'message' => 'User Already Exist!',
                ]); 
            }
    }

    public function saveDepartment(Request $request){
            // dd($request->all());
            $campus = (new AESCipher())->decrypt($request->campus);
            $department_name = $request->department_name;
            $department_description = $request->department_description;
            $department_head = $request->department_head;
            $immediate_supervisor = $request->immediate_supervisor;

            $check = DB::table('departments')
                        ->where('department_name', $department_name)
                        ->where('campus', $campus)
                        ->get();
            if(count($check)==0){
                $response= DB::table('departments')
                ->insert([
                    'campus' => $campus,
                    'department_name' => $department_name,
                    'description' => $department_description,
                    'department_head' => $department_head,
                    'immediate_supervisor' => $immediate_supervisor,
                    'created_at' => Carbon::now()
                ]);
                if($response){
                    return response()->json([
                        'status' => 200, 
                        'message' => 'Department Saved Successfully!',
                    ]);    
                } else{
                    return response()->json([
                        'status' => 400, 
                        'message' => 'Something Went Wrong',
                    ]);    
                }
            }else{
                return response()->json([
                    'status' => 400, 
                    'message' => 'Department Already Exist!',
                ]);  
            }
    }

    public function delete(Request $request){
        try {
            $id = (new AESCipher())->decrypt($request->id);
            $user = DB::table('users')->where('id',$id)->delete();
            if($user)
            {
                return response()->json([
                    'status'=>200,
                    'message'=>'Account Deleted Successfully!'
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>400,
                    'message'=>'No Account Found!'
                ]);
            }
        } catch (\Throwable $th) {
            dd('AdminContoller '.$th);
        }
    }
    public function deleteDepartment(Request $request){
        $id = (new AESCipher())->decrypt($request->id);
        $department = DB::table('departments')->where('id',$id)->delete();
        if($department){
            return response()->json([
                'status'=>200,
                'message'=>'Department Deleted Successfully!'
            ]);
        }else{
            return response()->json([
                'status'=>400,
                'message'=>'No Department Found!'
            ]);
        }
    }
    public function edit(Request $request)
    {
        $id = (new AESCipher())->decrypt($request->id);

        $user = DB::table('users')->where('id', $id)->get();
        // dd($user);
        return response()->json([
            'status'=>200,
            'user'=> $user, 
            'id'=> $request->id, 
        ]);
    }
    public function editDepartment(Request $request){
        // dd($request->all());
        $id = (new AESCipher())->decrypt($request->id);

        $department = DB::table('departments')->where('id', $id)->get();
        // dd($campus);
        return response()->json([
            'status'=>200,
            'department'=> $department, 
            'id'=> $request->id, 
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->all());
            $updatename = $request->updatename;
            $updatedepartment = $request->updatedepartment;
            $updateselectcampus = (new AESCipher())->decrypt($request->updateselectcampus);
            $updateselectrole = (new AESCipher())->decrypt($request->updateselectrole);
            $updateid = $request->updateid;

            $id = (new AESCipher())->decrypt($updateid);
            
            $response = DB::table('users')
                        ->where('id',$id)
                        ->update([
                            'name' => $updatename,
                            'campus' => $updateselectcampus,
                            'role' => $updateselectrole,
                            'department_id' => $updatedepartment,
                            'updated_at' =>  Carbon::now()
                            ]
                        );

                if($response){
                return response()->json([
                        'status' => 200, 
                        'message' => 'User Updated Successfully!',

                    ]);    
                } else{
                return response()->json([
                        'status' => 400, 
                        'message' => 'failed',
                    ]);    
                }
    }

    public function updateDepartment(Request $request){
            // dd($request->all());
            $update_campus = (new AESCipher())->decrypt($request->update_campus);
            $update_department_name = $request->update_department_name;
            $update_department_description = $request->update_department_description;
            $update_department_head = $request->update_department_head;
            $update_immediate_supervisor = $request->update_immediate_supervisor;
            $update_id = $request->update_id;
            
            $id = (new AESCipher())->decrypt($update_id);
            $response = DB::table('departments')
                        ->where('id',$id)
                        ->update([
                            'campus' => $update_campus,
                            'department_name' => $update_department_name,
                            'description' => $update_department_description,
                            'department_head' => $update_department_head,
                            'immediate_supervisor' => $update_immediate_supervisor,
                            'updated_at' =>  Carbon::now()
                            ]
            );
            if($response){
                return response()->json([
                        'status' => 200, 
                        'message' => 'Department Updated Successfully!',
                    ]);    
            } else{
                return response()->json([
                        'status' => 400, 
                        'message' => 'Failed',
                    ]);    
            }
    }
    
}
