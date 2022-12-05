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
    # the following imports the controller under Deparments Directory
    use App\Http\Controllers\Department\DepartmentPagesController;
    use App\Http\Controllers\Department\DepartmentController;
    use App\Http\Controllers\BudgetOfficer\BudgetOfficerController;
    
    

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

    Route::get('/departments', 'SuperAdmin\AdminController@getDepartments');
    
    // Route::get('/getDepartments', 'SuperAdmin\AdminController@departments');
    Route::post('/getUsers', 'SuperAdmin\AdminController@getUsers');

    
    Route::get('/getDepartmentsByCampus', 'SuperAdmin\AdminController@getDepartmentsByCampus')->name('getDepartmentsByCampus');

    Route::get('/getDepartments', 'BudgetOfficer\BudgetOfficerController@getDepartments')->name('getDepartments');
    Route::get('/getFundSources', 'BudgetOfficer\BudgetOfficerController@getFundSources')->name('getFundSources');
    Route::get('/getMandatoryExpenditures', 'BudgetOfficer\BudgetOfficerController@getMandatoryExpenditures')->name('getMandatoryExpenditures');
    Route::get('/getYears', 'BudgetOfficer\BudgetOfficerController@get_years')->name('getYears');

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
    // Route::get('/allocate_budget1','BudgetOfficer\AllocateBudgetController@index1');
    Route::post('/allocate_budget','BudgetOfficer\BudgetOfficerController@allocate_budget')->name('allocate_budget');
    Route::post('/delete', 'BudgetOfficer\AllocateBudgetController@delete')->name('delete');
    Route::post('/edit_allocated_budget', 'BudgetOfficer\BudgetOfficerController@edit_allocated_budget')->name('edit_allocated_budget');
    Route::post('/updateAllocatedBudget', 'BudgetOfficer\AllocateBudgetController@updateAllocatedBudget')->name('updateAllocatedBudget');

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
        Route::get('/', 'SupervisorController@index');
        Route::post('/show-ppmp', 'SupervisorController@show')->name('show-ppmp');
        Route::post('/supervisor-ppmp-approved', 'SupervisorController@status')->name('supervisor-ppmp-approved');
        Route::post('/supervisor-ppmp-disapproved', 'SupervisorController@status')->name('supervisor-ppmp-disapproved');
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
// Route::group(['prefix' => 'superadmin','middleware' => ['authuser']], function() {
//     Route::get('/users', 'SuperAdmin\AdminController@index');
//     Route::post('/save', 'SuperAdmin\AdminController@store')->name('save');
//     Route::post('/pass', 'SuperAdmin\AdminController@pass')->name('pass');
//     Route::post('/delete', 'SuperAdmin\AdminController@delete')->name('delete');
//     Route::post('/edit', 'SuperAdmin\AdminController@edit')->name('edit');
//     Route::post('/update', 'SuperAdmin\AdminController@update')->name('update');

//     Route::get('/departments', 'SuperAdmin\AdminController@getDepartments');
    
//     Route::get('/getDepartments', 'SuperAdmin\AdminController@departments');

//     Route::get('/get_Departments', 'BudgetOfficer\AllocateBudgetController@getDepartments')->name('getDepartments');
//     Route::get('/getFundSources', 'BudgetOfficer\AllocateBudgetController@getFundSources')->name('getFundSources');
//     Route::get('/getMandatoryExpenditures', 'BudgetOfficer\AllocateBudgetController@getMandatoryExpenditures')->name('getMandatoryExpenditures');
//     Route::get('/getYears', 'BudgetOfficer\AllocateBudgetController@get_years')->name('getYears');
//     Route::post('/getUsers', 'SuperAdmin\AdminController@getUsers');

//     Route::get('/ppmp_deadline','SuperAdmin\AdminController@setDeadlineIndex');
//     Route::post('/set_ppmp_deadline','SuperAdmin\AdminController@set_deadline')->name('set_ppmp_deadline');
//     Route::post('/edit_ppmp_deadline', 'SuperAdmin\AdminController@edit_deadline')->name('edit_ppmp_deadline');
//     Route::post('/update_ppmp_deadline', 'SuperAdmin\AdminController@update_deadline')->name('update_ppmp_deadline');
//     Route::post('/delete_ppmp_deadline', 'SuperAdmin\AdminController@delete_deadline')->name('delete_ppmp_deadline');

//     Route::get('/fund_sources', 'SuperAdmin\AdminController@fund_sources_index');
//     Route::post('/add_FundSource','SuperAdmin\AdminController@addFundSource')->name('add_FundSource');
//     Route::post('/edit_FundSource', 'SuperAdmin\AdminController@editFundSource')->name('edit_FundSource');
//     Route::post('/update_FundSource', 'SuperAdmin\AdminController@updateFundSource')->name('update_FundSource');
//     Route::post('/delete_FundSource', 'SuperAdmin\AdminController@deleteFundSource')->name('delete_FundSource');

//     Route::get('/mandatory_expenditures', 'SuperAdmin\AdminController@mandatory_expenditures_index');
//     Route::post('/add_MandatoryExpenditure','SuperAdmin\AdminController@addMandatoryExpenditure')->name('add_MandatoryExpenditure');
//     Route::post('/edit_MandatoryExpenditure', 'SuperAdmin\AdminController@editMandatoryExpenditure')->name('edit_MandatoryExpenditure');
//     Route::post('/update_MandatoryExpenditure', 'SuperAdmin\AdminController@updateMandatoryExpenditure')->name('update_MandatoryExpenditure');
//     Route::post('/delete_MandatoryExpenditure', 'SuperAdmin\AdminController@deleteMandatoryExpenditure')->name('delete_MandatoryExpenditure');

//     Route::get('/allocate_budget','SuperAdmin\AdminController@allocateBudgetIndex');
//     Route::post('/save_allocate_budget','SuperAdmin\AdminController@save_allocate_budget')->name('save_allocate_budget');
//     Route::post('/delete_allocated_budget', 'SuperAdmin\AdminController@delete_allocated_budget')->name('delete_allocated_budget');
//     Route::post('/edit_allocated_budget', 'SuperAdmin\AdminController@edit_allocated_budget')->name('edit_allocated_budget');
//     Route::post('/update_allocated_budget', 'SuperAdmin\AdminController@update_allocated_budget')->name('update_allocated_budget');

//     // Route::get('/get_Departments', 'SuperAdmin\AdminController@get_Departments')->name('get_Departments');
//     // Route::get('/get_FundSources', 'SuperAdmin\AdminController@get_FundSources')->name('get_FundSources');
//     // Route::get('/get_Mandatory_Expenditures', 'SuperAdmin\AdminController@get_MandatoryExpenditures')->name('get_MandatoryExpenditures');

    
//     Route::post('/saveDepartment', 'SuperAdmin\AdminController@saveDepartment')->name('saveDepartment');
//     Route::post('/deleteDepartment', 'SuperAdmin\AdminController@deleteDepartment')->name('deleteDepartment');
//     Route::post('/editDepartment', 'SuperAdmin\AdminController@editDepartment')->name('editDepartment');
//     Route::post('/updateDepartment', 'SuperAdmin\AdminController@updateDepartment')->name('updateDepartment');
//     Route::post('/getDepartmentHeads', 'SuperAdmin\AdminController@getDepartmentHeads')->name('getDepartmentHeads');
//     Route::post('/getSupervisors', 'SuperAdmin\AdminController@getSupervisors')->name('getSupervisors');
//     Route::get('/get_Years', 'SuperAdmin\AdminController@getYears')->name('get_Years');
//     Route::get('/getDeadlineByYear', 'SuperAdmin\AdminController@getDeadlineByYear')->name('getDeadlineByYear');
//     Route::get('/getYearByType', 'SuperAdmin\AdminController@getYearByType')->name('getYearByType');


//     //PURCHASE REQUEST ROUTES
//     Route::get('/purchase_request', 'SuperAdmin\AdminController@PurchaseRequestIndex');



//     //END PURCHASE REQUEST ROUTES

//       // Route::get('/users', 'SuperAdmin\AdminController@index');
//     //jerald
    
//     //Category
//     Route::get('/category', 'BAC\CategoryController@index');
//     Route::post('/add-category', 'BAC\CategoryController@store')->name('add-category');
//     Route::post('/delete-category', 'BAC\CategoryController@delete')->name('delete-category');
//     Route::post('/show-category', 'BAC\CategoryController@show')->name('show-category');
//     Route::post('/update-category', 'BAC\CategoryController@update')->name('update-category');

//     //Items
//     Route::get('/items', 'BAC\ItemController@additems');
//     Route::post('/add-item', 'BAC\ItemController@store')->name('add-item');
//     Route::post('/delete-item', 'BAC\ItemController@delete')->name('delete-item');
//     Route::post('/show-item', 'BAC\ItemController@show')->name('show-item');
//     Route::post('/update-item', 'BAC\ItemController@update')->name('update-item');

//     //Unit of Measurement
//     Route::get('/unit-of-measurement', 'BAC\UnitofMeasurementController@index');
//     Route::post('/add-unit', 'BAC\UnitofMeasurementController@store')->name('add-unit');
//     Route::post('/delete-unit', 'BAC\UnitofMeasurementController@delete')->name('delete-unit');
//     Route::post('/show-unit', 'BAC\UnitofMeasurementController@show')->name('show-unit');
//     Route::post('/update-unit', 'BAC\UnitofMeasurementController@update')->name('update-unit');

//     //Mode of Procurement
//     Route::get('/mode-of-procurement', 'BAC\ModeofProcurementController@index');
//     Route::post('/add-procurement', 'BAC\ModeofProcurementController@store')->name('add-procurement');
//     Route::post('/delete-procurement', 'BAC\ModeofProcurementController@delete')->name('delete-procurement');
//     Route::post('/show-procurement', 'BAC\ModeofProcurementController@show')->name('show-procurement');
//     Route::post('/update-procurement', 'BAC\ModeofProcurementController@update')->name('update-procurement');

//     //Approved PPMP
//     Route::get('/approved-ppmp', 'BAC\ApprovedPPMPController@index');
//     Route::post('/show-approved', 'BAC\ApprovedPPMPController@show');
    
//     //Approved Supplemental
//     Route::get('/approved-supplemental', 'BAC\ApprovedSupplementalController@index');
//     Route::post('/show-supplemental', 'BAC\ApprovedSupplementalController@show');
    
//     //New PPMP Request
//     Route::get('/request-new-ppmp', 'BAC\NewPPMPRequestController@index');
//     // Route::post('/show-approved', 'BAC\ApprovedPPMPController@show');

    
//     //generate APP - NONCSE
//     Route::get('/app-non-cse', 'BAC\APPNONCSEController@index');
//     Route::get('/try', 'BAC\APPNONCSEController@try');
//     Route::post('/app-non-cse-generate', 'BAC\APPNONCSEController@generatepdf')->name('app-non-cse-generate');
//     Route::get('/app-non-cse-generate-excel', 'BAC\APPNONCSEController@generateexcel')->name('app-non-cse-generate-excel');
//     Route::post('/show-signatories', 'BAC\APPNONCSEController@show_signatories')->name('show-signatories');
//     Route::post('/add-preparedby', 'BAC\APPNONCSEController@add_preparedby')->name('add-preparedby');
//     Route::post('/add-approvedby', 'BAC\APPNONCSEController@add_approvedby')->name('add-approvedby');
//     Route::post('/add-recommendingapproval', 'BAC\APPNONCSEController@add_recommendingapproval')->name('add-recommendingapproval');
//     Route::post('/update-signatories', 'BAC\APPNONCSEController@update_signatories')->name('update-signatories');
//     Route::get('/app-non-cse-year', 'BAC\APPNONCSEController@app_non_cse_year')->name('app-non-cse-year');
    
//     Route::post('/show-campusinfo', 'BAC\APPNONCSEController@show_campusinfo')->name('show-campusinfo');
//     Route::post('/update-campusinfo', 'BAC\APPNONCSEController@update_campusinfo')->name('update-campusinfo');
//     // Route::post('/show-campuslogo', 'BAC\APPNONCSEController@show_campuslogo')->name('show-campusinfo');
//     Route::post('/update-campuslogo', 'BAC\APPNONCSEController@update_campuslogo')->name('update-campuslogo');
//     Route::post('/update-logo', 'BAC\APPNONCSEController@update_logo')->name('update-logo');

    
//     Route::group(['prefix' => 'supervisor','middleware' => ['authuser']], function() {
//         //Supervisor Side
//         Route::get('/', 'SupervisorController@index');
//         Route::post('/show-ppmp', 'SupervisorController@show')->name('show-ppmp');
//         Route::post('/supervisor-ppmp-approved', 'SupervisorController@status')->name('supervisor-ppmp-approved');
//         Route::post('/supervisor-ppmp-disapproved', 'SupervisorController@status')->name('supervisor-ppmp-disapproved');
//     });
    
//     //Ammendments
//     Route::get('/request-for-amendments', 'BAC\RequestforAmendmentsController@index');
//     // Route::post('/app-non-cse-view', 'BAC\APPNONCSEController@view')->name('app-non-cse-view');
//     //jrald end
//     // Route::get('/adduser','Admin\AdminController@adduser');
    
//     //jerald
//     // Route::get('/items', 'BAC\ItemController@additems');
//     // Route::get('/unit-of-measurement', 'BAC\UnitofMeasurementController@additems');
//     // Route::get('/mode-of-procurement', 'BAC\ModeofProcurementController@additems');
//     //jrald end
//     // Route::get('/adduser','Admin\AdminController@adduser');

//      /* Torrexx Additionals */
//         /** Implementing Functions Related to the department end user */
//             # this will show dashboard for end user role
//             Route::get('/announcements', [DepartmentPagesController::class, 'showAnnouncementPage'])->name('department-dashboard');
//             # this will show the create project title 
//             Route::get('/project-category', [DepartmentPagesController::class, 'showProjectCategory'])->name('derpartment-project-category');

//             # this will create the PPMP or the project title
//             Route::get('/createPPMP', [DepartmentPagesController::class, 'showCreatePPMP'])->name('department-showCreatetPPMP');
//             # this will submit created project title by the end user or department roloe= 4 
//             Route::post('/createPPMP', [DepartmentController::class, 'createProjectTitle'])->name('department-createProjectTitle');
//             # this will get the project title based on id
//             Route::get('/get-project-title', [DepartmentController::class, 'getProjectTitle'])->name('department-get-project');
//             # this will delete project title 
//             Route::get('/destroy-project-title', [DepartmentController::class, 'destoryProjectTitle'])->name('department-destroy-project');
//             # this will update project title
//             Route::post('/update-project-title', [DepartmentController::class, 'updateProjectTitle'])->name('department-update-project');

//             # this will show add item page based on the clicked project title id
//             Route::get('/addItem/projectid', [DepartmentPagesController::class, 'showAddItem'])->name('department-addItem');
//             # this will create items on the items table
//             Route::post('/create-ppmps', [DepartmentController::class, 'createPPMPs'])->name('department-create-ppmps');
//             # this will fetch all item description based on the given item name
//             Route::post('/fetch/item-description', [DepartmentController::class, 'getItemDescription'])->name('department-item-description');
//             # this will fetch all the item category based item name
//             Route::post('/fetch/item-category', [DepartmentController::class, 'getItemCategory'])->name('department-item-category');
//             # this will update item details on ppmps table
//             Route::post('/update/item-details', [DepartmentController::class, 'updatePPMPS'])->name('department-update-item');
//             # deleting data from ppmps table
//             Route::post('/delete/item-details', [DepartmentController::class, 'deletePPMPS'])->name('department-delete-item');
//             # this will submit ppmp on the 
//             Route::post('/sumbit/ppmp', [DepartmentController::class, 'submitPPMP'])->name('department-submit-ppmp');

//             # this will show the my ppmp page based on the department id
//             Route::get('/myPPMP', [DepartmentPagesController::class, 'showMyPPMP'])->name('department-showMyPPMP');
//             # this will show the status of the project along with its item
//             Route::post('/project/status', [DepartmentPagesController::class, 'showProjectStatus'])->name('department-showProjectStatus');
//         /** Gettin data from the database */
//             # this will get the databa by unit of measure per item fron the unit measure from the dabase
//             Route::post('/UnitOfMeasure', 'Department\UnitOfMeasurementController@show')->name('UnitOfMeasure');
//             # get data all project
//             Route::post('/getAllProject', [ProjectsController::class, 'show'])->name('department-get-projects');
//             # this will get the project title by year created
//             Route::post('/get-by-year-created', [DepartmentPagesController::class, 'show_by_year_created'])->name('department-year-created');

//             Route::get('/pending_ppmps','BudgetOfficer\BudgetOfficerController@PPMPindex');
//             Route::post('/view_ppmp/showPPMP', 'BudgetOfficer\BudgetOfficerController@showPPMP')->name('showPPMP');
//             Route::post('/view_ppmp/showPPMP/ppmp-approved', 'BudgetOfficer\BudgetOfficerController@status')->name('ppmp-approved');
//             Route::post('/view_ppmp/showPPMP/ppmp-disapproved', 'BudgetOfficer\BudgetOfficerController@status')->name('ppmp-disapproved');

// });
#End Route for SuperAdmin

Route::group(['prefix' => 'admin','middleware' => ['authuser']], function() {
    Route::get('/users', 'Admin\AdminController@index');
    Route::post('/save', 'Admin\AdminController@store')->name('save');
    Route::post('/delete', 'Admin\AdminController@delete')->name('delete');
    Route::post('/edit', 'Admin\AdminController@edit')->name('edit');
    Route::post('/update', 'Admin\AdminController@update')->name('update');
});
//Jerald 
Route::post('/add-item', 'BAC\ItemController@store');
Route::get('/delete-item/{id}', 'BAC\ItemController@delete');

#Romar
#Start Route for Budget Officer
// Route::group(['prefix' => 'budgetofficer','middleware' => ['authuser']], function() {
//     Route::get('/try','BudgetOfficer\BudgetOfficerController@try');

//     Route::get('/ppmp_deadline','BudgetOfficer\BudgetOfficerController@index');
//     Route::post('/ppmp_deadline','BudgetOfficer\BudgetOfficerController@store')->name('ppmp_deadline');
//     Route::post('/edit_deadline', 'BudgetOfficer\BudgetOfficerController@edit')->name('edit_deadline');
//     Route::post('/update_deadline', 'BudgetOfficer\BudgetOfficerController@update')->name('update_deadline');
//     Route::post('/delete_deadline', 'BudgetOfficer\BudgetOfficerController@delete')->name('delete_deadline');

//     Route::get('/mandatory_expenditures','BudgetOfficer\BudgetOfficerController@mandatory_expenditures');
//     Route::post('/addMandatoryExpenditure','BudgetOfficer\BudgetOfficerController@addMandatoryExpenditure')->name('addMandatoryExpenditure');
//     Route::post('/editMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@editMandatoryExpenditure')->name('editMandatoryExpenditure');
//     Route::post('/updateMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@updateMandatoryExpenditure')->name('updateMandatoryExpenditure');
//     Route::post('/deleteMandatoryExpenditure', 'BudgetOfficer\BudgetOfficerController@deleteMandatoryExpenditure')->name('deleteMandatoryExpenditure');

//     Route::get('/allocate_budget','BudgetOfficer\AllocateBudgetController@index');
//     // Route::get('/allocate_budget1','BudgetOfficer\AllocateBudgetController@index1');
//     Route::post('/allocate_budget','BudgetOfficer\AllocateBudgetController@allocate_budget')->name('allocate_budget');
//     Route::post('/delete', 'BudgetOfficer\AllocateBudgetController@delete')->name('delete');
//     Route::post('/edit', 'BudgetOfficer\AllocateBudgetController@edit')->name('edit');
//     Route::post('/updateAllocatedBudget', 'BudgetOfficer\AllocateBudgetController@updateAllocatedBudget')->name('updateAllocatedBudget');
//     Route::get('/getDepartments', 'BudgetOfficer\AllocateBudgetController@getDepartments')->name('getDepartments');
//     Route::get('/getFundSources', 'BudgetOfficer\AllocateBudgetController@getFundSources')->name('getFundSources');
//     Route::get('/getMandatoryExpenditures', 'BudgetOfficer\AllocateBudgetController@getMandatoryExpenditures')->name('getMandatoryExpenditures');
//     Route::get('/getYears', 'BudgetOfficer\AllocateBudgetController@get_years')->name('getYears');
//     Route::get('/get_DeadlineByYear', 'BudgetOfficer\AllocateBudgetController@get_DeadlineByYear')->name('get_DeadlineByYear');

//     Route::get('/view_ppmp','BudgetOfficer\BudgetOfficerController@PPMPindex');
//     Route::post('/view_ppmp/showPPMP', 'BudgetOfficer\BudgetOfficerController@showPPMP')->name('showPPMP');
//     Route::post('/view_ppmp/showPPMP/ppmp-approved', 'BudgetOfficer\BudgetOfficerController@status')->name('ppmp-approved');
//     Route::post('/view_ppmp/showPPMP/ppmp-disapproved', 'BudgetOfficer\BudgetOfficerController@status')->name('ppmp-disapproved');
//     Route::post('/view_ppmp/showPPMP/ppmp-timeline', 'BudgetOfficer\BudgetOfficerController@timeline')->name('ppmp-timeline');

//     Route::get('/view_signed_ppmp','BudgetOfficer\ViewSignedPPMPController@index');
// });

Route::group(['prefix' => 'budgetofficer','middleware' => ['authuser']], function() {
    Route::get('/try','BudgetOfficer\BudgetOfficerController@try');

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
    Route::post('/delete', 'BudgetOfficer\AllocateBudgetController@delete')->name('delete');
    Route::post('/edit_allocated_budget', 'BudgetOfficer\BudgetOfficerController@edit_allocated_budget')->name('edit_allocated_budget');
    Route::post('/updateAllocatedBudget', 'BudgetOfficer\AllocateBudgetController@updateAllocatedBudget')->name('updateAllocatedBudget');

    Route::get('/getDepartments', 'BudgetOfficer\BudgetOfficerController@getDepartments')->name('getDepartments');
    Route::get('/getFundSources', 'BudgetOfficer\BudgetOfficerController@getFundSources')->name('getFundSources');
    Route::get('/getMandatoryExpenditures', 'BudgetOfficer\BudgetOfficerController@getMandatoryExpenditures')->name('getMandatoryExpenditures');
    Route::get('/getYears', 'BudgetOfficer\BudgetOfficerController@get_years')->name('getYears');
    Route::get('/get_DeadlineByYear', 'BudgetOfficer\AllocateBudgetController@get_DeadlineByYear')->name('get_DeadlineByYear');

    Route::get('/view_ppmp','BudgetOfficer\BudgetOfficerController@PPMPindex');
    Route::post('/view_ppmp/showPPMP', 'BudgetOfficer\BudgetOfficerController@showPPMP')->name('showPPMP');
    Route::post('/view_ppmp/showPPMP/ppmp-approved', 'BudgetOfficer\BudgetOfficerController@status')->name('ppmp-approved');
    Route::post('/view_ppmp/showPPMP/ppmp-disapproved', 'BudgetOfficer\BudgetOfficerController@status')->name('ppmp-disapproved');
    Route::post('/view_ppmp/showPPMP/ppmp-timeline', 'BudgetOfficer\BudgetOfficerController@timeline')->name('ppmp-timeline');

    Route::get('/view_signed_ppmp','BudgetOfficer\ViewSignedPPMPController@index');
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
    Route::get('/show-all', 'BAC\APPNONCSEController@university_wide')->name('show-all');
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

    // Route::post('/supervisor-ppmp-done', 'SupervisorController@done')->name('supervisor-ppmp-done');

    
    Route::group(['prefix' => 'supervisor','middleware' => ['authuser']], function() {
        //Supervisor Side
        Route::get('/', 'SupervisorController@index');
        Route::post('/show-ppmp', 'SupervisorController@show')->name('show-ppmp');
        Route::post('/supervisor-ppmp-approved', 'SupervisorController@status')->name('supervisor-ppmp-approved');
        Route::post('/supervisor-ppmp-disapproved', 'SupervisorController@status')->name('supervisor-ppmp-disapproved');
        Route::post('/supervisor-ppmp-done', 'SupervisorController@done')->name('supervisor-ppmp-done');
    });
    
    //Ammendments
    Route::get('/request-for-amendments', 'BAC\RequestforAmendmentsController@index');
    // Route::post('/app-non-cse-view', 'BAC\APPNONCSEController@view')->name('app-non-cse-view');
    //jrald end
    // Route::get('/adduser','Admin\AdminController@adduser');
    
});

Route::group(['prefix' => 'supply_custodian','middleware' => ['authuser']], function() {

    Route::get('/property','SupplyCustodian\PropertyController@index');
    Route::post('/assign-par','SupplyCustodian\PropertyController@assign_par')->name('assign-par');
    Route::post('/finalize-par','SupplyCustodian\PropertyController@finalize_par')->name('finalize-par');
    Route::post('/delete-par','SupplyCustodian\PropertyController@delete_par')->name('delete-par');
    Route::post('/edit-par','SupplyCustodian\PropertyController@edit_par')->name('edit-par');
    Route::post('/finaldelete-par','SupplyCustodian\PropertyController@finaldelete_par')->name('finaldelete-par');
    Route::post('/submitdelete-par','SupplyCustodian\PropertyController@submitdelete_par')->name('submitdelete-par');
    Route::post('/submitEdit-par','SupplyCustodian\PropertyController@submitEdit_par')->name('submitEdit-par');
    Route::post('/submitadd-par','SupplyCustodian\PropertyController@submitadd_par')->name('submitadd-par');
    Route::post('/additem-par','SupplyCustodian\PropertyController@additem_par')->name('additem-par');

    Route::get('/ics','SupplyCustodian\ICSController@index');
    Route::post('/assign-ics','SupplyCustodian\icsController@assign_ics')->name('assign-ics');
    Route::post('/finalize-ics','SupplyCustodian\icsController@finalize_ics')->name('finalize-ics');
    Route::post('/delete-ics','SupplyCustodian\icsController@delete_ics')->name('delete-ics');
    Route::post('/edit-ics','SupplyCustodian\icsController@edit_ics')->name('edit-ics');
    Route::post('/submitEdit-ics','SupplyCustodian\icsController@submitEdit_ics')->name('submitEdit-ics');
    Route::post('/submitadd-ics','SupplyCustodian\icsController@submitadd_ics')->name('submitadd-ics');
    Route::post('/additem-ics','SupplyCustodian\icsController@additem_ics')->name('additem-ics');


    Route::get('/supplier','SupplyCustodian\SupplierController@index');
    Route::post('/add-supplier','SupplyCustodian\SupplierController@add_supplier')->name('add-supplier');
    Route::post('/edit-supplier','SupplyCustodian\SupplierController@edit_supplier')->name('edit-supplier');
    Route::post('/submitedit-supplier','SupplyCustodian\SupplierController@submitedit_supplier')->name('submitedit-supplier');
    Route::post('/delete-supplier','SupplyCustodian\SupplierController@delete_supplier')->name('delete-supplier');
// });

});
#sdfghsh

//j-rald
# route for department-group
Route::group(['prefix' => 'department','middleware' => ['authuser']], function() {
    
    //MY PROPERTY MENU ROUTES
    Route::get('/my_par','Employee\EmployeeController@my_par');
    Route::get('/my_ics','Employee\EmployeeController@my_ics');

    //PURCHASE REQUEST ROUTES
    Route::get('/purchaseRequest', 'Department\PurchaseRequestController@PurchaseRequestIndex');
    Route::post('/purchaseRequest/add_Items_To_PR', 'Department\PurchaseRequestController@add_Items_To_PR');
    Route::get('/purchaseRequest/createPR', 'Department\PurchaseRequestController@createPR')->name('createPR');
    Route::get('/purchaseRequest/getEmployees', 'Department\PurchaseRequestController@getEmployees');
    Route::post('/purchaseRequest/savePR', 'Department\PurchaseRequestController@savePR');

    Route::get('/trackPR', 'Department\PurchaseRequestController@TrackPRIndex');
    // Route::get('/trackPR/viewPR', 'Department\PurchaseRequestController@viewPR')->name('viewPR');

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

            /** Submit revision of PPMP */            
                # this will re-submit ppmp | revised PPMP
                Route::post('/re-sumbit/ppmp', [DepartmentController::class, 'resubmitPPMP'])->name('department-re_submit-ppmp');
            /** END */
            
             /** Disapproved PPMP | Project */
                /** KEY to REMEMBER
                 * This will also apply to all project category | INDICATIVE, PPMP, SUPPLEMENTAL 
                 * */
                
                # this route will display the disapproved items
                Route::get('/disapproved-items', [DepartmentPagesController::class, 'show_disapproved_items'])->name('dept_disapproved-items');
            /** END */

        /** Gettin data from the database */
            # this will get the databa by unit of measure per item fron the unit measure from the dabase
            Route::post('/UnitOfMeasure', 'Department\UnitOfMeasurementController@show')->name('UnitOfMeasure');
            # get data all project
            Route::post('/getAllProject', [ProjectsController::class, 'show'])->name('department-get-projects');
});

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

