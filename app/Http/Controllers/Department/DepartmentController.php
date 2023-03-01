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
use App\Http\Controllers\HistoryLogController;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use DB;
use Validator;

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
     * * Submit PPMP | Create Project Title
     * TODO: Enable the submission of project title
     * TODO: Save project title as draft status
     */
    public function createProjectTitle(Request $request)
    {
        try {
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
                'project_category'  =>  (new AESCipher)->decrypt($request->project_category), 
                'project_type'  => $request->project_type,
                'status'    => 0,
                'year_created'  => Carbon::now()->format('Y'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);

            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus_id'),
                    null,
                    'Created Project Title as draft! No Items!',
                    'Create',
                    $request->ip(),
                );
            # end

            # this will be returned if status was successful
            if($project_titles == true) {
                return back()->with([
                    'success'   => 'Project Title Created Succesfully!'
                ]);
            } 
            return back()->with([
                'error'   => 'Project Title Created Failed'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return view('pages.error-500');
        }
    }

   /**
    * ! Delete created project title
    * TODO 1: get the ppmps or added items from that project title
    * TODO 2: get total estimated price
    * TODO 3: get allocated budget of the project
    * TODO 4: return the estimated price from the project to the remaning balance
    * TODO 5: delete project title & ppmps based on project id
    * TODO 6: Determine if project belongs the current login user
    * ? KEY: project id | id
    */
   public function destoryProjectTitle(Request $request)
   {
        try {
            // TODO 6
                $whos_project = \DB::table('project_titles')
                    ->where('id', ( new AESCipher)->decrypt($request->id))
                    ->where('campus', session('campus'))
                    ->where('department_id', session('department_id'))
                    ->whereNull('deleted_at')
                    ->get([
                        'employee_id'
                    ]);
                if($whos_project[0]->employee_id != session('employee_id')) {
                    return back()->with([
                        'failed' => 'You\'re not authorized to make changes to this project. Please contact the rightful author.'
                    ]);
                }
            
            // TODO: 1
                $ppmps = \DB::table('ppmps')
                    ->where('project_code', (new AESCipher)->decrypt($request->id))
                    ->where('campus', session('campus'))
                    ->where('employee_id', session('employee_id'))
                    ->where('department_id', session('department_id'))
                    ->whereNull('deleted_at')
                    ->get([
                        'estimated_price'
                    ]);
            // TODO: END
            // TODO: 2
                if(count($ppmps) > 0) {
                    $total_estimated_price = 0.0; // * this will hold the total estimated price for that project
                    // * compute the total estimated price
                    foreach ($ppmps as $item) {
                        $total_estimated_price += $item->estimated_price;
                    }
                }
            // TODO: END
            // TODO: 3
                // * search the project based on id
                $project_title = \DB::table('project_titles')
                    ->where('id', (new AESCipher)->decrypt($request->id))
                    ->where('campus', session('campus'))
                    ->where('employee_id', session('employee_id'))
                    ->where('department_id', session('department_id'))
                    ->get();
            // TODO: END
            // TODO: 4
               if(count($ppmps) > 0) {
                // * return the estimated price the remaining balance
                $allocated_budgets = \DB::table('allocated__budgets')
                    ->where('campus', session('campus'))
                    ->where('id', $project_title[0]->allocated_budget)
                    ->update([
                        'remaining_balance' => $total_estimated_price
                    ]);
               }
            // TODO: END
            # delete project title
                \DB::table('project_titles')
                    ->where('id', (new AESCipher)->decrypt($request->id))
                    ->where('campus', session('campus'))
                    ->where('employee_id', session('employee_id'))
                    ->where('department_id', session('department_id'))
                    ->update([
                        'deleted_at' => Carbon::now()
                    ]);
            # delete ppmps table
                \DB::table('ppmps')
                    ->where('project_code', (new AESCipher)->decrypt($request->id))
                    ->where('campus', session('campus'))
                    ->where('employee_id', session('employee_id'))
                    ->where('department_id', session('department_id'))
                    ->whereNull('deleted_at')
                    ->update([
                        'deleted_at' => Carbon::now()
                    ]);
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    (new AESCipher)->decrypt($request->id),
                    'Destroyed Project',
                    'Destory',
                    $request->ip(),
                );
            # end
            return back()->with([
                'success'   => 'Project Successfully deleted!'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return view('pages.error-500');
        }
   }

   /**
    * * Update Project title
    * TODO 1: Determine if project belongs the current login user
    * TODO 2: Enable project update
    * TODO 3: Store history log
    */
    public function updateProjectTitle(Request $request)
    {
        try {
            // TODO 1
                $whos_project = \DB::table('project_titles')
                    ->where('id',  $request->id)
                    ->where('campus', session('campus'))
                    ->where('department_id', session('department_id'))
                    ->whereNull('deleted_at')
                    ->get([
                        'employee_id'
                    ]);
                if($whos_project[0]->employee_id != session('employee_id')) {
                    return back()->with([
                        'failed' => 'You\'re not authorized to make changes to this project. Please contact the rightful author.'
                    ]);
                }
            // TODO 2
                $project_titles = \DB::table('project_titles')
                    ->where('id', $request->id)
                    ->where('campus', session('campus'))
                    ->where('employee_id', session('employee_id'))
                    ->where('department_id', session('department_id'))
                    ->whereNull('deleted_at')
                    ->update([
                        'project_title' => $request->project_title,
                        'project_type' => $request->project_type,
                        'project_year' =>explode('**', $request->fund_source)[1],
                        'fund_source' => explode('**', $request->fund_source)[0],
                        'allocated_budget' => explode('**', $request->fund_source)[2],
                    ]);
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    $request->id,
                    'Updated Project project title',
                    'update',
                    $request->ip(),
                );
            # end
            # return positive response
                return back()->with([
                    'success'   => 'Project Updated Successfully!'
                ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    /**
     * * Submit PPMP | add item to project
     * TODO 1: Validate Input
     * TODO 2: Check if estimated price has exceeded the remaining balance
     * TODO 3: Check if project has exceeded the deadline of submission
     * TODO 4: Add items to project | Implement insert
     * TODO 5: Insert data to history logs
     * TODO 6: Get allocated budget based on id
     * ? ----------------------------------------
     * ? KEY 1: Check if project is for request ppmp submission or else
     * * if project is for request ignore the deadline of submission
     * ? KEY 2: deterine the deadline of submission
     * ? KEY 3: get allocated budget that has the same procurement type as the project title
     * ? KEY 4: check if allocated budgets deadline of submision is null
     * * if allocated budgets deadline of submission is null, then set ppmp deadline end date as deadline of submission
     */
    public function createPPMPs(Request $request)
    {
        try {
            $_deadline_of_submission; //* this will hold the final deadline of submission
            $__total_estimated_price; //* this will hold the final total estimated price
            // TODO 1
                $this->validate($request, [
                    // * field validation | disabled
                    'item_name'     =>  'required',
                    'item_category' =>  'required',
                    'app_type' =>  'required',
                    'unit_price'     =>  'required',
                    'estimated_price'     =>  'required',
                    'quantity'  =>  'required',
                    'item_description'  =>  'required',
                    'mode_of_procurement'  =>  'required',
                    'expected_month'    =>  'required',
                    'unit_of_measurement'  =>  'required',
                ]); 
             // TODO 6
                $allocated_budgets = \DB::table('allocated__budgets')
                ->join('ppmp_deadline', 'ppmp_deadline.year', 'allocated__budgets.year')
                //* allocated budgets
                    ->where('allocated__budgets.campus', session('campus'))
                    ->where('allocated__budgets.department_id', session('department_id'))
                    ->where('allocated__budgets.id', (new AESCipher)->decrypt($request->allocated_budget))
                    ->whereNull('allocated__budgets.deleted_at')
                //* ppmp deadline
                    ->whereNull('ppmp_deadline.deleted_at')
                    ->where('ppmp_deadline.campus', session('campus'))
                // ? KEY 3
                    ->where('ppmp_deadline.procurement_type', (new AESCipher)->decrypt($request->project_category))
                    ->where('allocated__budgets.procurement_type', (new AESCipher)->decrypt($request->project_category))
                ->get([
                    'allocated__budgets.remaining_balance',
                    'allocated__budgets.deadline_of_submission',
                    'ppmp_deadline.start_date',
                    'ppmp_deadline.end_date',
                ]);
                // ? KEY 4
                    if( $allocated_budgets[0]->deadline_of_submission == null ) {
                        $_deadline_of_submission = $allocated_budgets[0]->end_date;
                    } else {
                        $_deadline_of_submission = $allocated_budgets[0]->deadline_of_submission;
                    }
            // TODO 2
                $__total_estimated_price = $request->total_estimated_price + doubleval($request->estimated_price);
                if(\doubleval($__total_estimated_price) > doubleval($allocated_budgets[0]->remaining_balance)) {
                    return back()->with([
                        'failed' => 'Your total estimated price has exceeded your remaining balance. Please make the necessary adjustments!'
                    ]);
                }
            // TODO 3
                $current_date = Carbon::now()->format('Y-m-d');
                if($current_date > $_deadline_of_submission) { // * if current date is greater than deadline of submission
                    return back()->with([
                        'failed'    => 'You\'ve exceeded the alloted deadline of submission.'
                    ]);
                } 
            // TODO 4
                \DB::table('ppmps')
                    ->insert([
                        'employee_id' => session('employee_id'),
                        'department_id' => session('department_id'),
                        'campus' => session('campus'),
                        'project_code'  =>  (new AESCipher)->decrypt($request->project_code),
                        'item_name' => $request->item_name,
                        'unit_price'    =>  $request->unit_price,
                        'app_type'  => $request->app_type,
                        'estimated_price'  => $request->estimated_price,
                        'item_description' =>  $request->item_description,
                        'item_category'     =>  $request->item_category,
                        'quantity'  => $request->quantity,
                        'unit_of_measurement'   =>  $request->unit_of_measurement,
                        'mode_of_procurement'   =>  $request->mode_of_procurement,
                        'expected_month'    => (new AESCipher)->decrypt($request->expected_month),
                        'created_at'    =>  Carbon::now(),
                        'updated_at'    =>  Carbon::now(),
                    ]);
            // TODO 5
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    (new AESCipher)->decrypt($request->project_code),
                    'Create PPMPs or add items on project',
                    'create',
                    $request->ip(),
                );
            
            return back()->with([
                'success'   => 'Item added as Draft!'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return view('pages.error-500');
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
        $item_description = \DB::table('ppmps')
            ->join('users', 'users.employee_id', 'ppmps.employee_id')
            ->where('ppmps.item_name', $request->item_name)
            ->whereNull('ppmps.deleted_at')
            ->whereNull('users.deleted_at')
            ->get([
                'ppmps.item_description',
                'ppmps.unit_price',
                'ppmps.unit_of_measurement',
                'ppmps.quantity',
                'ppmps.estimated_price',
                'users.name'
            ]);
        
        if($item_description) {
            return \json_decode($item_description);
        }
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
            # update ppmps
            $response = \DB::table('ppmps')
                ->where('id', $request->id)
                ->where('campus', session('campus'))
                ->where('department_id', session('department_id'))
                ->where('employee_id', session('employee_id'))
                ->update([
                    'item_name' => $request->item_name,
                    'item_category' => $request->item_category,
                    'app_type'  =>  $request->app_type,
                    'unit_of_measurement'   =>  $request->unit_of_measurement,
                    'quantity'  => $request->quantity,
                    'unit_price'    => $request->unit_price,
                    'estimated_price'   => $request->estimated_price,
                    'item_description'  => $request->item_description,
                    'mode_of_procurement'   => $request->mode_of_procurement,
                    'expected_month'    => $request->expected_month,
                    'updated_at'    => Carbon::now()
                ]);
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    $request->id,
                    'Update PPMPs or add items on project',
                    'Update',
                    $request->ip(),
                );
            # end
            return back()->with([
                'success'   => 'Item details updated successfully!'
            ]);

       } catch (\Throwable $th) {
            // throw $th;
            // return view('pages.error-500');
            return back()->with([
                'failed'   => 'Failed to update item details!'
            ]);
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
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    (new AESCipher)->decrypt($request->id),
                    'Delete PPMPs or add items on project',
                    'Delete',
                    $request->ip(),
                );
            # end
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
     * * Submit PPMP | Create title
     * TODO 1: Determine if project has exceeded its deadline of submission
     * TODO 2: Test if project has items  
     * TODO 3: Get the total estimated price
     * TODO 4: Compare if project total estimated price has exceeded its remaining balance
     * TODO 5: Get/Calculcate final remaining balance
     * TODO 6: Enable submission
     * TODO 7: Insert to history log
     * * if null, then check if ppmp deadline of submission end date is not null
     */
    public function submitPPMP(Request $request)
    {
        try {
            // TODO 1
            $_deadline_of_submission = Carbon::parse($request->deadline_of_submission)->format('Y-m-d');
            $current_date = Carbon::now()->format('Y-m-d');
                if($current_date > $_deadline_of_submission) {
                    return back()->with([
                        'failed'    => 'You\'ve exceeded the deadline of submission'
                    ]);
                } 
            $total_estimated_price = 0.0;
            // TODO 2
            $has_project_items = (new PPMPController)->show($request->current_project_code);
                if($has_project_items['status'] == 400) {
                    return back()->with([
                        'failed' => 'The submitted PPMP doesn\'t contains any item(s).'
                    ]);
                }
            // TODO 3
                foreach ($has_project_items['data'] as $item) {
                    $total_estimated_price += $item->estimated_price;
                }
            // TODO 4
                if($total_estimated_price > doubleval($request->remaining_balance)) {
                    return back()->with([
                        'failed' => 'You\'ve exceeded the allocated budget for your department'
                    ]);
                }
                if($has_project_items['status'] == 200) {
                    // TODO 5
                    $request->merge([
                        'f_remaining_balance' => ( doubleval($request->remaining_balance) - doubleval($total_estimated_price) )
                    ]);
                    // TODO 6
                    $response = (new PPMPController)->submitPPMP($request);
                    # this will created history_log
                        (new HistoryLogController)->store(
                            session('department_id'),
                            session('employee_id'),
                            session('campus'),
                            (new AESCipher)->decrypt($request->current_project_code),
                            'Submit PPMPs for Immediate\s supervisor acceptance',
                            'Submit',
                            $request->ip(),
                        );
                    # end
                    // this will update the procurement type of the allocated budget
                    return redirect(route('derpartment-project-category'))->with([
                        'success' => $response['message']
                    ]);
                } 
        } catch (\Throwable $th) {
            return view('pages.error-500');
        }
    }

    # get ppmps for edit
    public function resubmitPPMP(Request $request)
    {
        try {
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
                $has_project_items = \DB::table('ppmps')
                    ->where('project_code', (new AESCipher)->decrypt($request->current_project_code))
                    ->where('campus', session('campus'))
                    ->where('department_id', session('department_id'))
                    ->where('employee_id', session('employee_id'))
                    ->whereRaw("status = '3' OR status = '5'")
                    ->whereNull('deleted_at')
                    ->get();

                if(count($has_project_items) <= 0) {
                    return back()->with([
                        'failed' => 'The submitted PPMP doesn\'t contains any item(s).'
                    ]);
                }
                foreach ($has_project_items as $item) {
                    $total_estimated_price += $item->estimated_price;
                }
                # this will determine the allocated budget
                if($total_estimated_price > doubleval($request->remaining_balance)) {
                    return back()->with([
                        'failed' => 'You\'ve exceeded the allocated budget for your department'
                    ]);
                }
            if($has_project_items) {
                $request->merge([
                    'f_remaining_balance' => ( doubleval($request->remaining_balance) - doubleval($total_estimated_price) )
                ]);
                # this will submit ppmp 
                    $response = (new PPMPController)->re_submitPPMP($request);
                # this will created history_log
                    (new HistoryLogController)->store(
                        session('department_id'),
                        session('employee_id'),
                        session('campus'),
                        (new AESCipher)->decrypt($request->current_project_code),
                        'Re-submit PPMPs for Immediate\s supervisor acceptance',
                        'Re-submit',
                        $request->ip(),
                    );
                # end
                return redirect(route('department-showCreatetPPMP'))->with([
                    'success' => $response['message']
                ]);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return view('pages.error-500');
        } 
    }

    # get items 
    public function get_items() {
        $items = \DB::table('items')
                ->join('mode_of_procurement', 'mode_of_procurement.id', 'items.mode_of_procurement_id')
                ->whereNull('mode_of_procurement.deleted_at')
                ->whereNull('items.deleted_at')
                ->get();
        if($items) {
            return \json_decode($items);
        }
    }

    # get specified mode of procurement
    public function get_mode_of_procurement(Request $request) {
        $response = \DB::table('mode_of_procurement')
            ->where('id', $request->id)
            ->get();

        if($request) {
            return \json_decode($response);
        }
    }

    # get specified mode 
    public function get_ppmps(Request $request) {
        $ppmp_response = \DB::table('ppmps')
            ->join('mode_of_procurement', 'mode_of_procurement.id', 'ppmps.mode_of_procurement')
            ->where('ppmps.id', $request->id)
            ->where('ppmps.campus', session('campus'))
            ->where('ppmps.department_id', session('department_id'))
            ->where('ppmps.employee_id', session('employee_id'))
            ->whereNull('ppmps.deleted_at')
            ->get([
                'ppmps.*',
                'mode_of_procurement.*',
                'ppmps.id as ppmps_id',
                'mode_of_procurement.id as procurement_id',
            ]);

        return \json_decode($ppmp_response);
    }

    /**
     * * Submit PPMP | Submit all projects
     * TODO 1: Get all project tiles with the same allocated budgets
     * TODO 2: Eleminate project titles that don't have the same allocated budget
     * TODO 3: Get items or ppmps for each project titles
     * TODO 4: Check if there are project that doesn't contain any items or ppmps
     * TODO 5: Get the total estimated price per project
     * TODO 6: Subtract total estimated price per project to their respective allocated budgets
     * TODO 7: Update the statuses for projects and corresponding ppmps / items
     * TODO 8: update remaining balance of the allocated budget
     * TODO 9: insert to project timeline
     * TODO 10: insert to history log
     * ? -----------------
     * ? KEY 1: get allocated budget by joining project titles and allocated budgets table.
     * * each project title mas be determined by their allocated budget value or ID
     * ? KEY 2: Get deadline of submission by including ppmp deadline on table join
     * ? KEY 3: Get items that are 
     */
    public function submit_all_projects(Request $request) {
        try {
            $_final_project_title = array(); //* this hold all the project titles that will be tested based on ther allocated budgets
            $_final_allocated_budget = array(); //* this hold all the project titles that will be tested based on ther allocated budgets
            $total_estimated_price = 0.0;
            $final_remaining_balance = 0.0; // * this will hold the final remaining balance 
            $__total_estimated_price = array();
            $arr_count = array();
            $index = 0; //* this will hold the number of index that foreach has looped
            // TODO 1
            foreach ($request->allocated_budget as $item) {
                $project_titles = \DB::table('project_titles')
                    ->join('allocated__budgets', 'allocated__budgets.id', 'project_titles.allocated_budget')
                    ->join('ppmp_deadline', 'ppmp_deadline.year', 'allocated__budgets.year') // ? KEY 2
                    //* project titles
                    ->where('project_titles.employee_id', session('employee_id'))
                    ->where('project_titles.campus', session('campus'))
                    ->where('project_titles.department_id', session('department_id'))
                    ->whereNull('project_titles.deleted_at')
                    //* allocated budgets
                    ->where('allocated__budgets.campus', session('campus'))
                    ->where('allocated__budgets.department_id', session('department_id'))
                    ->whereNull('allocated__budgets.deleted_at')
                    //* ppmp deadline
                    ->whereNull('ppmp_deadline.deleted_at')
                    // ? KEY 1
                    // ->where('project_titles.id', $request->project_titles[$index])
                    ->where('project_titles.allocated_budget', $item)
                    ->where('allocated__budgets.id',  $item)
                    ->where('project_titles.project_category', $request->procurement_type[$index])
                    ->where('allocated__budgets.procurement_type', $request->procurement_type[$index])
                    ->where('ppmp_deadline.procurement_type', $request->procurement_type[$index])
                    ->get([
                        'project_titles.*',
                        'allocated__budgets.remaining_balance',
                        'allocated__budgets.deadline_of_submission',
                        'ppmp_deadline.end_date'
                    ]);
                // TODO 2
                for ($i=0; $i < count($request->allocated_budget); $i++) { 
                    if($request->allocated_budget[$i] == $item) {
                        array_push($_final_project_title, $request->project_titles[$i]);
                    }
                }
                // TODO 3
                for ($x=0; $x < count($_final_project_title); $x++) { 
                    $ppmps = \DB::table('ppmps')
                        ->where('project_code', $_final_project_title[$x])
                        ->where('campus', session('campus'))
                        ->where('employee_id', session('employee_id'))
                        ->where('department_id', session('department_id'))
                        ->whereNull('deleted_at')
                        ->get([
                            'estimated_price'
                        ]);
                    // TODO 4
                    if(count($ppmps) <= 0) {
                        return ([
                            'status'    => 400,
                            'message'   => 'Some of the projects doesn\'t contain any items!'
                        ]);
                    }
                    // TODO 5
                    foreach ($ppmps as $_ppmps) {
                        $total_estimated_price += \doubleval($_ppmps->estimated_price);
                    }
                }
               
                // TODO 6
                $final_remaining_balance = $project_titles[0]->remaining_balance - $total_estimated_price;
                if($total_estimated_price > $project_titles[0]->remaining_balance) {
                    return ([
                        'status'    => 400,
                        'message'   => 'Some projects have exceeded their remaining balance!'
                    ]);
                } else {
                    // TODO 7
                    foreach ($_final_project_title as $_item) {
                        //* update status of project titles
                        \DB::table('project_titles')
                        ->where('id', $_item)
                        ->where('department_id', session('department_id'))
                        ->where('employee_id', session('employee_id'))
                        ->where('campus', session('campus'))
                        ->update([
                            'status'    => 1,
                            'updated_at'    => Carbon::now()
                        ]);
                        //* update status of ppmps / items
                        \DB::table('ppmps')
                        ->where('project_code', $_item)
                        ->where('department_id', session('department_id'))
                        ->where('employee_id', session('employee_id'))
                        ->where('campus', session('campus'))
                        ->update([
                            'status'    => 1, //* change project status to pending for supervisor's acceptance
                            'updated_at'    => Carbon::now()
                        ]);
                        // TODO 8
                        \DB::table('allocated__budgets')
                        ->where('id', $item)
                        ->update([
                            'remaining_balance' => $final_remaining_balance,
                            // 'procurement_type' => 2, //* convert allocated to supplemental
                            'updated_at'    => Carbon::now()
                        ]);
                        // TODO 9
                        \DB::table('project_timeline')
                        ->insert([
                            'employee_id'   => session('employee_id'),
                            'department_id' => session('department_id'),
                            'role'  =>  \session('role'),
                            'campus'  => \session('campus'),
                            'project_id'    => $_item,
                            'status'    => intval(1),
                            'remarks'   =>   'Your PPMP is currently Pending of Immediates Supervisor\'s Acceptance.',
                            'created_at'    =>  Carbon::now(),
                            'updated_at'    =>  Carbon::now(),
                        ]);
                        // TODO 10
                        (new HistoryLogController)->store(
                            session('department_id'),
                            session('employee_id'),
                            session('campus'),
                            (new AESCipher)->decrypt($request->current_project_code),
                            'Submit All Project for Immediate\s supervisor acceptance',
                            'Submit',
                            $request->ip(),
                        );
                    }
                }
                $index += 1;
            }
            return ([
                'status'    => 200,
                'message'   => 'Project(s) submitted for Supervisor\'s Acceptance!'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            // return view('pages.error-500');
        }
    }

    /* Generate or Export PPMP File
     * PDF
     */
    public function export_approved_ppmp(Request $request) {
       try {
            # project title
                $project_title = \DB::table('project_titles')
                    ->where('campus', session('campus'))
                    ->where('employee_id', session('employee_id'))
                    ->where('department_id', session('department_id'))
                    ->where('id', (new AESCipher)->decrypt($request->id))
                    ->where('status', 4)
                    ->whereNull('deleted_at')
                    ->get();
            # ppmps
                $ppmps = \DB::table('ppmps')
                    ->join('mode_of_procurement', 'mode_of_procurement.id', 'ppmps.mode_of_procurement')
                    ->where('ppmps.campus', session('campus'))
                    ->where('ppmps.employee_id', session('employee_id'))
                    ->where('ppmps.department_id', session('department_id'))
                    ->where('ppmps.project_code', (new AESCipher)->decrypt($request->id))
                    ->where('ppmps.status', 4)
                    ->whereNull('ppmps.deleted_at')
                    ->get([
                        'ppmps.*',
                        'mode_of_procurement.abbreviation'
                    ]);
           
            $pdf = \Pdf::loadView('pages.department.export-ppmp', compact('project_title', 'ppmps'))->setPaper('a4', 'portrait');
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    null,
                    'Exported approved ppmp',
                    'Export',
                    $request->ip(),
                );
            # end
            return $pdf->download('PPMP_' . Carbon::now() . '.pdf'); 
            // return view('pages.department.export-ppmp', compact('project_title', 'ppmps'));
       } catch (\Throwable $th) {
            // throw $th;
            return view('pages.error-500');
       }
    }

    /* Upload Signed PPMP
     * - Creating files for Signed PPMP 
     * - Downloadable PPMP
    */
    public function upload_ppmp(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'project_category'  => ['required'],
                'year_created'  =>  ['required'],
                'file_name' => ['required'],
                'signed_ppmp'   => ['required', 'mimes:pdf', 'max:2048']
            ]);
            if($request->hasFile('signed_ppmp')) {
                $file = $request->file('signed_ppmp');
                $extension = $request->file('signed_ppmp')->getClientOriginalExtension();
               
                $is_valid = false;
                # validate extension
                    $allowed_extensions = ['pdf'];
                    // $allowed_extensions = ['pdf', 'jpeg', 'jpg', 'png'];
                    for ($i = 0; $i < count($allowed_extensions) ; $i++) { 
                       if($allowed_extensions[$i] == $extension) {
                            $is_valid = true;
                       }
                    }
                    if($is_valid == false) {
                        return back()->with([
                            'error' => 'Invalid file format!'
                        ]);
                    }
                # end
                # moving of the actual file
                    $file_name = (new GlobalDeclare)->project_category((new AESCipher)->decrypt($request->project_category)) .'-'. time();
                    $destination_path = env('APP_NAME').'\\department_upload\\signed_ppmp\\';
                    if (!\Storage::exists($destination_path)) {
                        \Storage::makeDirectory($destination_path);
                    }
                    $file->storeAs($destination_path, $file_name.'.'.$extension);
                    $file->move('storage/'. $destination_path, $file_name.'.'.$extension);
                # end
                # storing data to signed_ppmp table
                    \DB::table('signed_ppmp')
                    ->insert([
                        'employee_id'   => session('employee_id'),
                        'department_id'   => session('department_id'),
                        'campus'   => session('campus'),
                        'year_created'   => (new AESCipher)->decrypt($request->year_created),
                        'project_category'  => (new AESCipher)->decrypt($request->project_category),
                        'file_name'   => $request->file_name,
                        'file_directory'    => $destination_path .''. $file_name.'.'.$extension,
                        'signed_ppmp' =>  $file_name.'.'.$extension,
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now()
                    ]);
                # this will created history_log
                    (new HistoryLogController)->store(
                        session('department_id'),
                        session('employee_id'),
                        session('campus'),
                        null,
                        'Uploaded sigend ppmp',
                        'upload',
                        $request->ip(),
                    );
                # end
                return back()->with([
                    'success' => 'PPMP uploaded successfully!'
                ]);
            } else {
                return back()->with([
                    'error' => 'Please fill the form accordingly!'
                ]);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return view('pages.error-500');
        }
    }

    /** 
     * * Download Uplooded PPMP
     * - this will enable downlaod uploade PPMP
     * - based on: Employee id, campus, department_id
     * TODO: get file from storage upload id search
     */
    public function download_uploaded_PPMP(Request $request) {
        try {
            $response = \DB::table('signed_ppmp')
                ->where('employee_id', session('employee_id'))
                ->where('department_id', session('department_id'))
                ->where('campus', session('campus'))
                ->where('id', (new AESCipher)->decrypt($request->id))
                ->whereNull('deleted_at')
                ->get([
                    'signed_ppmp',
                    'file_directory'
                ]);
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    null,
                    'Downloaded sigend ppmp',
                    'Download',
                    $request->ip(),
                );
            # end
            // return \Storage::download('storage\\'.$response[0]->file_directory);
            return response()->download('storage\\'.$response[0]->file_directory, $response[0]->signed_ppmp);
        } catch (\Throwable $th) {
            throw $th;
            return view('pages.error-500');
        }
    }
    /* Delete Uploaded Signed PPMP
     * - this will delete the uploaded PPMP
     * - based on: Employee id, campus, department_id
     */
    public function delete_uploaded_ppmp(Request $request) {
        try {
            $response = \DB::table('signed_ppmp')
            ->where('employee_id', session('employee_id'))
            ->where('department_id', session('department_id'))
            ->where('campus', session('campus'))
            ->where('id', (new AESCipher)->decrypt($request->id))
            ->whereNull('deleted_at')
            ->update([
                'updated_at'    => Carbon::now(),
                'deleted_at'    => Carbon::now()
            ]);
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    (new AESCipher)->decrypt($request->id),
                    'Deleted sigend ppmp',
                    'Delete',
                    $request->ip(),
                );
            # end
            return back()->with([
                'success'   => 'Uploaded PPMP successfully deleted!'
            ]);
        } catch (\Throwable $th) {
            return view('pages.error-500');
            // throw $th;
        }
    }

    /* View uploaded PPMP
     * - this will allow preview of the uploaded PPMP
     */
    public function view_uploaded_ppmp(Request $request) {
        try {
            $response = \DB::table('signed_ppmp')
                ->where('employee_id', session('employee_id'))
                ->where('department_id', session('department_id'))
                ->where('campus', session('campus'))
                ->where('id', (new AESCipher)->decrypt($request->id))
                ->whereNull('deleted_at')
                ->get([
                    'file_directory',
                    'signed_ppmp'
                ]);
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    (new AESCipher)->decrypt($request->id),
                    'Viewed sigend ppmp',
                    'View',
                    $request->ip(),
                );
            # end
            return response ([
                'status'    => 200,
                'data'  => $response
            ]);
        } catch (\Throwable $th) {
            return view('pages.error-500');
            throw $th;
        }
    }

    /* GET Edit Uploaded PPMP
     * - get uploaded ppmp for edit feature
     */
    public function get_edit_ppmp(Request $request) {
        try {
            $response = \DB::table('signed_ppmp')
                ->where('employee_id', session('employee_id'))
                ->where('department_id', session('department_id'))
                ->where('campus', session('campus'))
                ->where('id', (new AESCipher)->decrypt($request->id))
                ->whereNull('deleted_at')
                ->get();

            return ([
                'status'    => 200,
                'data'  => $response
            ]);
           
        } catch (\Throwable $th) {
            return view('pages.error-500');
            throw $th;
        }
    }

    /* GET Edit Uploaded PPMP
     * - edit / update uploaded ppmp
     * ! wadwadaw
     * ? awdawdaw
     */
    public function edit_uploaded_ppmp(Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'project_category'  => ['required'],
                'year_created'  =>  ['required'],
                'file_name' => ['required'],
                'signed_ppmp'   => ['required', 'mimes:pdf, jpeg, jpg, png', 'max:2048']
            ]);
            if($request->hasFile('signed_ppmp')) {
                $file = $request->file('signed_ppmp');
                $extension = $request->file('signed_ppmp')->getClientOriginalExtension();
                $is_valid = false;
                # validate extension
                    $allowed_extensions = ['pdf', 'jpeg', 'jpg', 'png'];
                    for ($i = 0; $i < count($allowed_extensions) ; $i++) { 
                       if($allowed_extensions[$i] == $extension) {
                            $is_valid = true;
                       }
                    }
                    if($is_valid == false) {
                        return back()->with([
                            'error' => 'Invalid file format!'
                        ]);
                    }
                # end
                $file_name = (new GlobalDeclare)->project_category((new AESCipher)->decrypt($request->project_category)) .'-'. time();
                $destination_path = env('APP_NAME').'\\department_upload\\signed_ppmp\\';
                if (!\Storage::exists($destination_path)) {
                    \Storage::makeDirectory($destination_path);
                }
                $file->storeAs($destination_path, $file_name.'.'.$extension);
                // \Storage::put($destination_path, $file_name.'.'.$extension);
                $file->move('storage/'. $destination_path, $file_name.'.'.$extension);
                # storing data to signed_ppmp table
                    \DB::table('signed_ppmp')
                    ->where('id', $request->id)
                    ->update([
                        'year_created'   => (new AESCipher)->decrypt($request->year_created),
                        'project_category'  => (new AESCipher)->decrypt($request->project_category),
                        'file_name'   => $request->file_name,
                        'file_directory'    => $destination_path .''. $file_name.'.'.$extension,
                        'signed_ppmp' =>  $file_name.'.'.$extension,
                        'updated_at'    => Carbon::now()
                    ]);
                # this will created history_log
                    (new HistoryLogController)->store(
                        session('department_id'),
                        session('employee_id'),
                        session('campus'),
                        null,
                        'Viewed sigend ppmp',
                        'View',
                        $request->ip(),
                    );
                # end
                return back()->with([
                    'success' => 'PPMP uploaded successfully!'
                ]);
            } else {
                return back()->with([
                    'error' => 'Please fill the form accordingly!'
                ]);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return view('pages.error-500');
        }
    }

    /* Live search item
     * - this will enable search item on textchange
     */
    public function live_search_item(Request $request) {
        try {
            $response = \DB::table('items')
            ->join('mode_of_procurement', 'mode_of_procurement.id', 'items.mode_of_procurement_id')
            ->whereNull('mode_of_procurement.deleted_at')
            ->whereNull('items.deleted_at')
            ->where('item_name', 'like', '%'.$request->item_name.'%')
            ->get();

            if(count($response) > 0) {
                return $response;
            } 
            # return error or zero item(s) found
            return 400;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * * Request for PPMP Submission
     * TODO 1: Get the project title id
     * TODO 2: Valdate the request fields
     * TODO 3: Enable Submission of request
     * TODO 4: Save log to history logs table
     * ? ---------------
     * ? KEY 1: project title id is included with the requests
     */
    public function request_submission(Request $request) {
        try {
            // TODO 2
                $this->validate($request, [
                    'reason' => 'required'
                ]);
            // TODO 3
                \DB::table('ppmp_request_submission')
                    ->insert([
                        'employee_id' => session('employee_id'),
                        'department_id' => session('department_id'),
                        'campus' => session('campus'),
                        'allocated_budget' =>  $request->allocated_budget,
                        'reason'    => $request->reason,
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now()
                    ]);
            // TODO 4
                # this will created history_log
                    (new HistoryLogController)->store(
                        session('department_id'),
                        session('employee_id'),
                        session('campus'),
                        (new AESCipher)->decrypt($request->project_id),
                        'Submitted a request for PPMP submission',
                        'Created',
                        $request->ip(),
                    );
                # end  
            return redirect(route('show_ppmp_submission'))->with([
                'success'   => 'Project has been requested for PPMP Submission'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return view('pages.error-500');
        }
    }

    /**
     * * Get allocated budget
     * TODO 1: Get allocate budget based on procurement type
     * TODO 2: Join Allocated budgets and ppmp request submission
     * ? ----------
     * ? KEY 1: Check if the ppmp deadline exceeds the current date
     * ? KEY 2: Check if allocated budget has already been requested for reopening?
     */
    public function get_allocated_budget(Request $request) {
        try {
            // TODO 1
            $allocated_budgets = \DB::table('allocated__budgets')
                ->join('ppmp_request_submission', 'ppmp_request_submission.allocated_budget', '!=', 'allocated__budgets.id') // TODO 2: 
                ->join('fund_sources', 'fund_sources.id', 'allocated__budgets.fund_source_id')
                ->join('ppmp_deadline', 'ppmp_deadline.year', 'allocated__budgets.year')
                ->where('allocated__budgets.campus', session('campus'))
                ->where('allocated__budgets.department_id', session('department_id'))
                ->whereNull('allocated__budgets.deleted_at')
                ->whereNull('fund_sources.deleted_at')
                ->whereNull('ppmp_deadline.deleted_at')
                ->where('allocated__budgets.procurement_type', (new AESCipher)->decrypt($request->procurement_type))
                ->where('ppmp_deadline.procurement_type', (new AESCipher)->decrypt($request->procurement_type))
                ->where('ppmp_deadline.end_date', '<', Carbon::now()->format('Y-m-d'))
                ->whereNull('allocated__budgets.deadline_of_submission')
                ->get([
                    'allocated__budgets.*',
                    'fund_sources.fund_source as fund_source',
                    'ppmp_deadline.start_date',
                    'ppmp_deadline.end_date',
                ]);
                return view('pages.department.ppmp-submission', compact('allocated_budgets'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * * Get allocated budget
     * TODO 1: Check if ppmp request submission is empty
     * * --------------------------
     * ? KEY 2: if PPMP request submission is not empty. Join allocated budgets to ppmp request submission
     */
    public function get_pending_request(Request $request) {
        try {
            // TODO 1
            $ppmp_request_submission = \DB::table('ppmp_request_submission')
                ->join('allocated__budgets', 'allocated__budgets.id', 'ppmp_request_submission.allocated_budget')
                ->join('fund_sources', 'fund_sources.id', 'allocated__budgets.fund_source_id')
                ->join('ppmp_deadline', 'ppmp_deadline.year', 'allocated__budgets.year')
                ->where('allocated__budgets.procurement_type', (new AESCipher)->decrypt($request->procurement_type))
                ->where('ppmp_deadline.procurement_type', (new AESCipher)->decrypt($request->procurement_type))
                // * ---- check if deleted at is null
                    ->whereNull('allocated__budgets.deleted_at')
                    ->whereNull('fund_sources.deleted_at')
                    ->whereNull('ppmp_deadline.deleted_at')
                    ->whereNull('ppmp_request_submission.deleted_at')
                // * ---- check campus
                    ->where('allocated__budgets.campus', session('campus'))
                    ->where('ppmp_deadline.campus', session('campus'))
                // * ---- check department
                    ->where('allocated__budgets.department_id', session('department_id'))
                ->get([
                    'allocated__budgets.*',
                    'fund_sources.fund_source as fund_source',
                    'ppmp_deadline.start_date',
                    'ppmp_deadline.end_date',
                    'ppmp_request_submission.*'
                ]);
                return view('pages.department.ppmp-submission', compact('ppmp_request_submission'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * * Get project
     * TODO 1: Get project based allocated budgets
     * TODO 2: Get project that haven't been sent/attached as request
     * ? --------------------------------
     * ? KEY 1: 
     */
    public function get_projects(Request $request) {
        try {
            $this->validate($request,[
                'procurement_type' => 'required',
                'allocated_budget'  => 'required'
            ]);

            $project_titles = \DB::table('project_titles') // TODO 1
                ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                ->join('allocated__budgets', 'allocated__budgets.id', 'project_titles.allocated_budget') 
                ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                ->join('ppmp_deadline', 'ppmp_deadline.year', 'allocated__budgets.year') 
                ->where('project_titles.department_id', session('department_id'))
                ->where('allocated__budgets.department_id', session('department_id'))
                ->where('project_titles.employee_id', session('employee_id'))
                ->where('project_titles.campus', session('campus'))
                ->whereNull('project_titles.deleted_at')
                ->where('project_titles.status', 0) 
                ->whereNull('allocated__budgets.deadline_of_submission') 
                ->where('ppmp_deadline.end_date', '<', Carbon::now()->format('Y-m-d')) 
                ->where('project_titles.project_category', (new AESCipher)->decrypt($request->procurement_type))
                ->where('allocated__budgets.procurement_type', (new AESCipher)->decrypt($request->procurement_type)) 
                ->where('ppmp_deadline.procurement_type', (new AESCipher)->decrypt($request->procurement_type)) 
                ->where('project_titles.allocated_budget', $request->allocated_budget)
                ->get([
                    'project_titles.*',
                    'fund_sources.fund_source as fund_source',
                    'users.name as immediate_supervisor',
                    'allocated__budgets.allocated_budget',
                    'allocated__budgets.remaining_balance',
                    'ppmp_deadline.end_date as deadline_of_submission'
                ]);

            $total_estimated_price = array(); // * hold the total estimated prices for each ppmps basd on project
                if(count($project_titles) > 0) {
                    // * get the pppmps for each project title
                    foreach ($project_titles as $_project_title) {
                        $ppmps = \DB::table('ppmps')
                            ->where('campus', session('campus'))
                            ->where('department_id', session('department_id'))
                            ->where('employee_id', session('employee_id'))
                            ->where('project_code', $_project_title->id)
                            ->whereNull('ppmps.deleted_at')
                            ->get();
                            $estimated_price = 0.0; // * hold the total estimated prices for each added item from each ppmp
                        if(count($ppmps) > 0) {
                            // * get the total estimated prices of ppmps based on the project
                            foreach ($ppmps as $_ppmp) {
                                $estimated_price += $_ppmp->estimated_price;
                            }
                        } else {
                            $estimated_price = 0.0;
                        }
                        array_push($total_estimated_price, $estimated_price);
                    }
                }

           # display the view or blade file
            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
                ["link" => "/", "name" => "Home"],
                ["name" => "PPMP Submission"]
            ];
            return view('pages.department.ppmp-submission', 
                compact(
                    'project_titles', 'total_estimated_price',
                ));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * * Live search unit of measurement
     * TODO 1: Get all the unit of measurement similar to what is inputted by the end user
     * TODO 2: 
     * ? -----------------------------
     * ? KEY 1: partially compare input to unit of measurement stored in database
     */
    public function live_search_measurement(Request $request) {
        try {
            $response = \DB::table('unit_of_measurements')
                ->where('unit_of_measurement', 'like', '%'. $request->measurement .'%')
                ->whereNull('deleted_at')
                ->get();
            if(count($response) > 0) {
                return $response;
            } 
            # return error or zero item(s) found
            return $request->all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /**
     * * Get all unit of measurements
     * TODO 1: Get all stored unit of measurements stored in the database
     * 
     */
    public function get_measurements() {
        try {
            $response = \DB::table('unit_of_measurements')
            ->whereNull('deleted_at')
            ->get();
            if($response) {
                return ([
                    'status'    => 200,
                    'data'  => $response
                ]);
            }
            return ([
                'status'    => 400,
                'message'  => 'No data found'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
