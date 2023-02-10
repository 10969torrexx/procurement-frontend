<?php
namespace App\Http\Controllers\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Department\CategoriesController;
use App\Http\Controllers\Department\ProjectsController;
use App\Http\Controllers\Department\PpmpController;
use App\Http\Controllers\Department\AllocatedBudgetsController;
use App\Http\Controllers\Department\FundSourcesController;
use App\Http\Controllers\Department\ItemsController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\GlobalDeclare;

use App\Http\Controllers\ProjectTimelineController;
use App\Models\Departments;

use App\Models\Project_Titles;

use Carbon\Carbon;

class DepartmentPagesController extends Controller
{
        protected $aes;
        public function __construct(){
            $this->aes = new AESCipher();
        }
    /* Torrexx Additionals */
        # error view | pages
            public function showPageMaintenance () {
                return view('pages.page-maintenance');
            }
        # end
        # this function will show the dashboard for the department users
            public function showAnnouncementPage(){
               try {
                    /** This will join the allocated__budgets table and fundsources table
                     * - this will be displayed on the users dashboard page
                     * - table joined [ alocated_budgets, fundsources, mandatory_expenditures, mandatory_expenditures_list ]
                    */
                    $departmentID = \session('department_id');
                    $array_fund_id = [];
                    $mandatory_expeditures = [];
                    $allocated_budgets = \DB::table('allocated__budgets')
                        ->join('fund_sources', 'allocated__budgets.fund_source_id', 'fund_sources.id')
                        ->where('allocated__budgets.department_id', $departmentID)
                        ->where('allocated__budgets.campus', session('campus'))
                        ->whereNull('allocated__budgets.deleted_at')
                        ->groupBy('fund_sources.fund_source', 'allocated__budgets.year')
                        ->orderBy('allocated__budgets.year')
                        ->get([
                            'fund_sources.id', 'allocated__budgets.*', 'fund_sources.fund_source', \DB::raw('sum(allocated__budgets.allocated_budget) as SumBudget')
                        ]); 
                    
                    $mandatory_expeditures = \DB::table("mandatory_expenditures as me")
                        ->select("me.year", "me.fund_source_id", \DB::raw('sum(me.price) as SumMandatory'))
                        // ->where('me.campus', session('campus'))
                        // ->where('me.department_id', session('department_id'))
                        ->where('me.year', Carbon::now()->addYears(1)->format('Y'))
                        ->whereNull('me.deleted_at')
                        ->groupBy("me.year")
                        ->groupBy("me.fund_source_id")
                        ->get();
                    // dd($mandatory_expeditures);
                /** This will return table and page configs */
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                    ["link" => "/", "name" => "Home"],["name" => "Announcements"]
                    ];
                    return view('pages.department.announcements',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
                        [
                            'mandatory_expeditures'   =>  $mandatory_expeditures,
                            'allocated_budgets'   =>  $allocated_budgets,
                        ]);
               } catch (\Throwable $th) {
                    //throw $th;
                    return view('pages.error-500');
               }
            }
        # end
        # this will show the create project year 
            public function showProjectCategory() {
                # this will return the page
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],["name" => "PROJECT CATEGORY"]
                    ];
                    return view('pages.department.select-project-category',
                    ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]
                    # this will attache the data to view
                   );
                # end
            }
        # end
        # this function will show Projec Titles that are status = 0
            public function showCreatePPMP(Request $request){
                try {
                    # this will get data from database
                        # from project_titles table | draft project titles
                            $project_titles = \DB::table('project_titles')
                                ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                                ->join('allocated__budgets', 'allocated__budgets.id', 'project_titles.allocated_budget')
                                ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                                ->where('project_titles.campus', session('campus'))
                                ->where('project_titles.department_id', session('department_id'))
                                ->where('project_titles.employee_id', session('employee_id'))
                                ->where('project_titles.status', 0) // status draft
                                ->where('project_titles.project_category', (new AESCipher)->decrypt($request->project_category))
                                ->whereNull('project_titles.deleted_at')
                                ->get([
                                    'project_titles.*',
                                    'fund_sources.fund_source',
                                    'users.name as immediate_supervisor',
                                    'allocated__budgets.deadline_of_submission'
                                ]);
                            dd($project_titles);
                        # from departments table
                            $departments = \DB::table('departments')->where('id', session('department_id'))->get();
                        # from categories table
                            $categories = \DB::table('categories')->whereNull('deleted_at')->get();
                        # from fund sources table
                            $fund_sources = \DB::table('allocated__budgets')
                                ->join('fund_sources', 'fund_sources.id', 'allocated__budgets.fund_source_id')
                                ->where('allocated__budgets.campus', session('campus'))
                                ->where('allocated__budgets.department_id', session('department_id'))
                                # this will determine the project category for this fund source | Indicative, PPMP, Supplemental
                                    ->where('allocated__budgets.procurement_type', 
                                        (new GlobalDeclare)->project_category((new AESCipher)->decrypt($request->project_category)))
                                // ! determine the deadline of submission
                                    ->where('allocated__budgets.deadline_of_submission', '>=', Carbon::now()->format('Y-m-d'))
                                ->whereNull('allocated__budgets.deleted_at')
                                ->get(['allocated__budgets.*', 'allocated__budgets.id as allocated_id','fund_sources.fund_source']);
                    # end 
                    # this will return the page
                        $pageConfigs = ['pageHeader' => true];
                        $breadcrumbs = [
                            ["link" => "/", "name" => "Home"],
                            ["link" => "/department/project-category", "name" => "PROJECT CATEGORY"],
                            ["name" =>  "CREATE PPMP |" . " " . strtoupper((new GlobalDeclare)->project_category((new AESCipher)->decrypt($request->project_category))) ],
                        ];
                        return view('pages.department.create-ppmp',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                        # this will attache the data to view
                        [
                            'project_titles' => $project_titles,
                            'fund_sources'   => \json_decode($fund_sources),
                            'departments'   =>  $departments,
                            'categories'    => $categories,
                            'project_category' => $request->project_category,
                        ]);
                    # end
                } catch (\Throwable $th) {
                    throw $th;
                    return view('pages.error-500');
                }
            }
        # end
        # this will show add item on project based on project title
        public function showAddItem(Request $request) {
            $id = $this->aes->decrypt($request->id);
            try {
                # this will grab the specific title based department id, employee id, campus, project year
                    $ProjectTitleResponse = Project_Titles::
                        join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                        ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                        ->where('project_titles.id', $id)
                        ->where('project_titles.campus', session('campus'))
                        ->where('project_titles.department_id', session('department_id'))
                        ->where('project_titles.employee_id', session('employee_id'))
                        ->whereNull('project_titles.deleted_at')
                        ->get([
                            'project_titles.*',
                            'fund_sources.fund_source',
                            'users.name as immediate_supervisor' 
                        ]);
                # end
                # this will get the item based on the project code, department id, employee id 
                    $ppmp_response = \DB::table('ppmps')
                        ->join('mode_of_procurement', 'mode_of_procurement.id', 'ppmps.mode_of_procurement')
                        ->where('ppmps.project_code', $id)
                        ->where('ppmps.campus', session('campus'))
                        ->where('ppmps.department_id', session('department_id'))
                        ->where('ppmps.employee_id', session('employee_id'))
                        ->whereNull('ppmps.deleted_at')
                        ->get([
                            'ppmps.*',
                            'mode_of_procurement.*',
                            'ppmps.id as ppmps_id'
                        ]);
                # end
                # this will get data from database
                    # for allocated budgets table
                        $allocated_budgets = \DB::table('allocated__budgets')
                            ->where('id', (new AESCipher)->decrypt($request->allocated_budget))
                            ->where('campus', session('campus'))
                            ->where('department_id', session('department_id'))
                            ->whereNull('deleted_at')
                            ->get();

                        # return if allocated budget is null
                            if((count($allocated_budgets) <= 0) || $allocated_budgets == null) {
                                return back()->with([
                                    'error' => 'You\'ve zero (0) allocated budget. Contact your campus budget officer'
                                ]);
                            }
                    # for mode of procurement
                        $mode_of_procurements = \DB::table('mode_of_procurement')
                            // ->where('campus', session('campus'))
                            ->whereNull('deleted_at')
                            ->get();
                        # return if null
                        if((count($mode_of_procurements) <= 0) || $mode_of_procurements == null) {
                                return back()->with([
                                    'error' => 'You\'ve no mode of procurment. Contact your campus BAC Secretariat'
                                ]);
                            }
                    # for unit of measure
                        $unit_of_measurement = \DB::table('unit_of_measurements')
                            // ->where('campus', session('campus'))
                            ->whereNull('deleted_at')
                            ->get();
                        # return if null
                            if((count($unit_of_measurement) <= 0) || $unit_of_measurement == null) {
                                return back()->with([
                                    'error' => 'You\'ve no unit of measurement. Contact your campus BAC Secretariat'
                                ]);
                            }
                    # for items
                        $items = \DB::table('items')
                            ->join('mode_of_procurement', 'mode_of_procurement.id', 'items.mode_of_procurement_id')
                            ->whereNull('mode_of_procurement.deleted_at')
                            ->whereNull('items.deleted_at')
                            ->get();

                        # return if null
                            if((count($items) <= 0) || $items == null) {
                                return back()->with([
                                    'error' => 'You\'ve no items. Contact your campus BAC Secretariat'
                                ]);
                            }
                # end
                # this will return the department.my-PPMP
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],
                        ["link" => "/department/project-category", "name" => "PROJECT CATEGORY"],
                        ["name" => "ADD ITEM"]
                    ];
                    return view('pages.department.add-item',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                        # this will attache the data to view
                        [
                            'id' => $id,
                            'ProjectTitleResponse'    => $ProjectTitleResponse,
                            'items' => \json_decode($items),
                            'mode_of_procurements'  =>  $mode_of_procurements,
                            'unit_of_measurements'  =>  $unit_of_measurement,
                            'ppmp_response' => $ppmp_response,
                            'allocated_budgets' => $allocated_budgets
                        ]
                    );
                # end
           } catch (\Throwable $th) {
            //    throw $th;
             return view('pages.error-500');
           }
        }

        # this will show the My PPMP Page based on the provided department id by the logged in user
        public function showMyPPMP() {
            try {
                # this will get all the data form the ppmp table based on the given department_id
                    $project_titles = \DB::table('project_titles')
                        ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                        // ->join('departments', 'departments.immediate_supervisor', 'project_titles.department_id')
                        ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                        ->where('project_titles.campus', session('campus'))
                        ->where('project_titles.department_id', session('department_id'))
                        ->where('project_titles.employee_id', session('employee_id'))
                        ->whereNull('project_titles.deleted_at')
                        ->get([
                            'project_titles.*',
                            'fund_sources.fund_source',
                            'users.name as immediate_supervisor' 
                        ]);
                # from project titles table | disapproved project titles
                    $pt_show_disapproved = \DB::table('project_titles')
                        ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                        ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                        ->where('project_titles.campus', session('campus'))
                        ->where('project_titles.department_id', session('department_id'))
                        ->where('project_titles.employee_id', session('employee_id'))
                        ->where(function($query) {
                            $query->where('project_titles.status', 3)
                                ->orWhere('project_titles.status', 5);
                        })
                        ->whereNull('project_titles.deleted_at')
                        ->get([
                            'project_titles.*',
                            'fund_sources.fund_source',
                            'users.name as immediate_supervisor' 
                        ]);
                # from project titles | approved project titles
                    $pt_show_approved = \DB::table('project_titles')
                        ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                        ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                        ->where('project_titles.campus', session('campus'))
                        ->where('project_titles.department_id', session('department_id'))
                        ->where('project_titles.employee_id', session('employee_id'))
                        ->where('project_titles.status', 4)
                        ->whereNull('project_titles.deleted_at')
                        ->get([
                            'project_titles.*',
                            'fund_sources.fund_source',
                            'users.name as immediate_supervisor' 
                        ]);
                # from categories table
                    $categories = \DB::table('categories')->whereNull('deleted_at')->get();  
                # from fund sources table
                    $fund_sources = \DB::table('allocated__budgets')
                        ->join('fund_sources', 'fund_sources.id', 'allocated__budgets.fund_source_id')
                        ->where('allocated__budgets.campus', session('campus'))
                        ->where('allocated__budgets.department_id', session('department_id'))
                        ->where('allocated__budgets.procurement_type', 'PPMP') // make this dynamic
                        ->whereNull('allocated__budgets.deleted_at')
                        ->get(['allocated__budgets.*', 'allocated__budgets.id as allocated_id','fund_sources.fund_source']);
                # this is for affixing header links above the card directoryyy
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                    ["link" => "/", "name" => "Home"],
                    ["name" => "My PPMP"]
                    ];
                    # this will return the department.my-PPMP
                    return view('pages.department.my-ppmp-status',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                        # this will attache the data to view
                        [
                            'project_titles' => $project_titles,
                            'pt_show_disapproved'   => $pt_show_disapproved,
                            'pt_show_approved'   => $pt_show_approved,
                            'categories'    => $categories,
                            'fund_sources'   => \json_decode($fund_sources),
                        ]
                    );
            } catch (\Throwable $th) {
                throw $th;
                return view('pages.error-500');
            }
        }

        # this will show the My PPMP Page based on the provided department id by the logged in user
        public function show_by_year_created(Request $request) {
            // dd($request->all());
            try {
                # this will get all the data form the ppmp table based on the given department_id
                    $project_titles = \DB::table('project_titles')
                        ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                        // ->join('departments', 'departments.immediate_supervisor', 'project_titles.department_id')
                        ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                        ->where('project_titles.campus', session('campus'))
                        ->where('project_titles.department_id', session('department_id'))
                        ->where('project_titles.employee_id', session('employee_id'))
                        ->where('project_titles.year_created', (new AESCipher)->decrypt($request->year_created))
                        ->where('project_titles.project_category', $request->project_category)
                        ->whereNull('project_titles.deleted_at')
                        ->get([
                            'project_titles.*',
                            'fund_sources.fund_source',
                            'users.name as immediate_supervisor' 
                        ]);
                # from project titles table | disapproved project titles
                    $pt_show_disapproved = \DB::table('project_titles')
                        ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                        ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                        ->where('project_titles.campus', session('campus'))
                        ->where('project_titles.department_id', session('department_id'))
                        ->where('project_titles.employee_id', session('employee_id'))
                        ->where('project_titles.year_created', (new AESCipher)->decrypt($request->year_created))
                        ->where('project_titles.project_category', $request->project_category)
                        ->where(function($query) {
                            $query->where('project_titles.status', 3)
                                ->orWhere('project_titles.status', 5);
                        })
                        ->whereNull('project_titles.deleted_at')
                        ->get([
                            'project_titles.*',
                            'fund_sources.fund_source',
                            'users.name as immediate_supervisor' 
                        ]);
                # from project titles | approved project titles
                    $pt_show_approved = \DB::table('project_titles')
                        ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                        ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                        ->where('project_titles.campus', session('campus'))
                        ->where('project_titles.department_id', session('department_id'))
                        ->where('project_titles.employee_id', session('employee_id'))
                        ->where('project_titles.year_created', (new AESCipher)->decrypt($request->year_created))
                        ->where('project_titles.status', 4)
                        ->where('project_titles.project_category', $request->project_category)
                        ->whereNull('project_titles.deleted_at')
                        ->get([
                            'project_titles.*',
                            'fund_sources.fund_source',
                            'users.name as immediate_supervisor' 
                        ]);
                # from categories table
                    $categories = \DB::table('categories')->whereNull('deleted_at')->get();  
                # from fund sources table
                    $fund_sources = \DB::table('allocated__budgets')
                        ->join('fund_sources', 'fund_sources.id', 'allocated__budgets.fund_source_id')
                        ->where('allocated__budgets.campus', session('campus'))
                        ->where('allocated__budgets.department_id', session('department_id'))
                        ->where('allocated__budgets.procurement_type', 'PPMP') // make this dynamic
                        ->whereNull('allocated__budgets.deleted_at')
                        ->get(['allocated__budgets.*', 'allocated__budgets.id as allocated_id','fund_sources.fund_source']);
                # this is for affixing header links above the card directoryyy
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                    ["link" => "/", "name" => "Home"],
                    ["name" => "My PPMP"]
                    ];
                    # this will return the department.my-PPMP
                    return view('pages.department.my-ppmp-status',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                        # this will attache the data to view
                        [
                            'project_titles' => $project_titles,
                            'pt_show_disapproved' => $pt_show_disapproved,
                            'pt_show_approved' => $pt_show_approved,
                            'categories'    => $categories,
                            'fund_sources'   => \json_decode($fund_sources),
                        ]
                    );
            } catch (\Throwable $th) {
                    // throw $th;
                    return view('pages.error-500');
            }
        }

        # this will show the status of the project
        public function showProjectStatus(Request $request) {
            try {
                $project_titles = \DB::table('project_titles')
                    ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                    // ->join('departments', 'departments.immediate_supervisor', 'project_titles.department_id')
                    ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                    ->where('project_titles.id', intval((new AESCipher)->decrypt($request->id)))
                    ->where('project_titles.campus', session('campus'))
                    ->where('project_titles.department_id', session('department_id'))
                    ->where('project_titles.employee_id', session('employee_id'))
                    ->where('project_titles.status', '!=', '0')
                    ->whereNull('project_titles.deleted_at')
                    ->get([
                        'project_titles.*',
                        'fund_sources.fund_source',
                        'users.name as immediate_supervisor' 
                    ]);
                $ppmp_response = \DB::table('ppmps')
                    ->join('mode_of_procurement', 'mode_of_procurement.id', 'ppmps.mode_of_procurement')
                    ->where('ppmps.department_id', session('department_id'))
                    ->where('ppmps.employee_id', intval(session('employee_id')))
                    ->where('ppmps.project_code', (new AESCipher)->decrypt($request->id))
                    ->whereNull('ppmps.deleted_at')
                    ->get();

                $project_timeline = \DB::table('project_timeline')
                    ->where('project_id', (new AESCipher)->decrypt($request->id))
                    ->get();
                # this will check if all required data is not null 
                    if((count($ppmp_response) <= 0) || $ppmp_response == null) {
                        # if there are null data this will retur na page maintenance page
                        return back()->with([
                            'failed'    => 'This Project doesn\' contain any items. Please add items using the Create PPMP Tab.'
                        ]);
                    } 
                # this is for affixing header links above the card directoryyy
                $pageConfigs = ['pageHeader' => true];
                $breadcrumbs = [
                ["link" => "/", "name" => "Home"],
                ["name" => "PROJECT DETAILS"]
                ];
                # this will return the department.my-PPMP
                return view('pages.department.project-status',
                    ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                    # this will attache the data to view
                    [
                        'project_titles' => $project_titles,
                        'ppmp_response' => $ppmp_response,
                        'project_timeline'  => $project_timeline
                    ]
                );
            } catch (\Throwable $th) {
                // throw $th;
                return view('pages.error-500');
            }
        }

        # this wil display view disapproved items pages
        public function show_disapproved_items(Request $request) {
            $id = $this->aes->decrypt($request->id);
            try {
                # this will grab the specific title based department id, employee id, campus, project year
                        $ProjectTitleResponse = Project_Titles::
                        join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                        ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                        ->where('project_titles.id', $id)
                        ->where('project_titles.campus', session('campus'))
                        ->where('project_titles.department_id', session('department_id'))
                        ->where('project_titles.employee_id', session('employee_id'))
                        ->whereNull('project_titles.deleted_at')
                        ->get([
                            'project_titles.*',
                            'fund_sources.fund_source',
                            'users.name as immediate_supervisor' 
                        ]);
                # end
                # this will get the item based on the project code, department id, employee id 
                    $ppmp_response = \DB::table('ppmps')
                        ->join('mode_of_procurement', 'mode_of_procurement.id', 'ppmps.mode_of_procurement')
                        ->where('ppmps.project_code', $id)
                        ->where('ppmps.campus', session('campus'))
                        ->where('ppmps.department_id', session('department_id'))
                        ->where('ppmps.employee_id', session('employee_id'))
                        ->whereNull('ppmps.deleted_at')
                        ->get([
                            'ppmps.*',
                            'mode_of_procurement.*',
                            'ppmps.id as ppmps_id'
                        ]);
                # end
                # this will get data from database
                    # for allocated budgets table
                        $allocated_budgets = \DB::table('allocated__budgets')
                            ->where('id', (new AESCipher)->decrypt($request->allocated_budget))
                            ->where('campus', session('campus'))
                            ->where('department_id', session('department_id'))
                            ->whereNull('deleted_at')
                            ->get();
                        # return if allocated budget is null
                            if((count($allocated_budgets) <= 0) || $allocated_budgets == null) {
                                return back()->with([
                                    'error' => 'You\'ve zero (0) allocated budget. Contact your campus budget officer'
                                ]);
                            }
                    # for mode of procurement
                        $mode_of_procurements = \DB::table('mode_of_procurement')
                            // ->where('campus', session('campus'))
                            ->whereNull('deleted_at')
                            ->get();
                        # return if null
                        if((count($mode_of_procurements) <= 0) || $mode_of_procurements == null) {
                                return back()->with([
                                    'error' => 'You\'ve no mode of procurment. Contact your campus BAC Secretariat'
                                ]);
                            }
                    # for unit of measure
                        $unit_of_measurement = \DB::table('unit_of_measurements')
                            // ->where('campus', session('campus'))
                            ->whereNull('deleted_at')
                            ->get();
                        # return if null
                            if((count($unit_of_measurement) <= 0) || $unit_of_measurement == null) {
                                return back()->with([
                                    'error' => 'You\'ve no unit of measurement. Contact your campus BAC Secretariat'
                                ]);
                            }
                    # for items
                        $items = \DB::table('items')
                            ->join('mode_of_procurement', 'mode_of_procurement.id', 'items.mode_of_procurement_id')
                            ->whereNull('mode_of_procurement.deleted_at')
                            ->whereNull('items.deleted_at')
                            ->get();

                        # return if null
                            if((count($items) <= 0) || $items == null) {
                                return back()->with([
                                    'error' => 'You\'ve no items. Contact your campus BAC Secretariat'
                                ]);
                            }
                # end
                # this will return the department.my-PPMP
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],
                        ["link" => "/department/project-category", "name" => "PROJECT CATEGORY"],
                        ["link" => "/department/createPPMP", "name" => "PROJECT TITLES"],
                        ["name" => "ADD ITEM"]
                    ];
                    return view('pages.department.view-disapproved-items',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                        # this will attache the data to view
                        [
                            'id' => $id,
                            'ProjectTitleResponse'    => $ProjectTitleResponse,
                            'items' => \json_decode($items),
                            'mode_of_procurements'  =>  $mode_of_procurements,
                            'unit_of_measurements'  =>  $unit_of_measurement,
                            'ppmp_response' => $ppmp_response,
                            'allocated_budgets' => $allocated_budgets
                        ]
                    );
                # end
           } catch (\Throwable $th) {
                //    throw $th;
                return view('pages.error-500');
           }
        }

        # request ppmp submission
        public function show_ppmp_submission() {
            try {
                return view('pages.page-coming-soon');
            //    return view('pages.department.ppmp-submission');
            } catch (\Throwable $th) {
                throw $th;
                return view('pages.error-500');
            }
        }

        # upload ppmp | signed ppmp
            public function show_upload_ppmp() {
                try {
                    # get uplpaded ppmp
                    $response = \DB::table('signed_ppmp')
                        ->where('employee_id', session('employee_id'))
                        ->where('department_id', session('department_id'))
                        ->where('campus', session('campus'))
                        ->whereNull('deleted_at')
                        ->get();
                    # return page
                    return view('pages.department.upload-ppmp', compact('response'));
                } catch (\Throwable $th) {
                    //throw $th;
                    return view('pages.error-500');
                }
            }
        # upload ppmp | signed ppmp
            public function get_upload_ppmp(Request $request) {
                try {
                    $this->validate($request, [
                        'project_category'  => ['required'],
                        'year_created'  =>  ['required'],
                        'file_name' => ['required'],
                    ]);
                    # get uplpaded ppmp
                    $response = \DB::table('signed_ppmp')
                        ->where('employee_id', session('employee_id'))
                        ->where('department_id', session('department_id'))
                        ->where('campus', session('campus'))
                        ->where('project_category', (new AESCipher)->decrypt($request->project_category))
                        ->where('year_created', (new AESCipher)->decrypt($request->year_created))
                        ->where('file_name', 'like', '%'. $request->file_name .'%')
                        ->whereNull('deleted_at')
                        ->get();
                    # return page
                    return view('pages.department.upload-ppmp', compact('response'));
                } catch (\Throwable $th) {
                    throw $th;
                    return view('pages.error-500');
                }
            }

}
