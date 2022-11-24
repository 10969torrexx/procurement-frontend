<?php

namespace App\Http\Controllers\BudgetOfficer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Deadline;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Support\Facades\Validator;
use DB;
class AllocateBudgetController extends Controller
{
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Allocate Budget"]
        ];
        // return view('pages.budgetofficer.allocatebudget',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);


        $ppmp_deadline =  Http::withToken(session('token'))->get(env('APP_API'). "/api/budget/get_deadline")->json();
            // dd($ppmp_deadline);
            $error="";
            if($ppmp_deadline['status']==400){
                $error=$ppmp_deadline['message'];
            }

            return view('pages.budgetofficer.allocatebudget',compact('ppmp_deadline'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                'error' => $error,
                'allocated_budgets' => $ppmp_deadline['data'],
                // 'year' => $ppmp_deadline['year'],
            ]); 
    }
    public function get_years(Request $request)
    {
        try {
            // $department = $request->department;
        // $response = Http::withToken(env('Auth_HRMIS_Token'))->post(env('APP_HRMIS_API'). "/api/auth/employeelist", [
        //     'department' => $department,
        //   ])->json();
          $response =  Http::withToken(session('token'))->get(env('APP_API'). "/api/years/getYears")->json();

        //   dd($response);
          return $response;
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
    public function get_DeadlineByYear(Request $request)
    {
        try {
        //   dd($request->all());

            $year = $request->year;
        // $response = Http::withToken(env('Auth_HRMIS_Token'))->post(env('APP_HRMIS_API'). "/api/auth/employeelist", [
        //     'department' => $department,
        //   ])->json();
          $response =  Http::withToken(session('token'))->get(env('APP_API'). "/api/years/getDeadlineByYear",[
            'year' => $year,
          ])->json();

        //   dd($response);
          return $response;
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
    public function getDepartments(Request $request)
    {
        try {
            $departments =  Http::withToken(session('token'))->get(env('APP_API'). "/api/department/getDepartments")->json();
        //   dd($departments);
          return $departments;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function getFundSources(Request $request)
    {
        try {
            $fundsources =  Http::withToken(session('token'))->get(env('APP_API'). "/api/fundsource/getFundSources")->json();
        //   dd($departments);
          return $fundsources;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function getMandatoryExpenditures(Request $request)
    {
        try {
            $expenditures =  Http::withToken(session('token'))->get(env('APP_API'). "/api/expenditures/getMandatoryExpenditures")->json();
        //   dd($expenditures);
          return $expenditures;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function allocate_budget(Request $request){
    //   dd($request->all());
    
        
        // dd($total_expenditures);
          $year = $request->year;
          $type = $request->type;
          $deadline_of_submission = $request->end_date;
          $department = $request->department;
          $fund_source = $request->fund_source;
          $budget = $request->budget;

          $total_expenditures = DB::table('mandatory_expenditures')
            ->where('department_id',$department)
            ->where('fund_source_id',$fund_source)
            ->where('year',$year)
            ->sum('price');
        //   $mandatory_expenditures = $request->mandatory_expenditures;
        //   $mandatory_expenditures = serialize($mandatory_expenditures_array);
        //   $mandatory_expenditures1 = unserialize($mandatory_expenditures);
        //   dd($mandatory_expenditures1);
          $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/budget/allocate_budget",[
          'type' => $type,
          'year' => $year,
          'total_expenditures' => $total_expenditures,
          'deadline_of_submission' => $deadline_of_submission,
          'department_id' => $department,
          'fund_source_id' => $fund_source,
          'allocated_budget' => $budget,
        //   'mandatory_expenditures' => $mandatory_expenditures,
          ])->json();
        //   dd($response);

        return $response;
  }
    
  public function delete(Request $request){
      // dd($request->all());

    $id = $request->id;
    $id1 = (new AESCipher())->decrypt($id);
    $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/budget/delete/".$id1,[
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

  public function edit(Request $request)
  {
      $id = $request->id;
      $id1 = (new AESCipher())->decrypt($id);
    //   dd($id1);

      $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/budget/edit/".$id1,[
          'id' => $id1,
          'message' => $id1,

      ])->json();
    //   dd($response);

      return $response;
  }

  public function updateAllocatedBudget(Request $request)
    {
    //    dd($request->all()) ;
        $validator = Validator::make($request->all(), [
            'update_department'=> 'required',
            'update_fund_source' => 'required',
            'update_budget' => 'required',
            'update_mandatory_expenditures' => 'required',
        ]);
        // if($request->update_mandatory_expenditures == null){
        //     return response()->json([
        //         'status'=>401,
        //         // 'message' => 'Please select mandatory expenditures!',
        //         'errors'=>$validator->messages()
        //     ]);
        // }
        if($validator->fails())
        {
            // return response($validator->errors()->toJson());
            return response()->json([
                'status'=>401,
                'errors'=>$validator->messages()
            ]);
            dd($validator);
            // dd($validator->errors()->toJson());
            // $validator->errors()->toJson();
        }
        else{
            $update_department = $request->update_department;
            $update_fund_source = $request->update_fund_source;
            $update_budget = $request->update_budget;
            $update_mandatory_expenditures = $request->update_mandatory_expenditures;
            $updateid = $request->updateid;
            $id = $updateid;
            
            $response = Http::withToken(session('token'))->put(env('APP_API'). "/api/budget/update/".$id,[
            'updateid' => $id,
            'update_department' => $update_department,
            'update_fund_source' => $update_fund_source,
            'update_budget' => $update_budget,
            'update_mandatory_expenditures' => $update_mandatory_expenditures,
            ])->json();
            // dd($response);
            return $response;
            // if($response){
            //     if($response['status'] == 200){
            // //    dd($response);
            //         return response()->json([
            //         'status' => 200, 
            //         'message' => 'Updated!',
            //         ]);    
            //     }if($response['status'] == 400){
            //         //    dd($response);
            //                 return response()->json([
            //                 'status' => 400, 
            //                 'message' => 'Deadline Already Exist!',
            //                 ]);    
            //     }if($response['status'] == 401){
            //         //    dd($response);
            //                 return response()->json([
            //                 'status' => 400, 
            //                 'message' => 'Please fill up the needed information!',
            //                 ]);    
            //             }
            // }
        }
        
    }
}
