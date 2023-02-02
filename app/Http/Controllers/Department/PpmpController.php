<?php

namespace App\Http\Controllers\department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ppmp;
use App\Models\Project_titles;
use App\Models\Project_Timeline;
use App\Models\Allocated_Budgets;
use App\Http\Controllers\AESCipher;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ProjectTimelineController;
use DB;

class PpmpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * This will update the statuses of project title and ppmp items based on the project code, 
     * Employee id
     * Department Id
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitPPMP(Request $request)
    {
        try {
            # this will update the project title status 
                $_project_title = Project_titles::
                    where('id', (new AESCipher)->decrypt($request->current_project_code))
                    ->where('employee_id', session('employee_id'))
                    ->where('department_id', session('department_id'))
                    ->where('campus', session('campus'))
                    ->update([
                        'status'    =>  1
                    ]);
            # end
            # this will update the status in the ppmps table based on the project code, employee id, department id
                $pmmp_response = ppmp::
                    where('project_code', (new AESCipher)->decrypt($request->current_project_code))
                    ->where('employee_id', session('employee_id'))
                    ->where('department_id', session('department_id'))
                    ->where('campus', session('campus'))
                    ->update([
                        'status'    =>  1
                    ]);
            # end
            # this will update remaining balance allocated budgets
                 $allocated_budgets = Allocated_Budgets::where('id', $request->allocated_budget)->update([
                    'remaining_balance' => doubleval($request->f_remaining_balance),
                    'procurement_type' => 'Supplemental'
                 ]);
            # end
            # this will store project as pending on project timeline
                 $project_timeline = (new ProjectTimelineController)->store($request, 1, 'Your PPMP is currently Pending of Immediates Supervisor\'s Acceptance.');
            # end
            return [
                'status' => 200,
                'message'   => 'You\'ve successfully submitted your PPMP.'
            ];
       } catch (\Throwable $th) {
            throw $th;
       }
    }

    public function re_submitPPMP(Request $request)
    {
        try {
                # this will update the project title status 
                    $_project_title = Project_titles::
                        where('id', (new AESCipher)->decrypt($request->current_project_code))
                        ->where('employee_id', session('employee_id'))
                        ->where('department_id', session('department_id'))
                        ->where('campus', session('campus'))
                        ->update([
                            'status'    =>  6
                        ]);
                # end
                # this will update the status in the ppmps table based on the project code, employee id, department id
                    $pmmp_response = ppmp::
                        where('project_code', (new AESCipher)->decrypt($request->current_project_code))
                        ->where('employee_id', session('employee_id'))
                        ->where('department_id', session('department_id'))
                        ->where('campus', session('campus'))
                        ->whereRaw("status = '3' OR status = '5'")
                        ->update([
                            'status'    =>  6
                        ]);
                # end
                # this will update remaining balance allocated budgets
                    $allocated_budgets = Allocated_Budgets::where('id', $request->allocated_budget)->update([
                        'remaining_balance' => doubleval($request->f_remaining_balance),
                        'procurement_type' => 'Supplemental'
                    ]);
                # end
                # this will store project as pending on project timeline
                    $project_timeline = (new ProjectTimelineController)->store($request, 6, 'Your revised PPMP is currently Pending of Immediates Supervisor\'s Approval.');
                # end
                return [
                    'status' => 200,
                    'message'   => 'You\'ve successfully submitted your PPMP.'
                ];
        } catch (\Throwable $th) {
                throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($project_code)
    {
        // 
        $response = ppmp::where('department_id', intval(session('department_id')))
        ->where('employee_id', intval(session('employee_id')))
        ->where('project_code', (new AESCipher)->decrypt($project_code))
        ->whereNull('deleted_at')
        ->get();
        if(count($response) > 0) {
            return ([
                'status'    =>  200,
                'data' => json_decode($response)
            ]);
        }
        return ([
            'status'    => 400,
            'message'   =>  'Failed to retrieve data from ppmp'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $response = ppmp::where('id', $request->id)->update([
            'item_name' => $request->item_name,
            'quantity'  =>  $request->quantity,
            'estimated_price'   =>  $request->estimated_price,
            'unit_of_measurement'   =>  $request->unit_of_measurement,
            'item_description'  =>  $request->item_description,
            'mode_of_procuremnet'   =>  $request->mode_of_procurement,
            'expected_month'    =>  (new AESCipher)->decrypt($request->expected_month)
        ]);
        // if(count($response) > 0)   
        // this will be return once update was successfull
        return ([
            'status' => 200,
            'message'   => $response
            // 'message'   => 'Item(s) updated successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //$response = DB::delete('delete from ppmps where id = ?',[(new AESCipher)->decrypt($request->id)]);
        $response = ppmp::where('id', (new AESCipher)->decrypt($request->id))->delete();
        return ([
            'status' => 200,
            'message'   =>  'Item successfully deleted!'
        ]);
    }

    /**
     * Show PPMP by id | project code
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_ppmp_projectcode_disapproved(Request $request)
    {
       $response = ppmp::
        where('project_code', (new AESCipher)->decrypt($request->id))
        ->whereNull('deleted_at')
        ->Where('status', '!=', 0)
        ->Where('campus', session('campus'))
        ->Where('department_id', session('department_id'))
        ->Where('employee_id', session('employee_id'))
        ->get();

       if($response) {
            return ([
                'status'    => 200,
                'data'  =>  $response
            ]);
       }
    }

    /** Romar Additionals 
     * To implement Purchase Request Index
    */
    public function PurchaseRequestIndex(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Purchase Request"]
        ];
        // return view('pages.budgetofficer.allocatebudget',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);

        
        $items =  Http::withToken(session('token'))->get(env('APP_API'). "/api/purchaseRequest/getItems")->json();
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
}
