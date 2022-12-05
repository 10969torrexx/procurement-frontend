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

use App\Http\Controllers\ProjectTimelineController;
use App\Models\Departments;

use App\Models\Project_Titles;

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
                        ->groupBy('fund_sources.fund_source', 'allocated__budgets.year')
                        ->orderBy('allocated__budgets.year')
                        ->get([
                            'fund_sources.id', 'allocated__budgets.*', 'fund_sources.fund_source', \DB::raw('sum(allocated__budgets.allocated_budget) as SumBudget')
                        ]); 
                    
                    $mandatory_expeditures = \DB::table("mandatory_expenditures as me")
                        ->select("me.year", "me.fund_source_id", \DB::raw('sum(me.price) as SumMandatory'))
                        ->where("me.department_id", $departmentID)
                        ->where("me.campus", session('campus'))
                        ->groupBy("me.year")
                        ->groupBy("me.fund_source_id")
                        ->get();

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
                # this will get data from database
                    # from project_titles table | draft project titles
                        $project_titles = \DB::table('project_titles')
                            ->join('fund_sources', 'fund_sources.id', 'project_titles.fund_source')
                            // ->join('departments', 'departments.immediate_supervisor', 'project_titles.department_id')
                            ->join('users', 'users.id', 'project_titles.immediate_supervisor')
                            ->where('project_titles.campus', session('campus'))
                            ->where('project_titles.department_id', session('department_id'))
                            ->where('project_titles.employee_id', session('employee_id'))
                            ->where('project_titles.status', 0) //status draft
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
                            // ->whereRaw("project_titles.status ='3' OR project_titles.status='5'")
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
                    # from departments table
                        $departments = \DB::table('departments')->where('id', session('department_id'))->get();
                    # from categories table
                        $categories = \DB::table('categories')->where('campus', session('campus'))->whereNull('deleted_at')->get();
                    # from fund sources table
                        $fund_sources = \DB::table('allocated__budgets')
                            ->join('fund_sources', 'fund_sources.id', 'allocated__budgets.fund_source_id')
                            ->where('allocated__budgets.campus', session('campus'))
                            ->where('allocated__budgets.department_id', session('department_id'))
                            ->where('allocated__budgets.procurement_type', 'PPMP') // make this dynamic
                            ->whereNull('allocated__budgets.deleted_at')
                            ->get(['allocated__budgets.*', 'allocated__budgets.id as allocated_id','fund_sources.fund_source']);
                # end 
                # this will return the page
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],
                        ["link" => "/department/project-category", "name" => "PROJECT CATEGORY"],
                        ["name" => "PROJECT TITLES"],
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
                        'pt_show_disapproved'   => $pt_show_disapproved
                    ]);
                # end
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
                        ->where('project_code', $id)
                        ->where('campus', session('campus'))
                        ->where('department_id', session('department_id'))
                        ->where('employee_id', session('employee_id'))
                        ->whereNull('deleted_at')
                        ->get();
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
                            ->where('campus', session('campus'))
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
                            ->where('campus', session('campus'))
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
                            ->where('campus', session('campus'))
                            ->whereNull('deleted_at')
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
               throw $th;
           }
        }

        # this will show the My PPMP Page based on the provided department id by the logged in user
        public function showMyPPMP() {
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

            # this will check if all required data is not null 
                if(count($project_titles) < 0) {
                    # if there are null data this will retur na page maintenance page
                    return view('pages.page-maintenance');
                } 
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
                    'project_titles' => $project_titles
                ]
            );
        }

        # this will show the My PPMP Page based on the provided department id by the logged in user
        public function show_by_year_created(Request $request) {
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
                ->whereNull('project_titles.deleted_at')
                ->get([
                    'project_titles.*',
                    'fund_sources.fund_source',
                    'users.name as immediate_supervisor' 
                ]);
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
                        'project_titles' => $project_titles
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
                $ppmp_response = (new PpmpController)->show($request->id);
                $project_timeline = (new ProjectTimelineController)->index($request->id);
                # this will check if all required data is not null 
                    if(count($project_titles) <= 0) {
                        # if there are null data this will retur na page maintenance page
                        return view('pages.page-maintenance');
                    } 

                    if(count($ppmp_response['data']) <= 0) {
                        # if there are null data this will retur na page maintenance page
                        return view('pages.page-maintenance');
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
                        'ppmp_response' => $ppmp_response['data'],
                        'project_timeline'  => $project_timeline['data']
                    ]
                );
            } catch (\Throwable $th) {
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
                        ->where('project_code', $id)
                        ->where('campus', session('campus'))
                        ->where('department_id', session('department_id'))
                        ->where('employee_id', session('employee_id'))
                        // ->whereRaw("status = '3' OR status = '5'")
                        ->where(function($query) {
                            $query->where('status', 3)
                                ->orWhere('status', 5);
                        })
                        ->whereNull('deleted_at')
                        ->get();
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
                            ->where('campus', session('campus'))
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
                            ->where('campus', session('campus'))
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
                            ->where('campus', session('campus'))
                            ->whereNull('deleted_at')
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
}
