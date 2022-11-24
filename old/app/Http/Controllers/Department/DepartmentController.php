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
        // 
        $response = (new ProjectsController)->showDraft($request);
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
                'project_year'     =>  'required',
                'fund_source'  =>  'required',
                'project_type'  =>  'required',
                'immediate_supervisor' => 'required'
        ]); 
         # determine if allocated budget exists
            $allocated_budget = DB::table('allocated__budgets')
            ->where('year','=',(new AESCipher)->decrypt($request->project_year))
            ->where('fund_source_id','=',(new AESCipher)->decrypt($request->fund_source))
            ->get();
            // dd($allocated_budget);
            // $allocated_budget = (new AllocatedBudgetsController)->find($request);
            if(count($allocated_budget) <= 0) {
                return back()->with([
                    'error' => 'No allocated budget/Fund source for that year'
                ]);
            }
            # this will add the allocated budget id to the request
            $request->merge([
                'allocated_budget' => (new AESCipher)->encrypt($allocated_budget[0]->id)
            ]);
         # end
        // this will send data to project titles table using api
            $project_titles = (new ProjectsController)->store($request);
        // this will be returned if status was successful
            if($project_titles['status'] == 200) {
                return back()->with([
                    'success'   => $project_titles['message']
                ]);
            } 
        # this will be returned if not successful
            if($project_titles['status'] == 400) {
                # if there are null data this will retur na page maintenance page
                // return view('pages.page-maintenance')->with(['error' => $mode_of_procurments['message']]);
                Session::put('error', $project_titles['message']);
                return view('pages.error-500');
            }
    }

    /* Store a newly created resource in storage for the Project Titles Table.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function destoryProjectTitle(Request $request)
   {
        # this will send data to project titles table using api
          $project_titles = (new ProjectsController)->destroy((new AESCipher)->decrypt($request->id));
        # end
        # this will be returned if status was successful
           if($project_titles['status'] == 200) {
               return back()->with([
                   'success'   => $project_titles['message']
               ]);
           } 
        # this will be returned if not successful
           if($project_titles['status'] == 400) {
                return back()->with([
                    'error'   => $project_titles['message']
                ]);
           }
   }

   /* Updates a resource in storage for the Project Titles Table.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function updateProjectTitle(Request $request)
    {
        $project_year = $request->project_year;
        $fund_source = $request->fund_source;
        $request->merge([
            'project_year'  => (new AESCipher)->encrypt($project_year),
            'fund_source'   =>  (new AESCipher)->encrypt($fund_source)
        ]);
        $allocated_budget = (new AllocatedBudgetsController)->find($request);
        if(count($allocated_budget['data']) <= 0) {
            return back()->with([
                'error' => 'No allocated budget/Fund source for that year'
            ]);
        }
        # this will add the allocated budget id to the request
        $request->merge([
            'allocated_budget' => (new AESCipher)->encrypt($allocated_budget['data'][0]->id)
        ]);
        $project_titles = (new ProjectsController)->update($request, $request->id);
        // this will be returned if status was successful
            if($project_titles['status'] == 200) {
                return back()->with([
                    'success'   => $project_titles['message']
                ]);
            } 
        # this will be returned if not successful
            if($project_titles['status'] == 400) {
                return back()->with([
                    'error' => $project_titles['message']
                ]);
            }
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
        // calculating estimated price
        // this will send data to project titles table using api
            $create_items =  Http::withToken(session('token'))->post(env('APP_API'). "/api/department/create-pppms", [
                'employee_id'   => (new AESCipher)->encrypt(session('employee_id')), 
                'department_id'   => (new AESCipher)->encrypt(session('department_id')), 
                'project_code'  =>  (new AESCipher)->encrypt(explode('**', $request->item_name)[2]),
                'campus'   => (new AESCipher)->encrypt(session('campus')), 
                'item_name' => explode('**', $request->item_name)[0],
                'unit_price'    =>  $request->unit_price,
                'app_type'  => explode('**', $request->item_name)[1],
                'estimated_price'  => (new AESCipher)->encrypt($request->estimated_price),
                'item_description' =>  $request->item_description,
                'item_category'     =>  $request->item_category,
                'quantity'  => (new AESCipher)->encrypt($request->quantity),
                'unit_of_measurement'   =>  (new AESCipher)->encrypt($request->unit_of_measurement),
                'mode_of_procurement'   =>  (new AESCipher)->encrypt($request->mode_of_procurement),
                'expected_month'    => $request->expected_month
            ])->json();
           
        // this will be returned if status was successful
            if($create_items['status'] == 200) {
                return back()->with([
                    'success'   => $create_items['message']
                ]);
            } 
        # this will be returned if not successful
            if($create_items['status'] == 400) {
                return back()->with([
                    'failed'   => $create_items['message']
                ]);
            }
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
            $response = (new PPMPController)->destroy($request);
            return back()->with([
                'success' => $response['message']
            ]);
            // return $request; 
       } catch (\Throwable $th) {
            throw $th;
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
        // dd(doubleval($request->remaining_balance));
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
                        'remaining_balance' => $request->remaining_balance
                    ]);
                $response = (new PPMPController)->submitPPMP($request);
                return redirect(route('department-showCreatetPPMP'))->with([
                    'success' => $response['message']
                ]);
           } 

          
    }
}
