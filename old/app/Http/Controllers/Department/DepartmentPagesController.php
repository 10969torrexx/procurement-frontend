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
            //   return view('pages.page-coming-soon');
                # this will get all data from the fund sources table
                $allocated_budgets = (new AllocatedBudgetsController)->index();
                $pageConfigs = ['pageHeader' => true];
                $breadcrumbs = [
                ["link" => "/", "name" => "Home"],["name" => "Announcements"]
                ];
                return view('pages.department.announcements',
                    ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
                    [
                        'allocated_budgets'   =>  $allocated_budgets['data'],
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
                    // from ppmp response table
                    $project_titles = (new ProjectsController)->showAllDraft();
                    $pt_show_disapproved = (new ProjectsController)->show_disapproved();
                    // from departments | this query grabs 
                    $departments = Departments::where('id', session('department_id'))->get();
                    // from categories table
                    $categories_response = (new CategoriesController)->index();
                    // from fund so\urces table
                    $fund_sources = (new FundSourcesController)->index();
                    
                # this will check if all required data from database are not null
                    # this will display if fund sources returns null
                    if($fund_sources['status'] == 400) {
                        return view('pages.page-maintenance', [
                            'error' => $fund_sources['message']
                        ]);
                    }
                # end
                # this will return the department.submit-PPMP
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],
                        ["link" => "/department/project-category", "name" => "PROJECT CATEGORY"],
                        ["name" => "PROJECT TITLES"],
                    ];
                # end
                # this will return the page
                    return view('pages.department.create-ppmp',
                    ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                    # this will attache the data to view
                    [
                        'ProjectTitleResponse' => $project_titles['data'],
                        // 'AllocatedBudget' => $allocated_budgets['data'],
                        'fund_sources'   => $fund_sources['data'],
                        'departments'   =>  $departments,
                        'categories'    => json_decode($categories_response['data']),
                        'project_category' => $request->project_category,
                        'pt_show_disapproved'   => $pt_show_disapproved['data']
                    ]);
                # end
            }
        # end
        # this will show add item on project based on project title
        public function showAddItem(Request $request) {
            $id = $this->aes->decrypt($request->id);
            try {
                # this will grab the specific title based department id, employee id, campus, project year
                    $ProjectTitleResponse = Http::withToken(session('token'))->post(env('APP_API'). "/api/deparment/ProjectTitles/data", [
                        'department_id' =>   $this->aes->encrypt(session('department_id')),
                        'employee_id'   =>  $this->aes->encrypt(session('employee_id')),
                        'campus'    =>   $this->aes->encrypt(session('campus')),
                        'id'  =>  $request->id
                    ])->json();
                # end
                # this will get the item based on the project code, department id, employee id 
                    $ppmp_response =  Http::withToken(session('token'))->post(env('APP_API'). "/api/department/ppmp/data", [
                        'department_id' =>   $this->aes->encrypt(session('department_id')),
                        'employee_id'   =>  $this->aes->encrypt(session('employee_id')),
                        'campus'    =>   $this->aes->encrypt(session('campus')),
                        'project_code'  =>  $request->id
                    ])->json();
                # end
                # this will get data from database
                    $allocated_budgets = (new AllocatedBudgetsController)->show((new AESCipher)->decrypt($request->allocated_budget));
                    $mode_of_procurements = Http::withToken(session('token'))->get(env('APP_API'). "/api/department/ModeOfProcurements/data")->json();
                    $unit_of_measurement = Http::withToken(session('token'))->get(env('APP_API'). "/api/department/UnitOfMeasurement/data")->json();
                    $items = (new ItemsController)->index();
                # end
                # this will determine if the required data are not null
                    # this will display if there are no retrieved mode of procurements
                    if($mode_of_procurements['status'] == 400) {
                        # if there are null data this will retur na page maintenance page
                        // return view('pages.page-maintenance')->with(['error' => $mode_of_procurments['message']]);
                        \Session::put('error', $mode_of_procurements['message']);
                        return view('pages.page-maintenance');
                    }
                    # this will display if there are no retrieved unit of measurements
                    if($unit_of_measurement['status'] == 400) {
                        # if there are null data this will retur na page maintenance page
                        // return view('pages.page-maintenance')->with(['error' => $mode_of_procurments['message']]);
                        \Session::put('error', $unit_of_measurement['message']);
                        return view('pages.page-maintenance');
                    }
                    # this will display if there are no retrieved items
                    if($items['status'] == 400) {
                        # if there are null data this will retur na page maintenance page
                        // return view('pages.page-maintenance')->with(['error' => $mode_of_procurments['message']]);
                        \Session::put('error', $items['message']);
                        return view('pages.page-maintenance');
                    }
                # end
                # this is for affixing header links above the card directoryyy
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],
                        ["link" => "/department/project-category", "name" => "PROJECT CATEGORY"],
                        ["link" => "/department/createPPMP", "name" => "PROJECT TITLES"],
                        ["name" => "ADD ITEM"]
                    ];
                # end
                # this will return the department.my-PPMP
                    return view('pages.department.add-item',
                        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
                        # this will attache the data to view
                        [
                            'id' => $id,
                            'ProjectTitleResponse'    => $ProjectTitleResponse['data'],
                            'items' =>  $items['data'],
                            'mode_of_procurements'  =>  $mode_of_procurements['data'],
                            'unit_of_measurements'  =>  $unit_of_measurement['data'],
                            'ppmp_response' => $ppmp_response['data'],
                           'allocated_budgets' => $allocated_budgets['data']
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
                $project_titles = (new ProjectsController)->index();
            # this will check if all required data is not null 
                if(count($project_titles['data']) <= 0) {
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
                    'project_titles' => $project_titles['data']
                ]
            );
        }

        # this will show the My PPMP Page based on the provided department id by the logged in user
        public function show_by_year_created(Request $request) {
            # this will get all the data form the ppmp table based on the given department_id
                $project_titles = (new ProjectsController)->show_by_year_created($request->year_created);
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
                    'project_titles' => $project_titles['data']
                ]
            );
        }

        # this will show the status of the project
        public function showProjectStatus(Request $request) {
            $project_titles = (new ProjectsController)->show($request);
            $ppmp_response = (new PpmpController)->show($request->id);
            $project_timeline = (new ProjectTimelineController)->index($request->id);
            # this will check if all required data is not null 
                if(count($project_titles['data']) <= 0) {
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
                    'project_titles' => $project_titles['data'],
                    'ppmp_response' => $ppmp_response['data'],
                    'project_timeline'  => $project_timeline['data']
                ]
            );
        }

        # this wil display view disapproved items pages
        public function show_disapproved_items(Request $request) {
            $id = $this->aes->decrypt($request->id);
            # this will grab the specific title based department id, employee id, campus, project year
                $project_title = (new ProjectsController)->show($request);
            # end
            # this will get the item based on the project code, department id, employee id 
                $ppmp_response =  ( new PpmpController)->show_ppmp_projectcode_disapproved($request);
            # end
            # this will get data from database
                $allocated_budgets = (new AllocatedBudgetsController)->show((new AESCipher)->decrypt($request->allocated_budget));
                $mode_of_procurements = Http::withToken(session('token'))->get(env('APP_API'). "/api/department/ModeOfProcurements/data")->json();
                $unit_of_measurement = Http::withToken(session('token'))->get(env('APP_API'). "/api/department/UnitOfMeasurement/data")->json();
                $items = (new ItemsController)->index();
            # end
            # this is for affixing header links above the card directoryyy
             $pageConfigs = ['pageHeader' => true];
             $breadcrumbs = [
                ["link" => "/", "name" => "Home"],
                ["link" => "/department/project-category", "name" => "PROJECT CATEGORY"],
                ["link" => "/department/createPPMP", "name" => "PROJECT TITLES"],
                ["name" => "DISAPPROVED PPMP"]
             ];
            # this will return the view-disapproved-items
             return view('pages.department.view-disapproved-items',
             ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
             # this will attache the data to view
             [
                'id' => $id,
                'ProjectTitleResponse'    => $project_title['data'],
                'items' =>  $items['data'],
                'mode_of_procurements'  =>  $mode_of_procurements['data'],
                'unit_of_measurements'  =>  $unit_of_measurement['data'],
                'ppmp_response' => $ppmp_response['data'],
                'allocated_budgets' => $allocated_budgets['data']
             ]
         );
        }
}
