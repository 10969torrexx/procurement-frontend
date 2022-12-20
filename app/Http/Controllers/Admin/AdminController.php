<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function accounts_index(){
            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
              ["link" => "/", "name" => "Home"],["name" => "Add User"]
            ];
            $users = DB::table('users as u')
                        ->select('u.*','d.department_name')
                        ->join('departments as d', 'd.id', '=', 'u.department_id')
                        ->where('u.campus',session('campus'))
                        ->whereNotNull('u.email')
                        ->whereNull('u.deleted_at')
                        ->orderBy('u.name')
                        ->get();
            // dd($users);
            
            return view('pages.admin.accounts_index',compact('users'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]);
    }

    public function get_accounts_HRMIS(){
        $response = DB::connection("hrmis")
            ->table("employee")
            ->whereNull("deleted_at")
            ->where("Campus",session('campus'))
            ->orderBy("LastName")
            ->orderBy("FirstName")
            ->get();
    
        return response()->json(["data"=>$response]);
        
    }

    public function getDepartmentsByCampus(){
        // dd($request->campus);
        try {
            $departments =  DB::table('departments')
                                ->where('campus',session('campus'))
                                ->whereNull('deleted_at')  
                                ->orderBy('department_name')
                                ->get();
            // dd($departments);
            return $departments;
            
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function save_account(Request $request){
        $employee_info = $request->employee;
        $info = explode('*',$employee_info);

        $first_name = $info[0];
        $middle_name = $info[1];
        $last_name = $info[2];
        $email = $info[3];
        $employee_id = $info[5];

        $middle_initial = substr($middle_name, 0, 1).'.';

        $full_name = $first_name.' '.$middle_initial.' '.$last_name;

        $role = (new AESCipher())->decrypt($request->role);
        $department = $request->department;

        $check = DB::table('users')
                    ->where('email', $email)
                    ->where('campus', session('campus'))
                    ->whereNull('deleted_at')
                    ->get();
        if(count($check)==0){
            $response= DB::table('users')
            ->insert([
                'campus' => session('campus'),
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

    public function edit_account(Request $request){
        $id = (new AESCipher())->decrypt($request->id);

        $user = DB::table('users')->where('id', $id)->get();
        // dd($user);
        return response()->json([
            'status'=>200,
            'user'=> $user, 
            'id'=> $request->id, 
        ]);
    }

    public function update_account(Request $request){
        // dd($request->all());
            $updatename = $request->updatename;
            $updatedepartment = $request->updatedepartment;
            $updaterole = (new AESCipher())->decrypt($request->updateselectrole);
            $updateid = $request->updateid;

            $id = (new AESCipher())->decrypt($updateid);
            
            $check = DB::table('users')
                        ->where('id', $id)
                        ->where('name', $updatename)
                        ->where('role', $updaterole)
                        ->where('department_id', $updatedepartment)
                        ->where('campus', session('campus'))
                        ->whereNull('deleted_at')
                        ->get();
            if(count($check) == 1){
                return response()->json([
                    'status' => 400, 
                    'message' => 'No Changes Made!',
                ]);    
            }else{
                $response = DB::table('users')
                ->where('id',$id)
                ->update([
                    'name' => $updatename,
                    'campus' => session('campus'),
                    'role' => $updaterole,
                    'department_id' => $updatedepartment,
                    'updated_at' =>  Carbon::now()
                    ]
                );

                if($response){
                    return response()->json([
                            'status' => 200, 
                            'message' => 'Account Updated Successfully!',

                        ]);    
                } else{
                    return response()->json([
                            'status' => 400, 
                            'message' => 'Failed!',
                        ]);    
                }
            }
    }

    public function delete_account(Request $request){
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

    public function departments_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["name" => "Add Department"]
        ];
        $departments = DB::table('departments as d')
                        ->select('d.department_name','d.campus','d.description','d.id','u.name as department_head','us.name as immediate_supervisor')
                        ->join('users as u', 'u.id', '=', 'd.department_head')
                        ->join('users as us', 'us.id', '=', 'd.immediate_supervisor')
                        ->whereNull('u.deleted_at')
                        ->whereNull('d.deleted_at')
                        ->where('d.campus',session('campus'))
                        ->orderBy('d.department_name')
                        ->get();

        return view('pages.admin.departments_index',compact('departments'), [
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
            // 'error' => $error,
        ]);
    }

    public function get_accounts_PMIS(){
        try {
            $response = DB::table('users')
                            ->where('campus',session('campus'))
                            ->whereNull('deleted_at')
                            ->whereNotNull('employee_id')
                            ->orderBy('name')
                            ->get();
          return $response;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function save_department(Request $request){
        // dd($request->all());
        $department_name = $request->department_name;
        $department_description = $request->department_description;
        $department_head = $request->department_head;
        $immediate_supervisor = $request->immediate_supervisor;

        $check = DB::table('departments')
                    ->where('department_name', $department_name)
                    ->where('campus', session('campus'))
                    ->get();
        if(count($check)==0){
            $response= DB::table('departments')
            ->insert([
                'campus' => session('campus'),
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

    public function delete_department(Request $request){
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

    public function edit_department(Request $request){
        // dd($request->all());
        $id = (new AESCipher())->decrypt($request->id);

        $department = DB::table('departments')->where('id', $id)->get();
        return response()->json([
            'status'=>200,
            'department'=> $department, 
            'id'=> $request->id, 
        ]);
    }

    public function update_department(Request $request){
        $update_department_name = $request->update_department_name;
        $update_department_description = $request->update_department_description;
        $update_department_head = $request->update_department_head;
        $update_immediate_supervisor = $request->update_immediate_supervisor;
        $update_id = $request->update_id;
        
        $id = (new AESCipher())->decrypt($update_id);

        $check = DB::table('departments')
                    ->where('id', $id)
                    ->where('department_name', $update_department_name)
                    ->where('description', $update_department_description)
                    ->where('department_head', $update_department_head)
                    ->where('immediate_supervisor', $update_immediate_supervisor)
                    ->where('campus', session('campus'))
                    ->whereNull('deleted_at')
                    ->get();

        $check1 = DB::table('departments')
                    ->where('id', $id)
                    ->where('department_name', $update_department_name)
                    ->where('campus', session('campus'))
                    ->whereNull('deleted_at')
                    ->get();

        $check2 = DB::table('departments')
                    ->where('department_name', $update_department_name)
                    ->where('campus', session('campus'))
                    ->whereNull('deleted_at')
                    ->get();

        if(count($check) == 1){
            return response()->json([
                'status' => 400, 
                'message' => 'No Changes Made!',
            ]);    
        }else if((count($check) == 0 && count($check2) == 0) || count($check1) == 1){
            $response = DB::table('departments')
                    ->where('id',$id)
                    ->update([
                        'campus' => session('campus'),
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
        }else if(count($check1) == 0 && count($check2) == 1){
            return response()->json([
                'status' => 400, 
                'message' => 'Department Already Exist!',
            ]);   
        }
    }

    public function mandatory_expenditures_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Mandatory Expenditure List"]
        ];
        
        $mandatory_expenditures = DB::table('mandatory_expenditures_list')
                            ->whereNull('deleted_at')
                            ->orderBy('expenditure')
                            ->get();
        // dd($mandatory_expenditures);
        return view('pages.admin.mandatory_expenditures_index',compact('mandatory_expenditures'), [
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
            // 'error' => $error,
        ]);
    }

    public function save_mandatory_expenditure(Request $request){
        // dd($request->all());
        $mandatory_expenditure = $request->mandatory_expenditure;
        $check = DB::table('mandatory_expenditures_list')
                    ->where('expenditure', $mandatory_expenditure)
                    ->whereNull('deleted_at')
                    ->get();
        if(count($check)==0){
            $response= DB::table('mandatory_expenditures_list')
                    ->insert([
                        'expenditure' => $mandatory_expenditure,
                        'created_at' => Carbon::now()
                    ]);
            if($response){
                return response()->json([
                    'status' => 200, 
                    'message' => 'Mandatory Expenditure Saved Successfully!',
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
                'message' => 'Mandatory Expenditure Already Exist!',
            ]);    
        }
    }

    public function delete_mandatory_expenditure(Request $request){
        $id = (new AESCipher())->decrypt($request->id);
        $mandatory_expenditure = DB::table('mandatory_expenditures_list')->where('id',$id)->delete();
        if($mandatory_expenditure)
        {
            return response()->json([
                'status'=>200,
                'message'=>'Mandatory Expenditure Deleted Successfully!'
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

    public function edit_mandatory_expenditure(Request $request){
        $id = (new AESCipher())->decrypt($request->id);
        $mandatory_expenditure = DB::table('mandatory_expenditures_list')->where('id', $id)->get();
        // dd($mandatory_expenditure);
        return response()->json([
            'status'=>200,
            'mandatory_expenditure'=> $mandatory_expenditure, 
            'id'=> $request->id, 
        ]);
    }

    public function update_mandatory_expenditure(Request $request){
        $mandatory_expenditure = $request->mandatory_expenditure;
        $id = (new AESCipher())->decrypt($request->id);
        $check = DB::table('mandatory_expenditures_list')
                    ->where('expenditure', $mandatory_expenditure)
                    ->whereNull('deleted_at')
                    ->get();
        $check1 = DB::table('mandatory_expenditures_list')
                    ->where('expenditure', $mandatory_expenditure)
                    ->where('id', $id)
                    ->whereNull('deleted_at')
                    ->get();
        if(count($check) == 1 && count($check1) == 0){
            return response()->json([
                'status' => 400, 
                'message' => 'Mandatory Expenditure Already Exist!',
            ]); 
        }else if(count($check1) == 1){
            return response()->json([
                'status' => 400, 
                'message' => 'No Changes Made!',
            ]); 
        }else if(count($check) == 0 && count($check1) == 0){
            $response = DB::table('mandatory_expenditures_list')
                            ->where('id',$id)
                            ->update([
                                'expenditure' => $mandatory_expenditure,
                                'updated_at' =>  Carbon::now()
                            ]);
                    if($response){
                        return response()->json([
                                'status' => 200, 
                                'message' => 'Mandatory Expenditure Updated Successfully!',
                            ]);    
                    } else{
                        return response()->json([
                                'status' => 400, 
                                'message' => 'Failed!',
                            ]);    
                    }
        }
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

        return view('pages.admin.fund_sources_index',compact('fund_sources'), [
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
            // 'error' => $error,
        ]);
    }

    public function add_fund_source(Request $request){
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

    public function delete_fund_source(Request $request){
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

    public function edit_fund_source(Request $request){
        $id = (new AESCipher())->decrypt($request->id);
        $fund_source = DB::table('fund_sources')->where('id', $id)->get();
        return response()->json([
            'status'=>200,
            'fund_source'=> $fund_source, 
            'id'=> $request->id, 
        ]);
    }

    public function update_fund_source(Request $request){
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

    public function items_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Items"]
        ];
    
        $category = DB::table("categories")
                //   ->where("campus", session('campus'))
                  ->whereNull("deleted_at")
                  ->orderBy("category")
                  ->get();
    
        $item = DB::table("items as i")
                ->select("i.*","mp.mode_of_procurement")
                ->join("mode_of_procurement as mp","mp.id","i.mode_of_procurement_id")
                //   ->where("campus", session('campus'))
                ->whereNull("i.deleted_at")
                ->orderBy("i.item_name")
                ->get();

        // $all = [$item,$category];
        // dd( $item);
        return view('pages.admin.items_index',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],compact('category', 'item')
        );
    }

    public function add_item(Request $request){
        // dd( $request-> all()); 
        $item = $request->item_name;
        $mode_of_procurement = $request->mode_of_procurement;
        $item_category = $request->item_category;
        $app_type = $request->app_type;
    
        $check = DB::table("items")
              ->where('item_name',$item)
            //   ->where('campus',session('campus'))
              ->whereNull('deleted_at')
              ->get();
        if(count($check) > 0)
        {
            return response()->json([
                'status' => 400, 
                'message' => 'Item Already Exist!.',
            ]);    
        }else{
            DB::table("items")
            ->insert([
                'item_name'=>  $item,
                'item_category' => $item_category,
                'app_type' => $app_type,
                'mode_of_procurement_id' => $mode_of_procurement,
                'campus'=>  session('campus'),
                'name'=>  session('name'),
                'created_at'=> Carbon::now(),
                // 'updated_at'=> Carbon::now(),
            ]);
            return response()->json([
                    'status' => 200, 
                    'message' => 'Item Saved Succesfully!.',
                ]); 
        }
    }

    public function delete_item(Request $request){
        $aes = new AESCipher();
        $id = $aes->decrypt($request->id);
      
        $response = DB::table("items")
            ->where("id",$id)
            ->update([
              'deleted_at'=> Carbon::now(),
          ]);
        if($response){
          return response()->json([
          'status' => 200, 
          ]);     
        }else{
          return response()->json([
            'status' => 400, 
          ]);   
        }
    }

    public function edit_item(Request $request){
        $aes = new AESCipher();
        $id = $aes->decrypt($request->id);
        $item = DB::table("items")
              ->where('id',$id)
              ->get();
        if(count($item) > 0)
        {
            return response()->json([
            'status' => 200, 
            'data'=>$item,
            'id'=>$aes->encrypt($id),
            ]);    
        }
        else{
            return response()->json([
                    'status' => 400, 
                    'message' => 'Does not exist!.',
            ]); 
        }
    }

    public function update_item(Request $request){
        try{
            $aes = new AESCipher();
            $id = $aes->decrypt($request->id);
            $mode_of_procurement =  $request->mode_of_procurement;
            $item_name = $request->item_name;
            $item_category = $request->item_category;
            $app_type = $request->app_type;

            $check = DB::table('items')
                        ->where('id', $id)
                        ->where('item_name', $item_name)
                        ->where('item_category', $item_category)
                        ->where('mode_of_procurement_id', $mode_of_procurement)
                        ->where('app_type', $app_type)
                        ->whereNull('deleted_at')
                        ->get();

            $check1 = DB::table('items')
                        ->where('id', $id)
                        ->where('item_name', $item_name)
                        ->whereNull('deleted_at')
                        ->get();
            $check2 = DB::table('items')
                        ->where('item_name', $item_name)
                        ->whereNull('deleted_at')
                        ->get();

            if(count($check) == 1){
                return response()->json([
                    'status' => 400, 
                    'message' => 'No Changes Made!',
                ]); 
            }else if((count($check) == 0 && count($check2) == 0) || count($check1) == 1){
                $response = DB::table("items")
                                ->where("id",$id)
                                ->update([
                                    'item_name' => $request->item_name,
                                    'name' => session('name'),
                                    'item_category' => $request->item_category,
                                    'app_type' => $request->app_type,
                                    'mode_of_procurement_id' =>  $mode_of_procurement,
                                    'updated_at'=> Carbon::now(),
                                ]);
                if($response){
                    return response()->json([
                            'status' => 200, 
                            'message' => 'Item Updated Successfully!',
                        ]);    
                } else{
                    return response()->json([
                            'status' => 400, 
                            'message' => 'Failed!',
                        ]);    
                }
            }else if(count($check1) == 0 && count($check2) == 1){
                return response()->json([
                    'status' => 400, 
                    'message' => 'Item Already Exist!',
                ]); 
            }
          }catch (\Throwable $th) {
              return response()->json([
                  'status' => 600,
                  'message'   => $th
              ]);
          }
    }

    public function get_mode_of_procurement(){
        $mode_of_procurement = DB::table("mode_of_procurement")
                                ->select("id","mode_of_procurement")
                                ->whereNull("deleted_at")
                                ->orderBy("mode_of_procurement")
                                ->get();
                                // dd($mode_of_procurement);
        if(count($mode_of_procurement) > 0){
            return response()->json([
                'status' => 200, 
                'data' => $mode_of_procurement,
            ]);    
        }else{
            return response()->json([
                'status' => 400, 
                'message' => "Not Found!",
            ]);    
        }
    }

    public function category_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Category"]
        ];
  
        $category = DB::table("categories")
                  ->whereNull("deleted_at")
                  ->get();
  
        return view('pages.admin.category_index',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
        [
          'data' => $category,
        ] 
        );
    }

    public function add_category(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $cat = DB::table("categories")
              ->where('category',$request->category)
              ->where("campus", session('campus'))
              ->whereNull("deleted_at")
                    ->get();
  
        if(count($cat) > 0){
            return response()->json([
            'status' => 400, 
            'message' => 'Item Already Exist!.',
              ]);    
        }
        else{
          DB::table("categories")
          ->insert([
          'category'=> $request->category,
          'campus'=> session('campus'),
          'name'=> session('name'),
          'created_at'=> Carbon::now(),
          'updated_at'=> Carbon::now(),
            ]);   
  
          return response()->json([
            'status' => 200, 
          ]);    
        }     
    }
  
    public function edit_category(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
  
        $response = DB::table("categories")
                  -> where('id',$id)
                  ->get();
        if($response){
          return response()->json([
            'status' => 200,
            'data'   => $response,
            'id'=>$aes->encrypt($id),
            ]);     
        }else{
          return response()->json([
            'status' => 400, 
            ]); 
        }
    }
  
    public function update_category(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
  
        $check = DB::table("categories")
                    ->where('category',$request->category )
                    ->get();
        if(count($check) > 0 )
        {
          return response()->json([
            'status' => 400, 
            'message' => 'error',
          ]); 
  
        }else{
          $category = DB::table("categories")
                  ->where('id',$id)
                  ->update([
                    'category' => $request->category,
                    'name'     =>session('name'),
                    'updated_at'=> Carbon::now(),
                  ]);
  
          return response()->json([
            'status' => 200, 
            'message' => 'Updated!.',
          ]);  
        }
    }
  
    public function delete_category(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        $cat = DB::table("categories")
                      ->where('id',$id )
                      ->update([
                        'deleted_at' => Carbon::now(),
                      ]);
  
        if( $cat)
          {
            return response()->json([
            'status' => 200, 
            'message' => 'Deleted!.',
        ]);    
        }
        else{
            return response()->json([
            'status' => 400, 
            'message' => 'error',
            ]); 
        }
    }

    public function unit_of_measurement_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Unit of Measurement"]
        ];
    
        $unitofmeasurement = DB::table("unit_of_measurements")
                  ->whereNull("deleted_at")
                  ->orderby("unit_of_measurement")
                  ->get();
    
        return view('pages.admin.unit_of_measurement_index',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
        [
          'data1' => $unitofmeasurement,
        ] 
        );
    }

    public function add_unit(Request $request){
        $unit_of_measurement = DB::table("unit_of_measurements")
            ->where('unit_of_measurement',$request->unitofmeasurement)
            ->where("campus", session('campus'))
            ->whereNull("deleted_at")
            ->get();
    
        if(count($unit_of_measurement) > 0){
            return response()->json([
            'status' => 400, 
            'message' => 'Item Already Exist!.',
              ]);    
        }
        else{
            DB::table("unit_of_measurements")
            ->insert([
            'unit_of_measurement'=> $request->unitofmeasurement,
            'campus'=> session('campus'),
            'name'=> session('name'),
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
              ]);   
    
            return response()->json([
              'status' => 200, 
            ]);    
        } 
    }
    
    public function edit_unit(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        // $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/category/show/".$id1,[
        // 'id' => $id1,
        // ])->json();

        $response = DB::table("unit_of_measurements")
                    -> where('id',$id)
                    ->get();
        // dd($response);          
        // return $response; 
        if($response){
            return response()->json([
            'status' => 200,
            'data'   => $response,
            'id'=>$aes->encrypt($id),
            ]);     
        }else{
            return response()->json([
            'status' => 400, 
            ]); 
        } 
    }
    
    public function update_unit(Request $request){
        // dd($request->all());
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);

        $check = DB::table("unit_of_measurements")
                    ->where('unit_of_measurement',$request->unit_of_measurement )
                    ->get();
        if(count($check) > 0 )
        {
            return response()->json([
            'status' => 400, 
            'message' => 'error',
            ]); 

        }else{
            $unit = DB::table("unit_of_measurements")
                    ->where('id',$id)
                    ->update([
                    'unit_of_measurement' => $request->unit_of_measurement,
                    'name'     =>session('name'),
                    'updated_at'=> Carbon::now(),
                    ]);
            if($unit){
            return response()->json([
                'status' => 200, 
                'message' => 'Updated!.',
            ]); 
            }

        };
    }

    public function delete_unit(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        $unit = DB::table("unit_of_measurements")
                        ->where('id',$id )
                        ->update([
                        'deleted_at' => Carbon::now(),
                        ]);

        if( $unit)
            {
            return response()->json([
            'status' => 200, 
            'message' => 'Deleted!.',
        ]);    
        }
        else{
            return response()->json([
            'status' => 400, 
            'message' => 'error',
            ]); 
        }
    } 

    public function mode_of_procurement_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Mode of Procurement"]
        ];
    
        $modeofprocurement = DB::table("mode_of_procurement")
                    //   ->where("campus", session('campus'))
                      ->whereNull("deleted_at")
                      ->orderBy("mode_of_procurement")
                      ->get();
    
        return view('pages.admin.mode_of_procurement_index',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
        [
          'data' => $modeofprocurement,
        ] 
        );
    }
    
    public function add_procurement(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $mode = DB::table("mode_of_procurement")
                ->where('mode_of_procurement',$request->modeofprocurement)
                // ->where("campus", session('campus'))
                ->whereNull("deleted_at")
                ->get();

        if(count($mode) > 0){
            return response()->json([
            'status' => 400, 
            'message' => 'Item Already Exist!.',
                ]);    
        }
        else{
            DB::table("mode_of_procurement")
            ->insert([
                'mode_of_procurement'=> $request->modeofprocurement,
                'abbreviation'=> $request->abbreviation,
                'campus'=> session('campus'),
                'name'=> session('name'),
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now(),
            ]);   

            return response()->json([
            'status' => 200, 
            ]);    
        }    
    }
    
    public function edit_procurement(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);

        $response = DB::table("mode_of_procurement")
                    ->where('id',$id)
                    ->get();
        if($response){
            return response()->json([
                'status' => 200,
                'data'   => $response,
                'id'=>$aes->encrypt($id),
            ]);     
        }else{
            return response()->json([
            'status' => 400, 
            ]); 
        }
    }
    
    public function update_procurement(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);

        $check = DB::table("mode_of_procurement")
                    ->where('mode_of_procurement',$request->mode_of_procurement )
                    ->get();
        if(count($check) > 0 )
        {
        return response()->json([
            'status' => 400, 
            'message' => 'error',
        ]); 

        }else{
        $category = DB::table("mode_of_procurement")
                ->where('id',$id)
                ->update([
                    'mode_of_procurement' => $request->mode_of_procurement,
                    'abbreviation' => $request->abbreviation,
                    'name'     =>session('name'),
                    'updated_at'=> Carbon::now(),
                ]);

        return response()->json([
            'status' => 200, 
            'message' => 'Updated!.',
        ]);  
        }
        // dd($response);
    }
      
    public function delete_procurement(Request $request){
        $id = $request->id;
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        $mode = DB::table("mode_of_procurement")
                        ->where('id',$id )
                        ->update([
                        'deleted_at' => Carbon::now(),
                        ]);

        if( $mode)
            {
            return response()->json([
            'status' => 200, 
            'message' => 'Deleted!.',
        ]);    
        }
        else{
            return response()->json([
            'status' => 400, 
            'message' => 'error',
            ]); 
        }
    }

    public function approved_ppmp_index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Approved PPMP"]
        ];
    
        $ppmp = DB::table("ppmps as p")
            ->select('d.department_name','pt.project_title','pt.project_code','pt.id','pt.employee_id','pt.department_id')
            ->selectRaw('SUM(estimated_price) as Total')
            ->join('project_titles as pt','pt.id','=','p.project_code')
            ->join('departments as d','d.id','=','pt.department_id')
            ->where('pt.campus','=', session('campus'))
            ->where('pt.status','=', 4)
            ->where('pt.deleted_at','=', NULL)
            // ->where('p.campus','=',session('campus'))
            ->where('p.is_supplemental','=', 0)
            ->groupBy('p.project_code')
            -> get();
    
        return view('pages.admin.approved_ppmp_index',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
        [
          'data' => $ppmp,
        ] 
        );
        
    }

    public function show_approved_ppmp(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $project_code = $aes->decrypt($request->project_code);
        $response = DB::table("ppmps")
                  ->select('project_titles.project_title','project_titles.project_code as ProjectCode','ppmps.*')
                  ->join('project_titles','project_titles.id','=','ppmps.project_code')
                  ->where('ppmps.project_code','=',$project_code)
                  ->where('ppmps.status','=', 4)
                  ->where('ppmps.deleted_at','=', NULL)
                  ->where('ppmps.is_supplemental','=',0)
                  -> get();
            if(count($response) > 0)
            {
                return response()->json([
                'status' => 200, 
                'data'=>$response,
                ]);    
            }
            else{
            return response()->json([
                        'status' => 400, 
                        'message' => 'Does not exist!.',
                    ]); 
            }
    }

    public function pending_ppmps_index(){
      
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" =>"Supervisor"]
        ];
        
        $ppmp = DB::table("project_titles as pt")
              ->select("pt.*","ab.allocated_budget","ab.remaining_balance","fs.fund_source")
              // ->selectRaw("Sum(p.estimated_price) as Total")
              ->join('ppmps as p','p.project_code','=','pt.id')
              ->join('allocated__budgets as ab','pt.allocated_budget','=','ab.id')
              ->join('fund_sources as fs','fs.id','=','pt.fund_source')
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

        return view('pages.admin.pending_ppmps_index', compact('ppmp','item'),
        [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs
        ]);
    }

    public function show_ppmp(Request $request){
        // dd($request->all());
        $aes = new AESCipher();
        $id = $aes->decrypt($request->project_code);
        $data = DB::table("ppmps as p")
                  ->select("pt.project_code as code","pt.status as projectStatus","pt.id as pt_id","pt.project_title as title","p.*")
                  ->join('project_titles as pt','pt.id','=','p.project_code')
                  ->where('p.project_code','=',$id)
                  ->whereNull("p.deleted_at")
                  ->where("p.is_supplemental","=", 0)
                  ->where("p.status","!=", 0)  
                  // ->orwhere("p.status","=", 2)  
                  // ->orwhere("p.status","=", 3)   
                  -> get();
        // $title = $data->title;
        // dd($data);   
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["link" => "/bac/supervisor","name" =>"Supervisor"],["name" =>"PPMP"]
        ];
        return view('pages.admin.supervisor_check_ppmp',compact('data'),
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
            ->where("status",2)
            ->where('department_id',  session('department_id'))
            ->where('campus', session('campus'))
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
}
