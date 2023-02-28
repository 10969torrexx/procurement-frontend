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
use App\Http\Controllers\HistoryLogController;

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
        /**
         * * Show Announcement Page
         * TODO 1: Get all allocated budget for the department
         * TODO 2: Get the associated fund sources for each allocated budget
         * TODO 3: Get total mandatory expenditures per budget allocation
         * TODO 4: Get total used budget by project for allocated budgets
         * ? -----------------------------------------
         * ? KEY 1: 
         * ? KEY 2: (TODO 4) 
         */
            public function showAnnouncementPage(){
               try {
                    /** This will join the allocated__budgets table and fundsources table
                     * - this will be displayed on the users dashboard page
                     * - table joined [ alocated_budgets, fundsources, mandatory_expenditures, mandatory_expenditures_list ]
                    */
                    $departmentID = \session('department_id');
                    $array_fund_id = [];
                    $mandatory_expeditures = [];
                    $total_estimated_price = [];
                    $allocated_budgets = \DB::table('allocated__budgets')
                        ->join('fund_sources', 'allocated__budgets.fund_source_id', 'fund_sources.id')
                        ->where('allocated__budgets.department_id', $departmentID)
                        ->where('allocated__budgets.campus', session('campus'))
                        ->whereNull('allocated__budgets.deleted_at')
                        ->groupBy('fund_sources.fund_source', 'allocated__budgets.year')
                        ->orderBy('allocated__budgets.year')
                        ->get([
                            'fund_sources.id', 
                            'allocated__budgets.*', 
                            'fund_sources.fund_source', 
                            \DB::raw('sum(allocated__budgets.allocated_budget) as SumBudget')
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

                   foreach ($allocated_budgets as $item) {
                        $ppmps = \DB::table('ppmps')
                            ->join('project_titles', 'project_titles.id', 'ppmps.project_code')
                            ->whereNull('ppmps.deleted_at')
                            ->where('project_titles.allocated_budget', $item->id)
                            ->get([
                                'estimated_price'
                            ]);
                        $_estimated_price = 0.0;
                        if(count($ppmps) > 0) {
                            // * get the total estimated prices of ppmps based on the project
                            foreach ($ppmps as $_ppmp) {
                                $_estimated_price += $_ppmp->estimated_price;
                            }
                        } else {
                            $_estimated_price = 0.0;
                        }
                        array_push($total_estimated_price, $_estimated_price);
                   }
                  
                /** This will return table and page configs */
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                    ["link" => "/", "name" => "Home"],
                    ["name" => "Announcements"]
                    ];
                    return view('pages.department.announcements',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
                        [
                            'mandatory_expeditures'   =>  $mandatory_expeditures,
                            'allocated_budgets'   =>  $allocated_budgets,
                            'total_estimated_price' => $total_estimated_price
                        ]);
               } catch (\Throwable $th) {
                    throw $th;
                    return view('pages.error-500');
               }
            }
        
        /**
         * * Show project category page
         * TODO: show the project category choices for the users
         * ? Project Category: Indicative, PPMP, Supplemental
         * ? Refer to GlobalDeclare for indexing
         */
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

        /**
         * * Submit PPMP | Create title
         * TODO 1: Get all project titles that are draft
         * TODO 2: Get all fund sources by joining ppmp_deadline, allocated__budgets & fund_sources
         * TODO 3: Get all the total estimated prices per project titles
         * TODO 4: Get all the ppmp deadline year based on procurement type
         * TODO 5: Get all departments
         * TODO 6: Get all categories
         * ? --------------
         * ? KEY 1: make sure that fund souces end date (deadline of submission) hasn't exceeded the current date
         * ? KEY 2: Compare current date to the fund source END DATE
         * ? KEY 3: get all ppmp deadline year based on the procurement type
         * ? KEY 4: Check if ppmp deadline of submission is 
         * * if null, then check if ppmp deadline of submission end date is not null
         */
            public function showCreatePPMP(Request $request){
                try {
                    // TODO 1
                        $project_titles = \DB::table('project_titles')
                            ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                            ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                            ->join('allocated__budgets', 'allocated__budgets.id', 'project_titles.allocated_budget')
                            ->join('ppmp_deadline', 'ppmp_deadline.year', 'allocated__budgets.year')
                            ->where('project_titles.project_category', (new AESCipher)->decrypt($request->project_category))
                            ->where('ppmp_deadline.procurement_type', (new AESCipher)->decrypt($request->project_category))
                            // ->where('allocated__budgets.procurement_type', (new AESCipher)->decrypt($request->project_category))
                            ->where('project_titles.status', 0) // * check if project is draft
                            ->whereNull('project_titles.deleted_at')
                            ->whereNull('users.deleted_at')
                            ->get([
                                'project_titles.*',
                                'users.name as immediate_supervisor',
                                'fund_sources.fund_source',
                                'allocated__budgets.id as allocated_budget',
                                'allocated__budgets.deadline_of_submission',
                                'ppmp_deadline.end_date',
                                'ppmp_deadline.start_date',
                            ]);
                    // TODO 2
                        $fund_sources = \DB::table('allocated__budgets')
                            ->join('fund_sources', 'fund_sources.id', 'allocated__budgets.fund_source_id')
                            ->join('ppmp_deadline', 'ppmp_deadline.year', 'allocated__budgets.year')
                            ->where('allocated__budgets.campus', session('campus')) 
                            ->where('department_id', session('department_id'))
                            ->where('ppmp_deadline.procurement_type', (new AESCipher)->decrypt($request->project_category)) // ? KEY 3
                            ->where('allocated__budgets.procurement_type', (new AESCipher)->decrypt($request->project_category)) // ? KEY 3
                            ->whereNull('allocated__budgets.deleted_at') 
                            ->whereNull('fund_sources.deleted_at') 
                            ->whereNull('ppmp_deadline.deleted_at')
                            ->get([
                                'allocated__budgets.*',
                                'allocated__budgets.id as allocated_id',
                                'fund_sources.id as fund_source_id',
                                'fund_sources.fund_source',
                                'ppmp_deadline.start_date',
                                'ppmp_deadline.end_date',
                            ]);
                    // TODO 3
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
                    // TODO 4
                        $ppmp_deadline = \DB::table('ppmp_deadline')
                            ->whereNull('deleted_at')
                            ->where('procurement_type', (new AESCipher)->decrypt($request->project_category))
                            ->get();
                    // TODO 5
                        $departments = \DB::table('departments')->where('id', session('department_id'))->get();
                    // TODO 6
                        $categories = \DB::table('categories')->whereNull('deleted_at')->get();
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
                            'project_titles' => $project_titles, // TODO 1
                            'fund_sources'   => \json_decode($fund_sources), // TODO 2
                            'total_estimated_price' => $total_estimated_price, // TODO 3
                            'ppmp_deadline' => $ppmp_deadline, // TODO 4
                            'departments'   =>  $departments, // TODO 5
                            'categories'    => $categories, // TODO 6
                            'project_category' => $request->project_category, // * submit the current project category
                        ]);
                    # end
                } catch (\Throwable $th) {
                    throw $th;
                    return view('pages.error-500');
                }
            }
        # end
       /**
        * * Submit PPMP | Add item Page
        * TODO 1: Get the specified project tile
        * TODO 2: Get the ppmps or items added to that project title
        * TODO 3: Get allocated budgets
        * TODO 4: Get all mode of procurements
        * TODO 5: Get all unit of measurements
        * TODO 6: Get all items
        * ? --------------------------
        * ? KEY 1: join allocated budgets and ppmp deadline tables
        * ? KEY 2: Get deadline of submission thru pppmp deadline end date
        * ? KEY 3: Check if department has allocated budgets
        * ? KEY 4: Check if department has mode of procurements
        * ? KEY 5: Check if department has unit of measurements
        * ? KEY 6: Check if department has items 
        * ? KEY 7: Check if current date has exceeded the ppmp deadline end date
        * ? KEY 8: Check if the procurement type
        */
        public function showAddItem(Request $request) {
            $id = (new AESCipher)->decrypt($request->id);
            try {
                // TODO 1
                    $project_titles = \DB::table('project_titles')
                        ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                        ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                        ->where('project_titles.id', (new AESCipher)->decrypt($request->id))
                        ->where('project_titles.campus', session('campus'))
                        ->where('project_titles.department_id', session('department_id'))
                        ->where('project_titles.employee_id', session('employee_id'))
                        ->whereNull('users.deleted_at')
                        ->get([
                            'project_titles.*',
                            'users.name as immediate_supervisor',
                            'fund_sources.fund_source'
                        ]);
                // TODO 2
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
                // TODO 3
                    $allocated_budgets = \DB::table('allocated__budgets')
                        ->join('ppmp_deadline', 'ppmp_deadline.year', 'allocated__budgets.year')
                        ->where('allocated__budgets.id', (new AESCipher)->decrypt($request->allocated_budget))
                        ->where('allocated__budgets.campus', session('campus'))
                        ->where('allocated__budgets.department_id', session('department_id'))
                        ->where('allocated__budgets.procurement_type', intval((new AESCipher)->decrypt($request->procurement_type)))
                        ->where('ppmp_deadline.procurement_type', intval((new AESCipher)->decrypt($request->procurement_type)))
                        ->whereNull('allocated__budgets.deleted_at')
                        ->get([
                            'ppmp_deadline.start_date',
                            'ppmp_deadline.end_date', // ? KEY 2
                            'allocated__budgets.*'
                        ]);
                    // ? KEY 3
                    if((count($allocated_budgets) <= 0) || $allocated_budgets == null) {
                        return back()->with([
                            'failed' => 'You\'ve zero (0) allocated budget. Contact your campus budget officer'
                        ]);
                    }
                // TODO 4       
                    $mode_of_procurements = \DB::table('mode_of_procurement')
                        ->whereNull('deleted_at')
                        ->get();
                    // ? KEY 4
                    if((count($mode_of_procurements) <= 0) || $mode_of_procurements == null) {
                        return back()->with([
                            'failed' => 'You\'ve no mode of procurment. Contact your campus BAC Secretariat'
                        ]);
                    }
                // TODO 5
                    $unit_of_measurement = \DB::table('unit_of_measurements')
                        ->whereNull('deleted_at')
                        ->get();
                    // ? KEY 5
                    if((count($unit_of_measurement) <= 0) || $unit_of_measurement == null) {
                        return back()->with([
                            'failed' => 'You\'ve no unit of measurement. Contact your campus BAC Secretariat'
                        ]);
                    }
                // TODO 6
                    $items = \DB::table('items')
                        ->join('mode_of_procurement', 'mode_of_procurement.id', 'items.mode_of_procurement_id')
                        ->whereNull('mode_of_procurement.deleted_at')
                        ->whereNull('items.deleted_at')
                        ->get();
                    // ? KEY 6
                    if((count($items) <= 0) || $items == null) {
                        return back()->with([
                            'failed' => 'You\'ve no items. Contact your campus BAC Secretariat'
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
                            'project_titles'    => $project_titles,
                            'items' => \json_decode($items),
                            'mode_of_procurements'  =>  $mode_of_procurements,
                            'unit_of_measurements'  =>  $unit_of_measurement,
                            'ppmp_response' => $ppmp_response,
                            'allocated_budgets' => $allocated_budgets
                        ]
                    );
                # end
           } catch (\Throwable $th) {
                // throw $th;
                return view('pages.error-500');
           }
        }

        /**
         * * View PPMP Status | My PPMP Tab
         * TODO 1: Get all projects that been created by end-user
         * TODO 2: Get all project that has been declined or disapproved
         * TODO 3: Get all project that been accepted or approved
         * ? ----------------------------
         * ? KEY 1: Get the ppmp deadline end date
         */
        public function showMyPPMP() {
            try {
                # this is for affixing header links above the card directoryyy
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],
                        ["name" => "My PPMP"]
                    ];
                    # this will return the department.my-PPMP
                        return view('pages.department.my-ppmp-status',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                    );
            } catch (\Throwable $th) {
                // throw $th;
                return view('pages.error-500');
            }
        }

        /**
         * * Show PPMP 
         * * this will show the PPMP on the My PPMP Tab
         * TODO 1: Get the specified PPMP based on search.
         * TODO 2: Get total estimated price per project
         * ? -----------
         * ? KEYS: year_created, procurement_type, status
         */
        public function show_by_year_created(Request $request) {
            try {
                // TODO 1
                $project_titles = \DB::table('project_titles')
                    ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                    ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                    ->join('allocated__budgets', 'allocated__budgets.id', 'project_titles.allocated_budget')
                    ->join('ppmp_deadline', 'ppmp_deadline.year', 'allocated__budgets.year')
                    //* project_titlles
                    ->where('project_titles.campus', session('campus'))
                    ->where('project_titles.employee_id', session('employee_id'))
                    ->where('project_titles.department_id', session('department_id'))
                    ->whereNull('project_titles.deleted_at')
                    //* allocated__budgets
                    ->where('allocated__budgets.campus', session('campus'))
                    ->whereNull('allocated__budgets.deleted_at')
                    //? KEYS
                    ->where('project_titles.project_year', $request->year_created)
                    ->where('project_titles.project_category', $request->procurement_type)
                    ->where('project_titles.status', $request->status)
                    ->where('allocated__budgets.procurement_type', $request->procurement_type)
                    ->where('ppmp_deadline.procurement_type', $request->procurement_type)
                    ->get([
                        'project_titles.*',
                        'allocated__budgets.remaining_balance',
                        'allocated__budgets.allocated_budget',
                        'allocated__budgets.deadline_of_submission',
                        'ppmp_deadline.start_date',
                        'ppmp_deadline.end_date',
                        'users.name as immediate_supervisor',
                        'fund_sources.fund_source'
                    ]);

                // TODO 2
                $total_estimated_price = array();
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

                
                # this is for affixing header links above the card directoryyy
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],
                        ["name" => "My PPMP"]
                    ];
                    # this will return the department.my-PPMP
                    return view('pages.department.my-ppmp-status', compact('project_titles', 'total_estimated_price'),
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                    );
            } catch (\Throwable $th) {
                throw $th;
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

        /**
        * * Submit PPMP | Add item Page
        * TODO 1: Get the specified project tile
        * TODO 2: Get the ppmps or items added to that project title
        * TODO 3: Get allocated budgets
        * TODO 4: Get all mode of procurements
        * TODO 5: Get all unit of measurements
        * TODO 6: Get all items
        * ? --------------------------
        * ? KEY 1: join allocated budgets and ppmp deadline tables
        * ? KEY 2: Get deadline of submission thru pppmp deadline end date
        * ? KEY 3: Check if department has allocated budgets
        * ? KEY 4: Check if department has mode of procurements
        * ? KEY 5: Check if department has unit of measurements
        * ? KEY 6: Check if department has items 
        * ? KEY 7: Check if current date has exceeded the ppmp deadline end date
        */
        public function show_disapproved_items(Request $request) {
            $id = (new AESCipher)->decrypt($request->id);
            try {
                // TODO 1
                    $project_titles = \DB::table('project_titles')
                        ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                        ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                        ->where('project_titles.id', (new AESCipher)->decrypt($request->id))
                        ->where('project_titles.campus', session('campus'))
                        ->where('project_titles.department_id', session('department_id'))
                        ->where('project_titles.employee_id', session('employee_id'))
                        ->whereNull('users.deleted_at')
                        ->get([
                            'project_titles.*',
                            'users.name as immediate_supervisor',
                            'fund_sources.fund_source'
                        ]);
                // TODO 2
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
                // TODO 3
                    $allocated_budgets = \DB::table('allocated__budgets')
                        ->join('ppmp_deadline', 'ppmp_deadline.year', 'allocated__budgets.year')
                        ->where('allocated__budgets.id', (new AESCipher)->decrypt($request->allocated_budget))
                        ->where('allocated__budgets.campus', session('campus'))
                        ->where('allocated__budgets.department_id', session('department_id'))
                        ->where('ppmp_deadline.end_date', '<', Carbon::now()->format('Y-m-d')) // ? KEY 7
                        ->whereNull('allocated__budgets.deleted_at')
                        ->get([
                            'ppmp_deadline.start_date',
                            'ppmp_deadline.end_date', // ? KEY 2
                            'allocated__budgets.*'
                        ]);
                    // ? KEY 3
                    if((count($allocated_budgets) <= 0) || $allocated_budgets == null) {
                        return back()->with([
                            'failed' => 'You\'ve zero (0) allocated budget. Contact your campus budget officer'
                        ]);
                    }
                // TODO 4       
                    $mode_of_procurements = \DB::table('mode_of_procurement')
                        ->whereNull('deleted_at')
                        ->get();
                    // ? KEY 4
                    if((count($mode_of_procurements) <= 0) || $mode_of_procurements == null) {
                        return back()->with([
                            'failed' => 'You\'ve no mode of procurment. Contact your campus BAC Secretariat'
                        ]);
                    }
                // TODO 5
                    $unit_of_measurement = \DB::table('unit_of_measurements')
                        ->whereNull('deleted_at')
                        ->get();
                    // ? KEY 5
                    if((count($unit_of_measurement) <= 0) || $unit_of_measurement == null) {
                        return back()->with([
                            'failed' => 'You\'ve no unit of measurement. Contact your campus BAC Secretariat'
                        ]);
                    }
                // TODO 6
                    $items = \DB::table('items')
                        ->join('mode_of_procurement', 'mode_of_procurement.id', 'items.mode_of_procurement_id')
                        ->whereNull('mode_of_procurement.deleted_at')
                        ->whereNull('items.deleted_at')
                        ->get();
                    // ? KEY 6
                    if((count($items) <= 0) || $items == null) {
                        return back()->with([
                            'failed' => 'You\'ve no items. Contact your campus BAC Secretariat'
                        ]);
                    }
                # end
                # this will return the department.my-PPMP
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],
                        ["link" => "/department/project-category", "name" => "PROJECT CATEGORY"],
                        ["name" => "VIEW ITEM"]
                    ];
                    return view('pages.department.view-disapproved-items',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                        # this will attache the data to view
                        [
                            'id' => $id,
                            'project_titles'    => $project_titles,
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
                (new HistoryLogController)->store(session('department_id'),session('employee_id'),session('campus'),null,'Searched Signed PPMP: '.$request->file_name,'Search',$request->ip());

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
                    if(session('role') == 2){
                    return view('pages.budgetofficer.signed-ppmp', compact('response'));
                    }
                    return view('pages.department.upload-ppmp', compact('response'));
                } catch (\Throwable $th) {
                    // throw $th;
                    return view('pages.error-500');
                }
            }

        /**
         * * Request for PPMP Submission | all project view
         * TODO 1: Get all the allocated budgets that are sent for PPMP request submission
         */
        public function show_ppmp_submission() {
           try {
                /** This will return table and page configs */
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],
                        ["name" => "PPMP Submission"]
                    ];
                    return view('pages.department.ppmp-submission',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
                        [
                          
                        ]);
           } catch (\Throwable $th) {
                //throw $th;
                return view('pages.error-500');
           }
        }
        
}
