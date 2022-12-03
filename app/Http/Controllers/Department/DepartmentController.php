<?php
namespace App\Http\Controllers\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// this will import AESCipeher COntroller
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Department\PpmpController;
use App\Http\Controllers\Department\ItemsController;
use App\Http\Controllers\Department\ProjectsController;
use App\Http\Controllers\Department\AllocatedBudgetsController;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use DB;
class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProjectTitle(Request $request)
    {
        $response = \DB::table('project_titles')
            ->join('fund_sources', 'project_titles.fund_source', 'fund_sources.id')
            ->where('project_titles.id', intval((new AESCipher)->decrypt($request->id)))
            ->where('project_titles.department_id', session('department_id'))
            ->where('project_titles.campus', session('campus'))
            ->whereNull('project_titles.deleted_at')
            ->get([
                'project_titles.*',
                'fund_sources.fund_source',
                'fund_sources.id as fund_source_id',
            ]);
        return $response;
    }
    /**
     * Store a newly created resource in storage for the Project Titles Table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createProjectTitle(Request $request)
    {
        # form fields validation
        $this->validate($request, [
            # field validation | disabled
                'project_title'     =>  'required',
                'fund_source'  =>  'required',
                'project_type'  =>  'required',
        ]); 
        $request->merge([
            'project_year'  => explode('**',$request->fund_source)[1],
            'allocated_budget' => explode('**', $request->fund_source)[2]
        ]);
        // this will create data to project titles table
           $project_titles = \DB::table('project_titles')
           ->insert([
                'employee_id'   => session('employee_id'),
                'project_title' => $request->project_title,
                'department_id' => session('department_id'),
                'campus'    =>  session('campus'),
                'project_year'  =>  (new AESCipher)->decrypt($request->project_year),
                'fund_source'   => (new AESCipher)->decrypt(explode('**',$request->fund_source)[0]),
                'allocated_budget' => (new AESCipher)->decrypt($request->allocated_budget),
                'immediate_supervisor' => session('immediate_supervisor'),
                'project_category'  =>  1, // soon to be dynamic
                'project_type'  => $request->project_type,
                'status'    => 0,
                'year_created'  => Carbon::now()->format('Y'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
           ]);
        // this will be returned if status was successful
            if($project_titles == true) {
                return back()->with([
                    'success'   => 'Project Title Created Succesfully!'
                ]);
            } 
              return back()->with([
                'error'   => 'Project Title Created Failed'
            ]);
    }

    /* Store a newly created resource in storage for the Project Titles Table.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function destoryProjectTitle(Request $request)
   {
        $project_titles = \DB::table('project_titles')
            ->where('id', (new AESCipher)->decrypt($request->id))
            ->where('campus', session('campus'))
            ->where('employee_id', session('employee_id'))
            ->where('department_id', session('department_id'))
            ->update([
                'deleted_at' => Carbon::now()
            ]);
        $ppmps = \DB::table('ppmps')
            ->where('project_code', (new AESCipher)->decrypt($request->id))
            ->where('campus', session('campus'))
            ->where('employee_id', session('employee_id'))
            ->where('department_id', session('department_id'))
            ->update([
                'deleted_at' => Carbon::now()
            ]);
        return back()->with([
            'success'   => 'Project Successfully deleted!'
        ]);
   }

   /* Updates a resource in storage for the Project Titles Table.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function updateProjectTitle(Request $request)
    {
        # Updating project title details
            $project_titles = \DB::table('project_titles')
                ->where('id', $request->id)
                ->where('campus', session('campus'))
                ->where('employee_id', session('employee_id'))
                ->where('department_id', session('department_id'))
                ->whereNull('deleted_at')
                ->update([
                    'project_title' => $request->project_title,
                    'project_type' => $request->project_type,
                    'project_year' => $request->project_year,
                    'fund_source' => explode('**', $request->fund_source)[1],
                    'allocated_budget' => explode('**', $request->fund_source)[0],
                ]);
        # return positive response
            return back()->with([
                'success'   => 'Project Updated Successfully!'
            ]);
    }
     /**
     * Store a newly created resource in storage for the Project Titles Table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createPPMPs(Request $request)
    {
        # form fields validation
        $this->validate($request, [
            # field validation | disabled
                'item_name'     =>  'required',
                'item_category' =>  'required',
                'unit_price'     =>  'required',
                'estimated_price'     =>  'required',
                'quantity'  =>  'required',
                'item_description'  =>  'required',
                'mode_of_procurement'  =>  'required',
                'expected_month'    =>  'required',
                'unit_of_measurement'  =>  'required',
        ]); 
        // this will send data to project titles table using api
            $create_items = \DB::table('ppmps')
                ->insert([
                    'employee_id' => session('employee_id'),
                    'department_id' => session('department_id'),
                    'campus' => session('campus'),
                    'project_code'  =>  explode('**', $request->item_name)[3],
                    'item_name' => (new AESCipher)->decrypt(explode('**', $request->item_name)[0]),
                    'unit_price'    =>  $request->unit_price,
                    'app_type'  => (new AESCipher)->decrypt(explode('**', $request->item_name)[1]),
                    'estimated_price'  => $request->estimated_price,
                    'item_description' =>  $request->item_description,
                    'item_category'     =>  (new AESCipher)->decrypt(explode('**', $request->item_name)[2]),
                    'quantity'  => $request->quantity,
                    'unit_of_measurement'   =>  $request->unit_of_measurement,
                    'mode_of_procurement'   =>  $request->mode_of_procurement,
                    'expected_month'    => (new AESCipher)->decrypt($request->expected_month),
                    'created_at'    =>  Carbon::now(),
                    'updated_at'    =>  Carbon::now(),
                ]);
            return back()->with([
                'success'   => 'Item added as Draft!'
            ]);
    }
    /**
     * 
     * Display the specified resource.
     *
     * this function is accessed using AJAX
     * 
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getItemDescription(Request $request)
    {
        // this will send data to api
        $item_description =  Http::withToken(session('token'))->post(env('APP_API'). "/api/department/fetch-item-description", [
           'item_name'  => (new AESCipher)->encrypt($request->item_name)
        ])->json();

        # this will be sent if status is 200
            if($item_description['status'] == 200) {
                return response()->json([
                    'status'    => $item_description['status'],
                    'data'  => $item_description['data']
                ]);
            }
        # end
        # this will be returned if status is equal tp 400
            if($item_description['status']  == 400) {
                return response()->json([
                    'status'    => $item_description['status'],
                    'message'  => $item_description['message']
                ]);
            }
        # end
    }

    /**
     * 
     * Display the specified resource.
     *
     * this function is accessed using AJAX
     * 
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getItemCategory(Request $request)
    {
        // this will get the item category
            $itemController = (new ItemsController)->show($request->item_name);
        # this will be sent if status is 200
            if($itemController['status'] == 200) {
                return response()->json([
                    'status'    => $itemController['status'],
                    'data'  => $itemController['data']
                ]);
            }
        # end
        # this will be returned if status is equal tp 400
            if($itemController['status']  == 400) {
                return response()->json([
                    'status'    => $itemController['status'],
                    'message'  => $itemController['message']
                ]);
            }
        # end
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePPMPS(Request $request)
    {
       try {
            # form fields validation
            $this->validate($request, [
                # field validation | disabled
                    'item_name'     =>  'required',
                    'estimated_price'     =>  'required',
                    'quantity'  =>  'required',
                    'unit_price'    =>  'required',
                    'item_category'    =>  'required',
                    'item_description'  =>  'required',
                    'mode_of_procurement'  =>  'required',
                    'expected_month'    =>  'required',
                    'unit_of_measurement'  =>  'required',
            ]); 
            $final_expected_date = '';
            if(strlen($request->expected_month) > 1) {
                $final_expected_date = $request->expected_month;
            } else {
                $final_expected_date = (new AESCipher)->encrypt($request->expected_month);
            }

            $final_item_name = '';
            if(strpos($request->item_name, '**') > 0) {
                $final_item_name = $request->item_name;
            } else {
                $final_item_name = $request->item_name;
            }
            # sending the dato to the API
            $ppmp_response =  Http::withToken(session('token'))->post(env('APP_API'). "/api/department/update-ppmps", [
                'id' => $request->id,
                'item_name'  =>  $final_item_name,
                'estimated_price'    =>  $request->estimated_price,
                'unit_price' => $request->unit_price,
                'item_category' =>  $request->item_category,
                'quantity'   => $request->quantity,
                'unit_of_measurement'    => $request->unit_of_measurement,
                'item_description'   =>  $request->item_description,
                'mode_of_procurement'    => $request->mode_of_procurement,
                'expected_month' =>  $final_expected_date
            ])->json();
            # this will be returned if status 200
            if($ppmp_response['status'] == 200) {
                // dd($ppmp_response['message']);
                return back()->with([
                    'success'   =>  $ppmp_response['message']
                ]);
            }
            # this will be returned if status is 400
            if($ppmp_response['status'] == 400) {
                if(count($ppmp_response['message']) > 0) {
                    // dd($ppmp_response['message']['errorInfo'][2]);
                    return back()->with([
                        'failed'   =>  $ppmp_response['message']['errorInfo'][2]
                    ]);
                }
                return back()->with([
                    'failed'   =>  $ppmp_response['message']
                ]);
            }
            
            // return $request;
       } catch (\Throwable $th) {
            throw $th;
       }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePPMPS(Request $request)
    {
       try {
            $response = \DB::table('ppmps')
                ->where('id', intval((new AESCipher)->decrypt($request->id)))
                ->where('employee_id', session('employee_id'))
                ->where('department_id', session('department_id'))
                ->where('campus', session('campus'))
                ->update([
                    'deleted_at' => Carbon::now()
                ]);

            return back()->with([
                'success' => 'Item deleted successfully!'
            ]);
       } catch (\Throwable $th) {
           return back()->with([
            'failed'    =>  'Failed to delete Item. Contact System Admin'
           ]);
       }
    }

    /**
     * Submit PPMP based on the users
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function submitPPMP(Request $request)
    {
        $_deadline_of_submission = Carbon::parse($request->deadline_of_submission)->format('Y-m-d');
        $current_date = Carbon::now()->format('Y-m-d');
        # comparing dates if current date exceeds the allotted deadline of submission
            if($current_date > $_deadline_of_submission) {
                return back()->with([
                    'failed'    => 'You\'ve exceeded the alloted deadline of submission'
                ]);
            } 
        $total_estimated_price = 0.0;
        # this will determine if the project title has items,
            $has_project_items = (new PPMPController)->show($request->current_project_code);
            if($has_project_items['status'] == 400) {
                return back()->with([
                    'failed' => 'The submitted PPMP doesn\'t contains any item(s).'
                ]);
            }
            foreach ($has_project_items['data'] as $item) {
                $total_estimated_price += $item->estimated_price;
            }
            # this will determine the allocated budget
            if($total_estimated_price > doubleval($request->remaining_balance)) {
                return back()->with([
                    'failed' => 'You\'ve exceeded the allocated budget for your department'
                ]);
            }
           if($has_project_items['status'] == 200) {
                # this will submit ppmp 
                $request->merge([
                    'f_remaining_balance' => ( doubleval($request->remaining_balance) - doubleval($total_estimated_price) )
                ]);
                $response = (new PPMPController)->submitPPMP($request);
                return redirect(route('department-showCreatetPPMP'))->with([
                    'success' => $response['message']
                ]);
           } 
    }

    # get ppmps for edit
    public function resubmitPPMP(Request $request)
    {
        $_deadline_of_submission = Carbon::parse($request->deadline_of_submission)->format('Y-m-d');
        $current_date = Carbon::now()->format('Y-m-d');
        # comparing dates if current date exceeds the allotted deadline of submission
            if($current_date > $_deadline_of_submission) {
                return back()->with([
                    'failed'    => 'You\'ve exceeded the alloted deadline of submission'
                ]);
            } 
        $total_estimated_price = 0.0;
        # this will determine if the project title has items,
            $has_project_items = (new PPMPController)->show($request->current_project_code);
            if($has_project_items['status'] == 400) {
                return back()->with([
                    'failed' => 'The submitted PPMP doesn\'t contains any item(s).'
                ]);
            }
            foreach ($has_project_items['data'] as $item) {
                $total_estimated_price += $item->estimated_price;
            }
            # this will determine the allocated budget
            if($total_estimated_price > doubleval($request->remaining_balance)) {
                return back()->with([
                    'failed' => 'You\'ve exceeded the allocated budget for your department'
                ]);
            }
           if($has_project_items['status'] == 200) {
                $request->merge([
                    'remaining_balance' => ( doubleval($request->remaining_balance) - doubleval($total_estimated_price) )
                ]);
                # this will submit ppmp 
                    $response = (new PPMPController)->re_submitPPMP($request);
                return redirect(route('department-showCreatetPPMP'))->with([
                    'success' => $response['message']
                ]);
           } 
    }

}
