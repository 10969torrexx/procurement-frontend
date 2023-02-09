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
     * Store a newly created resource in storage for the Project Titles Table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

    /* Store a newly created resource in storage for the Project Titles Table.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function destoryProjectTitle(Request $request)
   {
        try {
            # delete project title
            $project_titles = \DB::table('project_titles')
            ->where('id', (new AESCipher)->decrypt($request->id))
            ->where('campus', session('campus'))
            ->where('employee_id', session('employee_id'))
            ->where('department_id', session('department_id'))
            ->update([
                'deleted_at' => Carbon::now()
            ]);
            # delete ppmps table
            $ppmps = \DB::table('ppmps')
            ->where('project_code', (new AESCipher)->decrypt($request->id))
            ->where('campus', session('campus'))
            ->where('employee_id', session('employee_id'))
            ->where('department_id', session('department_id'))
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
    }
     /**
     * Store a newly created resource in storage for the Project Titles Table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createPPMPs(Request $request)
    {
        try {
            # form fields validation
            $this->validate($request, [
                # field validation | disabled
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
            $__total_estimated_price = $request->total_estimated_price + doubleval($request->estimated_price);
            # comparing total estimated price to remaining balance
            if($__total_estimated_price > doubleval($request->remaining_balance)) {
                return back()->with([
                    'failed' => 'Your total estimated price has exceeded your remaining balance. Please make necessary adjustments!'
                ]);
            }

            # comparing current date to deadline of submission
            $_deadline_of_submission = Carbon::parse($request->deadline_of_submission)->format('Y-m-d');
            $current_date = Carbon::now()->format('Y-m-d');
            if($current_date > $_deadline_of_submission) {
                return back()->with([
                    'failed'    => 'You\'ve exceeded the alloted deadline of submission.'
                ]);
            } 
            
            # this will send data to project titles table using api
                $create_items = \DB::table('ppmps')
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
            
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    (new AESCipher)->decrypt($request->project_code),
                    'Create PPMPs or add items on project',
                    'create',
                    $request->ip(),
                );
            # end
            
            return back()->with([
                'success'   => 'Item added as Draft!'
            ]);
        } catch (\Throwable $th) {
            throw $th;
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
     * Submit PPMP based on the users
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function submitPPMP(Request $request)
    {
        // dd($request->all());
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
                    return redirect(route('department-showCreatetPPMP'))->with([
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

   /* Submit All projects .
    * - this will enable submission of multiple projects
    */
    public function submit_all_projects(Request $request) {
        try {
            $total_estimated_price = 0.0;
            $final_remaining_balance = 0.0;
            $__total_estimated_price = array();
            foreach ($request->project_titles as $item) {
                # check if project has items
                    $ppmps = \DB::table('ppmps')
                        ->where('project_code', $item)
                        ->where('campus', session('campus'))
                        ->where('department_id', session('department_id'))
                        ->where('status', 0)
                        ->get();
                # return if project doesn't have items
                    if(count($ppmps) <= 0) {
                        return ([
                            'status'    => 400,
                            'message'   => 'Some of the projects doen\'t contain any items!'
                        ]);
                    }
                # testing if submittted project is beyond deadline of submission
                    $allocated_budgets = \DB::table('project_titles')
                            ->join('allocated__budgets', 'allocated__budgets.id', 'project_titles.allocated_budget')
                            ->where('project_titles.id', $item)
                            ->where('project_titles.campus', session('campus'))
                            ->where('project_titles.department_id', session('department_id'))
                            ->where('project_titles.employee_id', session('employee_id'))
                            ->get([
                                'allocated__budgets.id',
                                'allocated__budgets.deadline_of_submission',
                                'allocated__budgets.remaining_balance'
                            ]);
                    if(Carbon::now()->format('Y-m-d') > Carbon::parse($allocated_budgets[0]->deadline_of_submission)->format('Y-m-d')) {
                        return ([
                            'status'    => 400,
                            'message'   => 'You\'ve exceeded the alloted deadline of submission!'
                        ]);
                    }
                # comparing total of estimated price per project to remaining balance
                    foreach ($ppmps as $estimated_price) {
                        array_push($__total_estimated_price, $estimated_price->estimated_price);
                        $total_estimated_price +=  $estimated_price->estimated_price;
                    }
                    if($total_estimated_price > $allocated_budgets[0]->remaining_balance) {
                        return ([
                            'status'    => 400,
                            'message'   => 'Some of the projects have exceeded the remaining balance!'
                        ]);
                    }
                # get the final remaining balance
                    $final_remaining_balance = $allocated_budgets[0]->remaining_balance - $total_estimated_price;
                # updating statuses of project titles | PPMPS | remaining balance
                    \DB::table('project_titles')
                        ->where('id', $item)
                        ->where('department_id', session('department_id'))
                        ->where('employee_id', session('employee_id'))
                        ->where('campus', session('campus'))
                        ->update([
                            'status'    => 1,
                            'updated_at'    => Carbon::now()
                        ]);
                    \DB::table('ppmps')
                        ->where('project_code', $item)
                        ->where('department_id', session('department_id'))
                        ->where('employee_id', session('employee_id'))
                        ->where('campus', session('campus'))
                        ->update([
                            'status'    => 1,
                            'updated_at'    => Carbon::now()
                        ]);
                    \DB::table('allocated__budgets')
                        ->where('id', $allocated_budgets[0]->id)
                        ->update([
                            'remaining_balance' => $final_remaining_balance,
                            'procurement_type' => 'Supplemental',
                            'updated_at'    => Carbon::now()
                        ]);
                    \DB::table('project_timeline')
                        ->insert([
                            'employee_id'   => session('employee_id'),
                            'department_id' => session('department_id'),
                            'role'  =>  \session('role'),
                            'campus'  => \session('campus'),
                            'project_id'    => $item,
                            'status'    => intval(1),
                            'remarks'   =>   'Your PPMP is currently Pending of Immediates Supervisor\'s Acceptance.',
                            'created_at'    =>  Carbon::now(),
                            'updated_at'    =>  Carbon::now(),
                        ]);
                # this will created history_log
                    (new HistoryLogController)->store(
                        session('department_id'),
                        session('employee_id'),
                        session('campus'),
                        (new AESCipher)->decrypt($request->current_project_code),
                        'Submit All Project for Immediate\s supervisor acceptance',
                        'Submit',
                        $request->ip(),
                    );
                # end
            }
            return ([
                'status'    => 200,
                'message'   => 'Project(s) submitted for Supervisor\'s Acceptance!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return view('pages.error-500');
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
            throw $th;
            return view('pages.error-500');
        }
    }

    /* Download Uplooded PPMP
     * - this will enable downlaod uploade PPMP
     * - based on: Employee id, campus, department_id
     * - get file from storage upload id search
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
                'signed_ppmp'
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
            return \Storage::download(env('APP_NAME').'\\department_upload\\signed_ppmp\\'.$response[0]->signed_ppmp);
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
}
