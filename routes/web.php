<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BAC\ItemController;
use App\Http\Controllers\Department\PurchaseRequestController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Admin\ShowUsersController;
use App\Http\Controllers\BAC\APPNONCSEController;
use App\Http\Controllers\BAC\ApprovedPPMPController;
use App\Http\Controllers\BAC\ApprovedSupplementalController;
use App\Http\Controllers\BAC\CategoryController;
use App\Http\Controllers\BAC\ModeofProcurementController;
use App\Http\Controllers\BAC\NewPPMPRequestController;
use App\Http\Controllers\BAC\RequestforAmendmentsController;
use App\Http\Controllers\BAC\UnitofMeasurementController;
/* Torrexx Additionals */
    // ! departments controller imports
        use App\Http\Controllers\Department\DepartmentPagesController;
        use App\Http\Controllers\Department\DepartmentController;
        use App\Http\Controllers\BudgetOfficer\BudgetOfficerController;
    // ! END
    // ! BOR Secretary imports
        use App\Http\Controllers\BOR_Secretary\BOR_SecretaryPagesController;
        use App\Http\Controllers\BOR_Secretary\BOR_SecretaryController;
    // ! END
    
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// dashboard Routes
Route::get('/','DashboardController@dashboard')->middleware("authuser");
/* Torrexx Addionals */
    # this will re-route to dashboard page
    // Route::get('/','DashboardController@dashboard')->middleware("authuser");

Route::group(['prefix' => 'users','middleware' => ['authuser']], function() {
    Route::get('/','UsersController@users');
});

Route::group(['prefix' => 'employee','middleware' => ['authuser']], function() {
    Route::get('/user','Employee\EmployeeController@index');
    Route::get('/my_par','Employee\EmployeeController@my_par');
    Route::get('/my_ics','Employee\EmployeeController@my_ics');
});

#Romar
#Start Route for Admin

// Route::get('/users', [UsersController::class, 'getUsers']);

// Route::post('users', [AdminController::class, 'store']);
Route::group(['prefix' => 'superadmin','middleware' => ['authuser']], function() {
    Route::get('/users', 'SuperAdmin\AdminController@index');
    Route::post('/save', 'SuperAdmin\AdminController@store')->name('save');
    Route::post('/pass', 'SuperAdmin\AdminController@pass')->name('pass');
    Route::post('/delete_user', 'SuperAdmin\AdminController@delete')->name('delete');
    Route::post('/edit', 'SuperAdmin\AdminController@edit')->name('edit');
    Route::post('/update', 'SuperAdmin\AdminController@update')->name('update');

    
    // Route::get('/getDepartments', 'SuperAdmin\AdminController@departments');
    Route::post('/getUsers', 'SuperAdmin\AdminController@getUsers');

    
    Route::get('/getDepartmentsByCampus', 'SuperAdmin\AdminController@getDepartmentsByCampus')->name('getDepartmentsByCampus');

    Route::get('/departments', 'SuperAdmin\AdminController@departments_index');
    Route::get('/getDepartments', 'BudgetOfficer\BudgetOfficerController@getDepartments')->name('getDepartments');
    Route::get('/getFundSources', 'BudgetOfficer\BudgetOfficerController@getFundSources')->name('getFundSources');
    Route::get('/getMandatoryExpenditures', 'BudgetOfficer\BudgetOfficerController@getMandatoryExpenditures')->name('getMandatoryExpenditures');
    Route::get('/getYears', 'BudgetOfficer\BudgetOfficerController@get_years')->name('getYears');

    #START CRUD EXPENDITURES ROUTES
    Route::get('/mandatory_expenditure_list', 'Admin\AdminController@mandatory_expenditures_index');
    Route::post('/save_mandatory_expenditure', 'Admin\AdminController@save_mandatory_expenditure')->name('save_mandatory_expenditure');
    Route::post('/delete_mandatory_expenditure', 'Admin\AdminController@delete_mandatory_expenditure')->name('delete_mandatory_expenditure');
    Route::post('/edit_mandatory_expenditure', 'Admin\AdminController@edit_mandatory_expenditure')->name('edit_mandatory_expenditure');
    Route::post('/update_mandatory_expenditure', 'Admin\AdminController@update_mandatory_expenditure')->name('update_mandatory_expenditure');
    #END CRUD EXPENDITURES ROUTES

    Route::get('/ppmp_deadline','BudgetOfficer\BudgetOfficerController@ppmp_deadline_index');
    Route::post('/save_ppmp_deadline','BudgetOfficer\BudgetOfficerController@save_ppmp_deadline')->name('save_ppmp_deadline');
    Route::post('/edit_deadline', 'BudgetOfficer\BudgetOfficerController@edit_deadline')->name('edit_deadline');
    Route::post('/update_deadline', 'BudgetOfficer\BudgetOfficerController@update_deadline')->name('update_deadline');
    Route::post('/delete_deadline', 'BudgetOfficer\BudgetOfficerController@delete_deadline')->name('delete_deadline');

    // Route::get('/ppmp_deadline','SuperAdmin\AdminController@setDeadlineIndex');
    // Route::post('/set_ppmp_deadline','SuperAdmin\AdminController@set_deadline')->name('set_ppmp_deadline');
    // Route::post('/edit_ppmp_deadline', 'SuperAdmin\AdminController@edit_deadline')->name('edit_ppmp_deadline');
    // Route::post('/update_ppmp_deadline', 'SuperAdmin\AdminController@update_deadline')->name('update_ppmp_deadline');
    // Route::post('/delete_ppmp_deadline', 'SuperAdmin\AdminController@delete_deadline')->name('delete_ppmp_deadline');

    Route::get('/fund_sources', 'SuperAdmin\AdminController@fund_sources_index');
    Route::post('/add_FundSource','SuperAdmin\AdminController@addFundSource')->name('add_FundSource');
    Route::post('/edit_FundSource', 'SuperAdmin\AdminController@editFundSource')->name('edit_FundSource');
    Route::post('/update_FundSource', 'SuperAdmin\AdminController@updateFundSource')->name('update_FundSource');
    Route::post('/delete_FundSource', 'SuperAdmin\AdminController@deleteFundSource')->name('delete_FundSource');

    #EXPENDITURES MENU ROUTES
    // Route::get('/getFundSources', 'SuperAdmin\AdminController@getFundSources')->name('getFundSources');
    // Route::get('/mandatory_expenditures', 'SuperAdmin\AdminController@mandatory_expenditures_index');
    // Route::post('/add_MandatoryExpenditure','SuperAdmin\AdminController@addMandatoryExpenditure')->name('add_MandatoryExpenditure');
    // Route::post('/edit_MandatoryExpenditure', 'SuperAdmin\AdminController@editMandatoryExpenditure')->name('edit_MandatoryExpenditure');
    // Route::post('/update_MandatoryExpenditure', 'SuperAdmin\AdminController@updateMandatoryExpenditure')->name('update_MandatoryExpenditure');
    // Route::post('/delete_MandatoryExpenditure', 'SuperAdmin\AdminController@deleteMandatoryExpenditure')->name('delete_MandatoryExpenditure');

    Route::get('/mandatory_expenditures','BudgetOfficer\BudgetOfficerController@mandatory_expenditures_index');
    Route::post('/addMandatoryExpenditure','BudgetOfficer\BudgetOfficerController@addMandatoryExpenditure')->name('addMandatoryExpenditure');
    Route::post('/editMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@editMandatoryExpenditure')->name('editMandatoryExpenditure');
    Route::post('/updateMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@updateMandatoryExpenditure')->name('updateMandatoryExpenditure');
    Route::post('/deleteMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@deleteMandatoryExpenditure')->name('deleteMandatoryExpenditure');

    // Route::get('/allocate_budget','SuperAdmin\AdminController@allocateBudgetIndex');
    // Route::post('/save_allocate_budget','SuperAdmin\AdminController@save_allocate_budget')->name('save_allocate_budget');
    // Route::post('/delete_allocated_budget', 'SuperAdmin\AdminController@delete_allocated_budget')->name('delete_allocated_budget');
    // Route::post('/edit_allocated_budget', 'SuperAdmin\AdminController@edit_allocated_budget')->name('edit_allocated_budget');
    // Route::post('/update_allocated_budget', 'SuperAdmin\AdminController@update_allocated_budget')->name('update_allocated_budget');

    Route::get('/allocate_budget','BudgetOfficer\BudgetOfficerController@allocate_budget_index');
    Route::post('/allocate_budget','BudgetOfficer\BudgetOfficerController@allocate_budget')->name('allocate_budget');
    Route::post('/delete_allocated_budget', 'BudgetOfficer\BudgetOfficerController@delete_allocated_budget')->name('delete_allocated_budget');
    Route::post('/edit_allocated_budget', 'BudgetOfficer\BudgetOfficerController@edit_allocated_budget')->name('edit_allocated_budget');
    Route::post('/updateAllocatedBudget', 'BudgetOfficer\BudgetOfficerController@update_allocated_budget')->name('updateAllocatedBudget');

    Route::get('/get_Departments', 'SuperAdmin\AdminController@get_Departments')->name('get_Departments');
    Route::get('/get_FundSources', 'SuperAdmin\AdminController@get_FundSources')->name('get_FundSources');
    Route::get('/get_Mandatory_Expenditures', 'SuperAdmin\AdminController@get_MandatoryExpenditures')->name('get_MandatoryExpenditures');

    
    Route::post('/saveDepartment', 'SuperAdmin\AdminController@saveDepartment')->name('saveDepartment');
    Route::post('/deleteDepartment', 'SuperAdmin\AdminController@deleteDepartment')->name('deleteDepartment');
    Route::post('/editDepartment', 'SuperAdmin\AdminController@editDepartment')->name('editDepartment');
    Route::post('/updateDepartment', 'SuperAdmin\AdminController@updateDepartment')->name('updateDepartment');
    Route::post('/getDepartmentHeads', 'SuperAdmin\AdminController@getDepartmentHeads')->name('getDepartmentHeads');
    Route::post('/getSupervisors', 'SuperAdmin\AdminController@getSupervisors')->name('getSupervisors');
    Route::get('/get_Years', 'SuperAdmin\AdminController@getYears')->name('get_Years');
    Route::get('/getDeadlineByYear', 'SuperAdmin\AdminController@getDeadlineByYear')->name('getDeadlineByYear');
    Route::get('/getYearByType', 'SuperAdmin\AdminController@getYearByType')->name('getYearByType');


    //PURCHASE REQUEST ROUTES
    Route::get('/purchase_request', 'SuperAdmin\AdminController@PurchaseRequestIndex');



    //END PURCHASE REQUEST ROUTES

      // Route::get('/users', 'SuperAdmin\AdminController@index');
    //jerald
    
    //Category
    Route::get('/category', 'BAC\CategoryController@index');
    Route::post('/add-category', 'BAC\CategoryController@store')->name('add-category');
    Route::post('/delete-category', 'BAC\CategoryController@delete')->name('delete-category');
    Route::post('/show-category', 'BAC\CategoryController@show')->name('show-category');
    Route::post('/update-category', 'BAC\CategoryController@update')->name('update-category');

    //Items
    Route::get('/items', 'BAC\ItemController@additems');
    Route::post('/add-item', 'BAC\ItemController@store')->name('add-item');
    Route::post('/delete-item', 'BAC\ItemController@delete')->name('delete-item');
    Route::post('/show-item', 'BAC\ItemController@show')->name('show-item');
    Route::post('/update-item', 'BAC\ItemController@update')->name('update-item');

    //Unit of Measurement
    Route::get('/unit-of-measurement', 'BAC\UnitofMeasurementController@index');
    Route::post('/add-unit', 'BAC\UnitofMeasurementController@store')->name('add-unit');
    Route::post('/delete-unit', 'BAC\UnitofMeasurementController@delete')->name('delete-unit');
    Route::post('/show-unit', 'BAC\UnitofMeasurementController@show')->name('show-unit');
    Route::post('/update-unit', 'BAC\UnitofMeasurementController@update')->name('update-unit');

    //Mode of Procurement
    Route::get('/mode-of-procurement', 'BAC\ModeofProcurementController@index');
    Route::post('/add-procurement', 'BAC\ModeofProcurementController@store')->name('add-procurement');
    Route::post('/delete-procurement', 'BAC\ModeofProcurementController@delete')->name('delete-procurement');
    Route::post('/show-procurement', 'BAC\ModeofProcurementController@show')->name('show-procurement');
    Route::post('/update-procurement', 'BAC\ModeofProcurementController@update')->name('update-procurement');

    //Approved PPMP
    Route::get('/approved-ppmp', 'BAC\ApprovedPPMPController@index');
    Route::post('/show-approved', 'BAC\ApprovedPPMPController@show');
    
    //Approved Supplemental
    Route::get('/approved-supplemental', 'BAC\ApprovedSupplementalController@index');
    Route::post('/show-supplemental', 'BAC\ApprovedSupplementalController@show');
    
    //New PPMP Request
    Route::get('/request-new-ppmp', 'BAC\NewPPMPRequestController@index');
    // Route::post('/show-approved', 'BAC\ApprovedPPMPController@show');

    
    //generate APP - NONCSE
    Route::get('/app-non-cse', 'BAC\APPNONCSEController@index');
    Route::get('/try', 'BAC\APPNONCSEController@try');
    Route::post('/app-non-cse-generate', 'BAC\APPNONCSEController@generatepdf')->name('app-non-cse-generate');
    Route::get('/app-non-cse-generate-excel', 'BAC\APPNONCSEController@generateexcel')->name('app-non-cse-generate-excel');
    Route::post('/show-signatories', 'BAC\APPNONCSEController@show_signatories')->name('show-signatories');
    Route::post('/add-preparedby', 'BAC\APPNONCSEController@add_preparedby')->name('add-preparedby');
    Route::post('/add-approvedby', 'BAC\APPNONCSEController@add_approvedby')->name('add-approvedby');
    Route::post('/add-recommendingapproval', 'BAC\APPNONCSEController@add_recommendingapproval')->name('add-recommendingapproval');
    Route::post('/update-signatories', 'BAC\APPNONCSEController@update_signatories')->name('update-signatories');
    Route::get('/app-non-cse-year', 'BAC\APPNONCSEController@app_non_cse_year')->name('app-non-cse-year');
    
    Route::post('/show-campusinfo', 'BAC\APPNONCSEController@show_campusinfo')->name('show-campusinfo');
    Route::post('/update-campusinfo', 'BAC\APPNONCSEController@update_campusinfo')->name('update-campusinfo');
    // Route::post('/show-campuslogo', 'BAC\APPNONCSEController@show_campuslogo')->name('show-campusinfo');
    Route::post('/update-campuslogo', 'BAC\APPNONCSEController@update_campuslogo')->name('update-campuslogo');
    Route::post('/update-logo', 'BAC\APPNONCSEController@update_logo')->name('update-logo');

    
    Route::group(['prefix' => 'supervisor','middleware' => ['authuser']], function() {
        //Supervisor Side
        Route::get('/', 'Supervisor\SupervisorControllerTraditional@index');
        Route::post('/show-ppmp', 'Supervisor\SupervisorControllerTraditional@show')->name('show-ppmp');
        Route::post('/supervisor-ppmp-approved', 'Supervisor\SupervisorControllerTraditional@status')->name('supervisor-ppmp-approved');
        Route::post('/supervisor-ppmp-disapproved', 'Supervisor\SupervisorControllerTraditional@status')->name('supervisor-ppmp-disapproved');
    });
    
    //Ammendments
    Route::get('/request-for-amendments', 'BAC\RequestforAmendmentsController@index');

     /* Torrexx Additionals */
        /** Implementing Functions Related to the department end user */
            # this will show dashboard for end user role
            Route::get('/announcements', [DepartmentPagesController::class, 'showAnnouncementPage'])->name('department-dashboard');
            # this will show the create project title 
            Route::get('/project-category', [DepartmentPagesController::class, 'showProjectCategory'])->name('derpartment-project-category');

            # this will create the PPMP or the project title
            Route::get('/createPPMP', [DepartmentPagesController::class, 'showCreatePPMP'])->name('department-showCreatetPPMP');
            # this will submit created project title by the end user or department roloe= 4 
            Route::post('/createPPMP', [DepartmentController::class, 'createProjectTitle'])->name('department-createProjectTitle');
            # this will get the project title based on id
            Route::get('/get-project-title', [DepartmentController::class, 'getProjectTitle'])->name('department-get-project');
            # this will delete project title 
            Route::get('/destroy-project-title', [DepartmentController::class, 'destoryProjectTitle'])->name('department-destroy-project');
            # this will update project title
            Route::post('/update-project-title', [DepartmentController::class, 'updateProjectTitle'])->name('department-update-project');

            # this will show add item page based on the clicked project title id
            Route::get('/addItem/projectid', [DepartmentPagesController::class, 'showAddItem'])->name('department-addItem');
            # this will create items on the items table
            Route::post('/create-ppmps', [DepartmentController::class, 'createPPMPs'])->name('department-create-ppmps');
            # this will fetch all item description based on the given item name
            Route::post('/fetch/item-description', [DepartmentController::class, 'getItemDescription'])->name('department-item-description');
            # this will fetch all the item category based item name
            Route::post('/fetch/item-category', [DepartmentController::class, 'getItemCategory'])->name('department-item-category');
            # this will update item details on ppmps table
            Route::post('/update/item-details', [DepartmentController::class, 'updatePPMPS'])->name('department-update-item');
            # deleting data from ppmps table
            Route::post('/delete/item-details', [DepartmentController::class, 'deletePPMPS'])->name('department-delete-item');
            # this will submit ppmp on the 
            Route::post('/sumbit/ppmp', [DepartmentController::class, 'submitPPMP'])->name('department-submit-ppmp');

            # this will show the my ppmp page based on the department id
            Route::get('/myPPMP', [DepartmentPagesController::class, 'showMyPPMP'])->name('department-showMyPPMP');
            # this will show the status of the project along with its item
            Route::post('/project/status', [DepartmentPagesController::class, 'showProjectStatus'])->name('department-showProjectStatus');
        /** Gettin data from the database */
            # this will get the databa by unit of measure per item fron the unit measure from the dabase
            Route::post('/UnitOfMeasure', 'Department\UnitOfMeasurementController@show')->name('UnitOfMeasure');
            # get data all project
            Route::post('/getAllProject', [ProjectsController::class, 'show'])->name('department-get-projects');
            # this will get the project title by year created
            Route::post('/get-by-year-created', [DepartmentPagesController::class, 'show_by_year_created'])->name('department-year-created');

            Route::get('/pending_ppmps','BudgetOfficer\BudgetOfficerController@PPMPindex');
            Route::post('/view_ppmp/showPPMP', 'BudgetOfficer\BudgetOfficerController@showPPMP')->name('showPPMP');
            Route::post('/view_ppmp/showPPMP/ppmp-approved', 'BudgetOfficer\BudgetOfficerController@status')->name('ppmp-approved');
            Route::post('/view_ppmp/showPPMP/ppmp-disapproved', 'BudgetOfficer\BudgetOfficerController@status')->name('ppmp-disapproved');

});

Route::group(['prefix' => 'admin','middleware' => ['authuser']], function() {
    #START CRUD ACCOUNT ROUTES
    Route::get('/accounts_index', 'Admin\AdminController@accounts_index');
    Route::get('/get_accounts_HRMIS', 'Admin\AdminController@get_accounts_HRMIS')->name('get_accounts_HRMIS');
    Route::get('/getDepartmentsByCampus', 'Admin\AdminController@getDepartmentsByCampus')->name('getDepartmentsByCampus');
    Route::post('/save_account', 'Admin\AdminController@save_account')->name('save_account');
    Route::post('/edit_account', 'Admin\AdminController@edit_account')->name('edit_account');
    Route::post('/update_account', 'Admin\AdminController@update_account')->name('update_account');
    Route::post('/delete_account', 'Admin\AdminController@delete_account')->name('delete_account');
    #END CRUD ACCOUNT ROUTES

    #START CRUD DEPARTMENT ROUTES
    Route::get('/departments_index', 'Admin\AdminController@departments_index');
    Route::get('/get_accounts_PMIS', 'Admin\AdminController@get_accounts_PMIS')->name('get_accounts_PMIS');
    Route::post('/save_department', 'Admin\AdminController@save_department')->name('save_department');
    Route::post('/delete_department', 'Admin\AdminController@delete_department')->name('delete_department');
    Route::post('/edit_department', 'Admin\AdminController@edit_department')->name('edit_department');
    Route::post('/update_department', 'Admin\AdminController@update_department')->name('update_department');
    #END CRUD DEPARTMENT ROUTES

    #START CRUD EXPENDITURES ROUTES
    Route::get('/expenditures_index', 'Admin\AdminController@mandatory_expenditures_index');
    Route::post('/save_mandatory_expenditure', 'Admin\AdminController@save_mandatory_expenditure')->name('save_mandatory_expenditure');
    Route::post('/delete_mandatory_expenditure', 'Admin\AdminController@delete_mandatory_expenditure')->name('delete_mandatory_expenditure');
    Route::post('/edit_mandatory_expenditure', 'Admin\AdminController@edit_mandatory_expenditure')->name('edit_mandatory_expenditure');
    Route::post('/update_mandatory_expenditure', 'Admin\AdminController@update_mandatory_expenditure')->name('update_mandatory_expenditure');
    #END CRUD EXPENDITURES ROUTES

    #START CRUD FUND SOURCE ROUTES
    Route::get('/fund_sources_index', 'Admin\AdminController@fund_sources_index');
    Route::post('/add_fund_source','Admin\AdminController@add_fund_source')->name('add_fund_source');
    Route::post('/edit_fund_source', 'Admin\AdminController@edit_fund_source')->name('edit_fund_source');
    Route::post('/update_fund_source', 'Admin\AdminController@update_fund_source')->name('update_fund_source');
    Route::post('/delete_fund_source', 'Admin\AdminController@delete_fund_source')->name('delete_fund_source');
    #END CRUD FUND SOURCE ROUTES

    #START CRUD ITEM ROUTES
    Route::get('/items_index', 'Admin\AdminController@items_index');
    Route::post('/add_item', 'Admin\AdminController@add_item');
    Route::post('/delete_item', 'Admin\AdminController@delete_item')->name('delete_item');
    Route::post('/edit_item', 'Admin\AdminController@edit_item')->name('edit_item');
    Route::post('/update_item', 'Admin\AdminController@update_item')->name('update_item');

    Route::get('/get_mode_of_procurement', 'Admin\AdminController@get_mode_of_procurement')->name('get_mode_of_procurement');
    #END CRUD ITEM ROUTES

    #START CRUD DEADLINE ROUTES
    Route::get('/ppmp_deadline','BudgetOfficer\BudgetOfficerController@ppmp_deadline_index');
    Route::post('/save_ppmp_deadline','BudgetOfficer\BudgetOfficerController@save_ppmp_deadline')->name('save_ppmp_deadline');
    Route::post('/edit_deadline', 'BudgetOfficer\BudgetOfficerController@edit_deadline')->name('edit_deadline');
    Route::post('/update_deadline', 'BudgetOfficer\BudgetOfficerController@update_deadline')->name('update_deadline');
    Route::post('/delete_deadline', 'BudgetOfficer\BudgetOfficerController@delete_deadline')->name('delete_deadline');
    #END CRUD DEADLINE ROUTES

    #START CRUD MANDATORY EXPENDITURE ROUTES
    Route::get('/mandatory_expenditures_index','BudgetOfficer\BudgetOfficerController@mandatory_expenditures_index');
    Route::post('/addMandatoryExpenditure','BudgetOfficer\BudgetOfficerController@addMandatoryExpenditure')->name('addMandatoryExpenditure');
    Route::post('/editMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@editMandatoryExpenditure')->name('editMandatoryExpenditure');
    Route::post('/updateMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@updateMandatoryExpenditure')->name('updateMandatoryExpenditure');
    Route::post('/deleteMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@deleteMandatoryExpenditure')->name('deleteMandatoryExpenditure');

    Route::get('/getDepartments', 'BudgetOfficer\BudgetOfficerController@getDepartments')->name('getDepartments');
    Route::get('/getFundSources', 'BudgetOfficer\BudgetOfficerController@getFundSources')->name('getFundSources');
    Route::get('/getMandatoryExpenditures', 'BudgetOfficer\BudgetOfficerController@getMandatoryExpenditures')->name('getMandatoryExpenditures');
    Route::get('/getYears', 'BudgetOfficer\BudgetOfficerController@get_years')->name('getYears');
    #END CRUD MANDATORY EXPENDITURE ROUTES

    #START CRUD ALLOCATE BUDGET ROUTES
    Route::get('/allocate_budget_index','BudgetOfficer\BudgetOfficerController@allocate_budget_index');
    Route::post('/allocate_budget','BudgetOfficer\BudgetOfficerController@allocate_budget')->name('allocate_budget');
    Route::post('/delete_allocated_budget', 'BudgetOfficer\BudgetOfficerController@delete_allocated_budget')->name('delete_allocated_budget');
    Route::post('/edit_allocated_budget', 'BudgetOfficer\BudgetOfficerController@edit_allocated_budget')->name('edit_allocated_budget');
    Route::post('/updateAllocatedBudget', 'BudgetOfficer\BudgetOfficerController@update_allocated_budget')->name('updateAllocatedBudget');

    Route::get('/get_procurement_type', 'BudgetOfficer\BudgetOfficerController@get_procurement_type')->name('get_procurement_type');
    Route::get('/get_DeadlineByYear', 'BudgetOfficer\BudgetOfficerController@get_DeadlineByYear')->name('get_DeadlineByYear');
    #END CRUD ALLOCATE BUDGET ROUTES   

    #START CRUD PENDING PPMPS ROUTES
    Route::get('/view_ppmp_index','BudgetOfficer\BudgetOfficerController@PPMPindex')->name('view_ppmp');
    Route::get('/returnView','BudgetOfficer\BudgetOfficerController@returnView');
    Route::post('/view_ppmp/showPPMP', 'BudgetOfficer\BudgetOfficerController@showPPMP')->name('showPPMP');
    Route::post('/view_ppmp/showPPMP/ppmp-status', 'BudgetOfficer\BudgetOfficerController@status')->name('ppmp-status');
    Route::post('/view_ppmp/showPPMP/ppmp-timeline', 'BudgetOfficer\BudgetOfficerController@timeline')->name('ppmp-timeline');
    #END CRUD PENDING PPMPS ROUTES   

    #START VIEW SIGNED PPMPS ROUTE
    Route::get('/view_signed_ppmp_index','BudgetOfficer\BudgetOfficerController@signed_ppmps_index');
    #END VIEW SIGNED PPMPS ROUTE

    #START CRUD CATEGORY ROUTES
    Route::get('/category_index', 'Admin\AdminController@category_index');
    Route::post('/add_category', 'Admin\AdminController@add_category')->name('add_category');
    Route::post('/delete_category', 'Admin\AdminController@delete_category')->name('delete_category');
    Route::post('/edit_category', 'Admin\AdminController@edit_category')->name('edit_category');
    Route::post('/update_category', 'Admin\AdminController@update_category')->name('update_category');
    #END CRUD CATEGORY ROUTES

    #START CRUD UNIT OF MEASUREMENT ROUTES
    Route::get('/unit_of_measurement_index', 'Admin\AdminController@unit_of_measurement_index');
    Route::post('/add_unit', 'Admin\AdminController@add_unit')->name('add_unit');
    Route::post('/delete_unit', 'Admin\AdminController@delete_unit')->name('delete_unit');
    Route::post('/edit_unit', 'Admin\AdminController@edit_unit')->name('edit_unit');
    Route::post('/update_unit', 'Admin\AdminController@update_unit')->name('update_unit');
    #END CRUD UNIT OF MEASUREMENT ROUTES

    #START CRUD MODE OF PROCUREMENT ROUTES
    Route::get('/mode_of_procurement_index', 'Admin\AdminController@mode_of_procurement_index');
    Route::post('/add_procurement', 'Admin\AdminController@add_procurement')->name('add_procurement');
    Route::post('/delete_procurement', 'Admin\AdminController@delete_procurement')->name('delete_procurement');
    Route::post('/edit_procurement', 'Admin\AdminController@edit_procurement')->name('edit_procurement');
    Route::post('/update_procurement', 'Admin\AdminController@update_procurement')->name('update_procurement');
    #END CRUD MODE OF PROCUREMENT ROUTES

    #START VIEW APPROVED AND SIGNED PPMP ROUTES
    Route::get('/approved_ppmp', 'Admin\AdminController@approved_ppmp_index');
    Route::post('/show_approved_ppmp', 'Admin\AdminController@show_approved_ppmp');
    #END VIEW APPROVED AND SIGNED PPMP ROUTES


    #START PENDING PPMP ROUTES
    Route::get('/pending_ppmps_index', 'Admin\AdminController@pending_ppmps_index');
    Route::post('/show_ppmp', 'Admin\AdminController@show_ppmp')->name('show_ppmp');
    Route::post('/supervisor_ppmp_approved', 'Admin\AdminController@status')->name('supervisor_ppmp_approved');
    Route::post('/supervisor_ppmp_disapproved', 'Admin\AdminController@status')->name('supervisor_ppmp_disapproved');
    #END PENDING PPMP ROUTES

    // Route::post('/save', 'Admin\AdminController@store')->name('save');
    // Route::post('/delete', 'Admin\AdminController@delete')->name('delete');
    // Route::post('/edit', 'Admin\AdminController@edit')->name('edit');
    // Route::post('/update', 'Admin\AdminController@update')->name('update');
});

//Jerald 
Route::post('/add-item', 'BAC\ItemController@store');
Route::get('/delete-item/{id}', 'BAC\ItemController@delete');

Route::group(['prefix' => 'budgetofficer','middleware' => ['authuser']], function() {
    Route::get('/try','BudgetOfficer\BudgetOfficerController@try');
    Route::get('/my_par','BudgetOfficer\BudgetOfficerController@my_par');
    Route::get('/my_ics','BudgetOfficer\BudgetOfficerController@my_ics');

    Route::get('/ppmp_deadline','BudgetOfficer\BudgetOfficerController@ppmp_deadline_index');
    Route::post('/save_ppmp_deadline','BudgetOfficer\BudgetOfficerController@save_ppmp_deadline')->name('save_ppmp_deadline');
    Route::post('/edit_deadline', 'BudgetOfficer\BudgetOfficerController@edit_deadline')->name('edit_deadline');
    Route::post('/update_deadline', 'BudgetOfficer\BudgetOfficerController@update_deadline')->name('update_deadline');
    Route::post('/delete_deadline', 'BudgetOfficer\BudgetOfficerController@delete_deadline')->name('delete_deadline');

    Route::get('/mandatory_expenditures','BudgetOfficer\BudgetOfficerController@mandatory_expenditures_index');
    Route::post('/addMandatoryExpenditure','BudgetOfficer\BudgetOfficerController@addMandatoryExpenditure')->name('addMandatoryExpenditure');
    Route::post('/editMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@editMandatoryExpenditure')->name('editMandatoryExpenditure');
    Route::post('/updateMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@updateMandatoryExpenditure')->name('updateMandatoryExpenditure');
    Route::post('/deleteMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@deleteMandatoryExpenditure')->name('deleteMandatoryExpenditure');

    Route::get('/allocate_budget','BudgetOfficer\BudgetOfficerController@allocate_budget_index');
    // Route::get('/allocate_budget1','BudgetOfficer\AllocateBudgetController@index1');
    Route::post('/allocate_budget','BudgetOfficer\BudgetOfficerController@allocate_budget')->name('allocate_budget');
    // Route::post('/delete', 'BudgetOfficer\AllocateBudgetController@delete')->name('delete');
    Route::post('/delete_allocated_budget', 'BudgetOfficer\BudgetOfficerController@delete_allocated_budget')->name('delete_allocated_budget');
    Route::post('/edit_allocated_budget', 'BudgetOfficer\BudgetOfficerController@edit_allocated_budget')->name('edit_allocated_budget');
    Route::post('/updateAllocatedBudget', 'BudgetOfficer\BudgetOfficerController@update_allocated_budget')->name('updateAllocatedBudget');

    Route::get('/getDepartments', 'BudgetOfficer\BudgetOfficerController@getDepartments')->name('getDepartments');
    Route::get('/getFundSources', 'BudgetOfficer\BudgetOfficerController@getFundSources')->name('getFundSources');
    Route::get('/getMandatoryExpenditures', 'BudgetOfficer\BudgetOfficerController@getMandatoryExpenditures')->name('getMandatoryExpenditures');
    Route::get('/getYears', 'BudgetOfficer\BudgetOfficerController@get_years')->name('getYears');
    Route::get('/get_procurement_type', 'BudgetOfficer\BudgetOfficerController@get_procurement_type')->name('get_procurement_type');
    Route::get('/get_DeadlineByYear', 'BudgetOfficer\BudgetOfficerController@get_DeadlineByYear')->name('get_DeadlineByYear');

    Route::get('/view_signed_ppmp','BudgetOfficer\BudgetOfficerController@signed_ppmps_index');
    Route::get('/view_ppmp','BudgetOfficer\BudgetOfficerController@PPMPindex')->name('view_ppmp');
    Route::get('/returnView','BudgetOfficer\BudgetOfficerController@returnView');
    Route::post('/view_ppmp/showPPMP', 'BudgetOfficer\BudgetOfficerController@showPPMP')->name('showPPMP');
    Route::post('/view_ppmp/showPPMP/ppmp-status', 'BudgetOfficer\BudgetOfficerController@status')->name('ppmp-status');
    Route::post('/view_ppmp/showPPMP/ppmp-timeline', 'BudgetOfficer\BudgetOfficerController@timeline')->name('ppmp-timeline');
    Route::post('/view_ppmp/showPPMP/accept-reject-all', 'BudgetOfficer\BudgetOfficerController@accept_reject_all')->name('accept-reject-all');

});
#End Route for Budget Officer

Route::group(['prefix' => 'bac','middleware' => ['authuser']], function() {
    // Route::get('/users', 'Admin\AdminController@index');
    //jerald
    
    //Category
    Route::get('/category', 'BAC\CategoryController@index');
    Route::post('/add-category', 'BAC\CategoryController@store')->name('add-category');
    Route::post('/delete-category', 'BAC\CategoryController@delete')->name('delete-category');
    Route::post('/show-category', 'BAC\CategoryController@show')->name('show-category');
    Route::post('/update-category', 'BAC\CategoryController@update')->name('update-category');

    //Items
    Route::get('/items', 'BAC\ItemController@additems');
    Route::post('/add-item', 'BAC\ItemController@store')->name('add-item');
    Route::post('/delete-item', 'BAC\ItemController@delete')->name('delete-item');
    Route::post('/show-item', 'BAC\ItemController@show')->name('show-item');
    Route::post('/update-item', 'BAC\ItemController@update')->name('update-item');

    //Unit of Measurement
    Route::get('/unit-of-measurement', 'BAC\UnitofMeasurementController@index');
    Route::post('/add-unit', 'BAC\UnitofMeasurementController@store')->name('add-unit');
    Route::post('/delete-unit', 'BAC\UnitofMeasurementController@delete')->name('delete-unit');
    Route::post('/show-unit', 'BAC\UnitofMeasurementController@show')->name('show-unit');
    Route::post('/update-unit', 'BAC\UnitofMeasurementController@update')->name('update-unit');

    //Mode of Procurement
    Route::get('/mode-of-procurement', 'BAC\ModeofProcurementController@index');
    Route::post('/add-procurement', 'BAC\ModeofProcurementController@store')->name('add-procurement');
    Route::post('/delete-procurement', 'BAC\ModeofProcurementController@delete')->name('delete-procurement');
    Route::post('/show-procurement', 'BAC\ModeofProcurementController@show')->name('show-procurement');
    Route::post('/update-procurement', 'BAC\ModeofProcurementController@update')->name('update-procurement');

    //Approved PPMP
    Route::get('/approvedIndicative', 'BAC\ApprovedPPMPController@Indicative_index');
    Route::get('/approvedPPMP', 'BAC\ApprovedPPMPController@PPMP_index');
    Route::get('/approvedSupplemental', 'BAC\ApprovedPPMPController@Supplemental_index');
    Route::post('/show-approved-ppmp', 'BAC\ApprovedPPMPController@show')->name('show-approved-ppmp');
    Route::get('/signedIndicative', 'BAC\ApprovedPPMPController@signed_indicative')->name('signed-indicative');
    Route::get('/signedPPMP', 'BAC\ApprovedPPMPController@signed_ppmp')->name('signed-ppmp');
    Route::get('/signedSupplemental', 'BAC\ApprovedPPMPController@signed_supplemental')->name('signed-supplemental');
    Route::post('/view-signed-ppmp', 'BAC\ApprovedPPMPController@view_signed_ppmp')->name('view-signed-ppmp');
    Route::get('/download-signed-ppmp', 'BAC\ApprovedPPMPController@download_signed_ppmp')->name('download-signed-ppmp');

    //Approved Supplemental
    Route::get('/approved-supplemental', 'BAC\ApprovedSupplementalController@index');
    Route::post('/show-supplemental', 'BAC\ApprovedSupplementalController@show');
    
    //New PPMP Request
    Route::get('/request-new-ppmp', 'BAC\NewPPMPRequestController@index');
    // Route::post('/show-approved', 'BAC\ApprovedPPMPController@show');

    
    //generate APP - NONCSE
    Route::get('/app-non-cse-traditional', 'BAC\APPNONCSEController@traditional_index');
    Route::get('/app-non-cse-indicative', 'BAC\APPNONCSEController@indicative_index');
    Route::get('/app-non-cse-supplemental', 'BAC\APPNONCSEController@supplemental_index');
    Route::get('/try', 'BAC\APPNONCSEController@try');
    Route::post('/app-non-cse-done', 'BAC\APPNONCSEController@endorse_to_main')->name('app-non-cse-done');
    Route::post('/app-non-cse-submitpres', 'BAC\APPNONCSEController@submit_to_president')->name('app-non-cse-submitpres');
    Route::post('/app-non-cse-generate', 'BAC\APPNONCSEController@generatepdf')->name('app-non-cse-generate');
    Route::post('/show-all', 'BAC\APPNONCSEController@university_wide')->name('show-all');
    Route::get('/app-non-cse-generate-excel', 'BAC\APPNONCSEController@generateexcel')->name('app-non-cse-generate-excel');
    Route::post('/show-signatories', 'BAC\APPNONCSEController@show_signatories')->name('show-signatories');
    Route::post('/add-preparedby', 'BAC\APPNONCSEController@add_preparedby')->name('add-preparedby');
    Route::post('/add-approvedby', 'BAC\APPNONCSEController@add_approvedby')->name('add-approvedby');
    Route::post('/add-recommendingapproval', 'BAC\APPNONCSEController@add_recommendingapproval')->name('add-recommendingapproval');
    Route::post('/update-signatories', 'BAC\APPNONCSEController@update_signatories')->name('update-signatories');
    Route::get('/app-non-cse-year', 'BAC\APPNONCSEController@app_non_cse_year')->name('app-non-cse-year');
    Route::post('/app-non-cse-print', 'President\PresidentHopeController@print')->name('app-non-cse-print');
    
    Route::post('/show-campusinfo', 'BAC\APPNONCSEController@show_campusinfo')->name('show-campusinfo');
    Route::post('/update-campusinfo', 'BAC\APPNONCSEController@update_campusinfo')->name('update-campusinfo');
    // Route::post('/show-campuslogo', 'BAC\APPNONCSEController@show_campuslogo')->name('show-campusinfo');
    Route::post('/update-campuslogo', 'BAC\APPNONCSEController@update_campuslogo')->name('update-campuslogo');
    Route::post('/update-logo', 'BAC\APPNONCSEController@update_logo')->name('update-logo');

    // Route::post('/supervisor-ppmp-done', 'Supervisor\SupervisorControllerTraditional@done')->name('supervisor-ppmp-done');

    
    
    
    //Ammendments
    Route::get('/request-for-amendments', 'BAC\RequestforAmendmentsController@index');
    // Route::post('/app-non-cse-view', 'BAC\APPNONCSEController@view')->name('app-non-cse-view');
    //jrald end
    // Route::get('/adduser','Admin\AdminController@adduser');
    
});

Route::group(['prefix' => 'supervisor','middleware' => ['authuser']], function() {
    //Supervisor Side
    Route::get('/traditional', 'Supervisor\SupervisorController@Traditional_index');
    Route::post('/show-traditional-ppmp', 'Supervisor\SupervisorController@show_Traditional')->name('show-traditional-ppmp');
    Route::get('/supplemental', 'Supervisor\SupervisorController@Supplemental_index');
    Route::post('/show-supplemental-ppmp', 'Supervisor\SupervisorController@show_Supplemental')->name('show-supplemental-ppmp');
    Route::get('/indicative', 'Supervisor\SupervisorController@indicative_index');
    Route::post('/show-indicative-ppmp', 'Supervisor\SupervisorController@show_indicative')->name('show-indicative-ppmp');
    Route::post('/supervisor-ppmp-approved', 'Supervisor\SupervisorController@status')->name('supervisor-ppmp-approved');
    Route::post('/supervisor-ppmp-disapproved', 'Supervisor\SupervisorController@status')->name('supervisor-ppmp-disapproved');
    Route::post('/supervisor-ppmp-done', 'Supervisor\SupervisorController@done')->name('supervisor-ppmp-done');
    Route::post('/accept-reject-all', 'Supervisor\SupervisorController@accept_reject_all')->name('accept-reject-all');

    // Route::post('/supervisor-ppmp-approved', 'Supervisor\SupervisorControllerSupplemental@status')->name('supervisor-ppmp-approved');
    // Route::post('/supervisor-ppmp-disapproved', 'Supervisor\SupervisorControllerSupplemental@status')->name('supervisor-ppmp-disapproved');
    // Route::post('/supervisor-ppmp-done', 'Supervisor\SupervisorControllerSupplemental@done')->name('supervisor-ppmp-done');
    // Route::post('/accept-reject-all', 'Supervisor\SupervisorControllerSupplemental@accept_reject_all')->name('accept-reject-all');
});

Route::group(['prefix' => 'president','middleware' => ['authuser']], function() {
    //Supervisor Side
    Route::get('/list/{id}', 'President\PresidentHopeController@list');
    // Route::get('/traditional', 'President\PresidentHopeController@Traditional_index');
    Route::post('/pres_app_noncse', 'President\PresidentHopeController@index_app_noncse')->name('pres_app_noncse');
    Route::post('/pres_generatepdf', 'President\PresidentHopeController@generatepdf')->name('pres_generatepdf');
    Route::post('/pres_print', 'President\PresidentHopeController@print')->name('pres_print');
    Route::post('/pres_decision', 'President\PresidentHopeController@pres_decision')->name('pres_decision');
    Route::get('/supplemental', 'President\PresidentHopeController@Supplemental_index');
    Route::get('/indicative', 'President\PresidentHopeController@indicative_index');
});

Route::group(['prefix' => 'baccommittee','middleware' => ['authuser']], function() {
    //Supervisor Side
    Route::get('/list/{id}', 'BACCommittee\BACCommitteeController@list');
    // Route::get('/traditional', 'BACCommittee\BACCommitteeController@Traditional_index');
    Route::post('/bac_committee_app_noncse', 'BACCommittee\BACCommitteeController@index_app_noncse')->name('bac_committee_app_noncse');
    Route::post('/bac_committee_generatepdf', 'BACCommittee\BACCommitteeController@generatepdf')->name('bac_committee_generatepdf');
    Route::post('/bac_committee_print', 'BACCommittee\BACCommitteeController@print')->name('bac_committee_print');
    Route::post('/bac_committee_decision', 'BACCommittee\BACCommitteeController@bac_committee_decision')->name('bac_committee_decision');
    Route::get('/supplemental', 'BACCommittee\BACCommitteeController@Supplemental_index');
    Route::get('/indicative', 'BACCommittee\BACCommitteeController@indicative_index');
});

Route::group(['prefix' => 'supply_custodian','middleware' => ['authuser']], function() {

    Route::get('/property','SupplyCustodian\PropertyController@index');
    Route::post('/assign-par','SupplyCustodian\PropertyController@assign_par')->name('assign-par');
    Route::post('/searchPAR','SupplyCustodian\PropertyController@search')->name('searchPAR');
    Route::post('/finalize-par','SupplyCustodian\PropertyController@finalize_par')->name('finalize-par');
    Route::post('/delete-par','SupplyCustodian\PropertyController@delete_par')->name('delete-par');
    Route::post('/edit-par','SupplyCustodian\PropertyController@edit_par')->name('edit-par');
    Route::post('/finaldelete-par','SupplyCustodian\PropertyController@finaldelete_par')->name('finaldelete-par');
    Route::post('/transfer-par','SupplyCustodian\PropertyController@transfer_par')->name('transfer-par');
    Route::post('/submittransfer-par','SupplyCustodian\PropertyController@submittransfer_par')->name('submittransfer-par');
    Route::post('/print-par','SupplyCustodian\PropertyController@print_par')->name('print-par');
    Route::post('/print','SupplyCustodian\PropertyController@print')->name('print');
    Route::post('/submitdelete-par','SupplyCustodian\PropertyController@submitdelete_par')->name('submitdelete-par');
    Route::post('/submitEdit-par','SupplyCustodian\PropertyController@submitEdit_par')->name('submitEdit-par');
    Route::post('/submitadd-par','SupplyCustodian\PropertyController@submitadd_par')->name('submitadd-par');
    Route::post('/additem-par','SupplyCustodian\PropertyController@additem_par')->name('additem-par');

    
    Route::get('/ics','SupplyCustodian\ICSController@index')->name('ics_index');
    Route::post('/searchICS','SupplyCustodian\ICSController@search')->name('searchICS');
    Route::post('/assign-ics','SupplyCustodian\ICSController@assign_ics')->name('assign-ics');
    Route::post('/finalize-ics','SupplyCustodian\ICSController@finalize_ics')->name('finalize-ics');
    Route::post('/delete-ics','SupplyCustodian\ICSController@delete_ics')->name('delete-ics');
    Route::post('/finaldelete-ics','SupplyCustodian\ICSController@finaldelete_ics')->name('finaldelete-ics');
    Route::post('/transfer-ics','SupplyCustodian\ICSController@transfer_ics')->name('transfer-ics');
    Route::post('/submittransfer-ics','SupplyCustodian\ICSController@submittransfer_ics')->name('submittransfer-ics');
    Route::post('/print-ics','SupplyCustodian\ICSController@print_ics')->name('print-ics');
    Route::post('/printics','SupplyCustodian\ICSController@print')->name('printics');
    Route::post('/submitdelete-ics','SupplyCustodian\ICSController@submitdelete_ics')->name('submitdelete-ics');
    Route::post('/edit-ics','SupplyCustodian\ICSController@edit_ics')->name('edit-ics');
    Route::post('/submitEdit-ics','SupplyCustodian\ICSController@submitEdit_ics')->name('submitEdit-ics');
    Route::post('/submitadd-ics','SupplyCustodian\ICSController@submitadd_ics')->name('submitadd-ics');
    Route::post('/additem-ics','SupplyCustodian\ICSController@additem_ics')->name('additem-ics');


    Route::get('/supplier','SupplyCustodian\SupplierController@index');
    Route::post('/add-supplier','SupplyCustodian\SupplierController@add_supplier')->name('add-supplier');
    Route::post('/edit-supplier','SupplyCustodian\SupplierController@edit_supplier')->name('edit-supplier');
    Route::post('/submitedit-supplier','SupplyCustodian\SupplierController@submitedit_supplier')->name('submitedit-supplier');
    Route::post('/delete-supplier','SupplyCustodian\SupplierController@delete_supplier')->name('delete-supplier');
// });

});

Route::group(['prefix' => 'PR','middleware' => ['authuser']], function() {
    Route::get('/purchaseRequest', 'Department\PurchaseRequestController@PurchaseRequestIndex')->name('purchaseRequest');
    Route::post('/purchaseRequest/add_Items_To_PR', 'Department\PurchaseRequestController@add_Items_To_PR');
    Route::post('/purchaseRequest/addItem', 'Department\PurchaseRequestController@addItem')->name('addItem');
    Route::post('/purchaseRequest/updateItem', 'Department\PurchaseRequestController@updateItem')->name('updateItem');
    Route::get('/purchaseRequest/createPR', 'Department\PurchaseRequestController@createPR')->name('createPR');
    Route::post('/purchaseRequest/createPR/remove_item', 'Department\PurchaseRequestController@remove_item')->name('remove_item');
    Route::get('/purchaseRequest/getEmployees', 'Department\PurchaseRequestController@getEmployees');
    Route::get('/purchaseRequest/getItems', 'Department\PurchaseRequestController@getItems');
    Route::get('/purchaseRequest/getItem', 'Department\PurchaseRequestController@getItem');
    Route::get('/purchaseRequest/editPRItem', 'Department\PurchaseRequestController@editPRItem');
    Route::post('/purchaseRequest/savePR', 'Department\PurchaseRequestController@savePR');

    Route::get('/trackPR', 'Department\PurchaseRequestController@TrackPRIndex')->name('trackPR');
    Route::post('/trackPR/view_pr/printPR', 'Department\PurchaseRequestController@printPR')->name('printPR');
    Route::get('/trackPR/view_status', 'Department\PurchaseRequestController@view_status')->name('view_status');
    Route::get('/trackPR/view_pr', 'Department\PurchaseRequestController@view_pr')->name('view_pr');
    Route::post('/trackPR/delete_pr', 'Department\PurchaseRequestController@delete_pr')->name('delete_pr');
    Route::get('/trackPR/edit_pr', 'Department\PurchaseRequestController@editPR')->name('edit_pr');

});
# route for department-group
Route::group(['prefix' => 'department','middleware' => ['authuser']], function() {
    //MY PROPERTY MENU ROUTES
    Route::get('/my_par','Employee\EmployeeController@my_par');
    Route::get('/my_ics','Employee\EmployeeController@my_ics');

    //PURCHASE REQUEST ROUTES
   


    //END PURCHASE REQUEST ROUTES
    
    /* Torrexx Additionals */
        /** Implementing Functions Related to the department end user */
            # this will show dashboard for end user role
            Route::get('/announcements', [DepartmentPagesController::class, 'showAnnouncementPage'])->name('department-dashboard');
            # this will show the create project title 
            Route::get('/project-category', [DepartmentPagesController::class, 'showProjectCategory'])->name('derpartment-project-category');

            # this will create the PPMP or the project title
            Route::get('/createPPMP', [DepartmentPagesController::class, 'showCreatePPMP'])->name('department-showCreatetPPMP');
            # this will submit created project title by the end user or department roloe= 4 
            Route::post('/createPPMP', [DepartmentController::class, 'createProjectTitle'])->name('department-createProjectTitle');
            # this will get the project title based on id
            Route::get('/get-project-title', [DepartmentController::class, 'getProjectTitle'])->name('department-get-project');
            # this will delete project title 
            Route::get('/destroy-project-title', [DepartmentController::class, 'destoryProjectTitle'])->name('department-destroy-project');
            # this will update project title
            Route::post('/update-project-title', [DepartmentController::class, 'updateProjectTitle'])->name('department-update-project');

            # this will show add item page based on the clicked project title id
            Route::get('/addItem/projectid', [DepartmentPagesController::class, 'showAddItem'])->name('department-addItem');
            # this will create items on the items table
            Route::post('/create-ppmps', [DepartmentController::class, 'createPPMPs'])->name('department-create-ppmps');
            # this will fetch all item description based on the given item name
            Route::post('/fetch/item-description', [DepartmentController::class, 'getItemDescription'])->name('department-item-description');
            # this will fetch all the item category based item name
            Route::post('/fetch/item-category', [DepartmentController::class, 'getItemCategory'])->name('department-item-category');
            # this will update item details on ppmps table
            Route::post('/update/item-details', [DepartmentController::class, 'updatePPMPS'])->name('department-update-item');
            # deleting data from ppmps table
            Route::get('/delete/item-details', [DepartmentController::class, 'deletePPMPS'])->name('department-delete-item');
            # this will submit ppmp on the 
            Route::post('/sumbit/ppmp', [DepartmentController::class, 'submitPPMP'])->name('department-submit-ppmp');

            # this will show the my ppmp page based on the department id
            Route::get('/myPPMP', [DepartmentPagesController::class, 'showMyPPMP'])->name('department-showMyPPMP');
            # this will show the status of the project along with its item
            Route::post('/project/status', [DepartmentPagesController::class, 'showProjectStatus'])->name('department-showProjectStatus');
                # this will re-submit ppmp | revised PPMP
                Route::post('/re-sumbit/ppmp', [DepartmentController::class, 'resubmitPPMP'])->name('department-re_submit-ppmp');
                # this route will display the disapproved items
                Route::get('/disapproved-items', [DepartmentPagesController::class, 'show_disapproved_items'])->name('dept_disapproved-items');

            # submit all project titles on checkbox
            Route::get('submit-all-project', [DepartmentController::class, 'submit_all_projects'])->name('submit_all_projects');

            # export approved ppmp | this will generate PDF file of the Approved PPMP data
            Route::get('export-ppmp', [DepartmentController::class, 'export_approved_ppmp'])->name('export_ppmp');

            # ppmp submission | request for PPMP Submission
            Route::get('ppmp-submission', [DepartmentPagesController::class, 'show_ppmp_submission'])->name('show_ppmp_submission');

            # upload ppmp | upload signed ppmp
            Route::get('upload-ppmp', [DepartmentPagesController::class, 'show_upload_ppmp'])->name('show_upload_ppmp');
            # upload PPMP
            Route::post('upload-ppmp', [DepartmentController::class, 'upload_ppmp'])->name('upload_ppmp');
            Route::post('get-upload-ppmp', [DepartmentPagesController::class, 'get_upload_ppmp'])->name('get_upload_ppmp');
            # delete uploaded PPMP
            Route::get('delete-uploaded-ppmp', [DepartmentController::class, 'delete_uploaded_ppmp'])->name('delete_ppmp');
            # download uploaded PPMP
            Route::get('download-uploaded-ppmp', [DepartmentController::class, 'download_uploaded_ppmp'])->name('download_ppmp');
            # previw uploaded ppmp
            Route::get('view-uploaded-ppmp', [DepartmentController::class, 'view_uploaded_ppmp'])->name('view_ppmp');
            # edit upload
            Route::get('get-uploaded-ppmp', [DepartmentController::class, 'get_edit_ppmp'])->name('get_edit_ppmp');
            Route::post('edit-uploaded-ppmp', [DepartmentController::class, 'edit_uploaded_ppmp'])->name('edit_ppmp');
        /** END */

        /** Gettin data from the database */
            # this will get the databa by unit of measure per item fron the unit measure from the dabase
            Route::post('/UnitOfMeasure', 'Department\UnitOfMeasurementController@show')->name('UnitOfMeasure');
            # get data all project
            Route::post('/getAllProject', [ProjectsController::class, 'show'])->name('department-get-projects');
            # get all items
            Route::get('/get-items', [DepartmentController::class, 'get_items'])->name('get_items');
            # get specified mode of procurement
            Route::get('/get-mode-of-procurement', [DepartmentController::class, 'get_mode_of_procurement'])->name('get_mode_of_procurement');
            # get ppmps
            Route::get('/get-ppms', [DepartmentController::class, 'get_ppmps'])->name('get_ppmps');
            # live search item(s)
            Route::post('live-search-item', [DepartmentController::class, 'live_search_item'])->name('live_search_item');
    /** END | TORREXX */
});

/** TORREXX Additionals
 * ! BOR Secretary
 * ? this will show routes under BOR Secretary
 */
    Route::group(['prefix' => 'bor-secetary', 'middleware' => ['authuser']], function() {
        # show announcements page
            Route::get('/announcements', [BOR_SecretaryPagesController::class, 'show_announcements'])->name('bor_sec_announcements');
        // ? BOR Resolution
            // ! show upload BOR resolution
                Route::get('/upload-bor-resolution', [BOR_SecretaryPagesController::class, 'show_bor_resolution'])->name('bor_sec_bor_resolution');
            // ! upload BOR resolution
                Route::post('/upload-bor-resolution', [BOR_SecretaryController::class, 'upload_bor_resolution'])->name('upload_bor_resolution');
            // ! download BOR resolution
                Route::get('/download-bor-resolution', [BOR_SecretaryController::class, 'download_bor_resolution'])->name('download_bor_resolution');
            // ! Delete BOR resolution
                Route::get('/delete-bor-resolution', [BOR_SecretaryController::class, 'delete_bor_resolution'])->name('delete_bor_resolution');
            // ! View BOR resolution
                Route::get('/view-bor-resolution', [BOR_SecretaryController::class, 'view_bor_resolution'])->name('view_bor_resolution');
            // ! search uploaded bor resolution
                Route::post('/search-bor-resolution', [BOR_SecretaryController::class, 'search_bor_resolution'])->name('search_bor_resolution');
            // ! get bor resolution
                Route::get('/get-bor-resolution', [BOR_SecretaryController::class, 'get_bor_resolution'])->name('get_bor_resolution');
            // ! submit edit bor resolution
                Route::post('/edit-bor-resolution', [BOR_SecretaryController::class, 'edit_bor_resolution'])->name('edit_bor_resolution');
        // ? END
        // ? Recommended APP
            // ! show recommmended APP
            Route::get('/recomemnded-app', [BOR_SecretaryPagesController::class, 'show_recommended_app'])->name('show_recommended_app');
        // ? END
    });
/** END */

// Route::group(['prefix' => 'employee','middleware' => ['authuser']], function() {
//     Route::get('/','finalControllers\EmployeeController@index')->name('employeelist');
//     Route::post('/search-department-employment-status', 'finalControllers\EmployeeController@search');
//     Route::get('/edit','finalControllers\EmployeeController@edit')->name('employee.edit');
//     Route::post('/create','finalControllers\EmployeeController@store')->name('employee.add');
//     Route::post('/update','finalControllers\EmployeeController@update')->name('employee.update');
//     Route::post('/delete','finalControllers\EmployeeController@destroy')->name('employee.delete');
//     Route::get('/employeelist','finalControllers\EmployeeController@employeelist')->name('employee.list');
// });

// Route::group(['prefix' => 'inventory','middleware' => ['authuser']], function() {
//     Route::get('/','finalControllers\InventoryController@index');
//     Route::post('/search','finalControllers\InventoryController@search')->name("psearch");
//     Route::get('/print/{id}/{type}/{cluster}','finalControllers\InventoryController@print');
// });

// Route::group(['prefix' => 'property','middleware' => ['authuser']], function() {
//     Route::get('/','finalControllers\PropertyController@index');
//     Route::get('/print/{id}/{fund}/{empid}','finalControllers\PropertyController@print');
// });

// Route::group(['prefix' => 'ics','middleware' => ['authuser']], function() {
//     Route::get('/','finalControllers\ICSController@index');
//     Route::post('/search', 'finalControllers\ICSController@search');
//     Route::get('/search/{Employee}', 'finalControllers\ICSController@search');
//     Route::get('/print/{id}/{fund}/{empid}','finalControllers\ICSController@print');

// });

// Route::group(['prefix' => 'supplier','middleware' => ['authuser']], function() {
//     Route::get('/','finalControllers\SupplierController@index');
// });

// Route::group(['prefix' => 'department','middleware' => ['authuser']], function() {
//     Route::get('/','finalControllers\DepartmentController@index');
// });



#end

/* Default code block */
    //Route::post('/fileupload', [FileUploadController::class, 'store']);
    Route::post('/fileupload', 'Department\FileUploadController@store');

    // Route::get('/dashboard-ecommerce','DashboardController@dashboardEcommerce');
    // Route::get('/dashboard-analytics','DashboardController@dashboardAnalytics');


    // Authentication  Route
    Route::get('/logins','AuthenticationController@loginPage');
    Route::get('/logout','AuthenticationController@logout');
    Route::get('/admin/login','AuthenticationController@authLockPage')->name('admin.login');
    Route::post('/adminlogin','AuthenticationController@adminlogin');


    // Miscellaneous
    Route::get('/page-coming-soon','MiscellaneousController@comingSoonPage');
    Route::get('/error-404','MiscellaneousController@error404Page');
    Route::get('/error-500','MiscellaneousController@error500Page');
    Route::get('/page-not-authorized','MiscellaneousController@notAuthPage');
    Route::get('/page-maintenance','MiscellaneousController@maintenancePage');

    Route::get('auth/google', 'AuthenticationController@redirectToGoogle')->name('google.signin'); 
    Route::get('auth/google/callback', 'AuthenticationController@handleGoogleCallback');

    Auth::routes();

