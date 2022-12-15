<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

use DB;
class PurchaseRequestController extends Controller
{
  protected $aes;
        public function __construct(){
            $this->aes = new AESCipher();
        }

  public function PurchaseRequestIndex(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Approve PPMPs"]
    ];
    $year = Carbon::now()->format('Y');
    $department_id = session('department_id');
    $ppmps = DB::table("project_titles")
                ->select('project_titles.id','project_titles.project_title','project_titles.project_year','fund_sources.fund_source')
                ->join('fund_sources', 'fund_sources.id', '=', 'project_titles.fund_source')
                ->where('department_id',$department_id)
                ->where('project_year',$year+1)
                ->where('status',4)
                ->get();

      return view('pages.department.purchase-request-index',compact('ppmps'), [
          'pageConfigs'=>$pageConfigs,
          'breadcrumbs'=>$breadcrumbs,
      ]); 
  }

  public function TrackPRIndex(){
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["name" => "Track PR"]
      ];
      // return view('pages.budgetofficer.allocatebudget',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);
  
      $department_id = session('department_id');
      $pr = DB::table("purchase_request as pr")
            ->select("pr.*","fs.fund_source","u.name","d.department_name")
            ->join("departments as d", "pr.department_id", "=", "d.id")
            ->join("fund_sources as fs", "pr.fund_source_id", "=", "fs.id")
            ->join("users as u", "pr.printed_name", "=", "u.id")
            ->where("pr.department_id", $department_id)
            ->get();
            // dd($pr);

          return view('pages.department.track-pr-index',compact('pr'), [
              'pageConfigs'=>$pageConfigs,
              'breadcrumbs'=>$breadcrumbs,
              // 'error' => $error,
          ]); 
  }

  public function viewPR(Request $request) {

      // dd($request->all());
      try {
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],
            ["link" => "/department/trackPR", "name" => "Purchase Request"],
            // ["link" => "/department/purchaseRequest/createPR", "name" => "Create PR"],
            ["name" => "View PR"]
        ];
      $id = $this->aes->decrypt($request->id);
      // dd($id);

        $details = DB::table("project_titles as pt")
        ->select("pt.campus","pt.project_title","pt.fund_source","d.department_name")
        ->join("departments as d", "pt.department_id", "=", "d.id")
        ->join("fund_sources as fs", "pt.fund_source", "=", "fs.id")
        ->where("pt.id", $id)
        ->get();

        return view('pages.department.view-pr',
        ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
        # this will attache the data to view
        [
          //   'id' => $id,
          //   'ProjectTitleResponse'    => $ProjectTitleResponse['data'],
          //   'items' =>  $items['data'],
          //   'mode_of_procurements'  =>  $mode_of_procurements['data'],
          //   'unit_of_measurements'  =>  $unit_of_measurement['data'],
          //   'ppmp_response' => $ppmp_response['data'],
          //  'allocated_budgets' => $allocated_budgets['data']
        ]
        );
     } catch (\Throwable $th) {
         throw $th;
     }
  }

  public function createPR(Request $request) {
      try {
        $date = Carbon::now()->format('Y-m-d');
        $project_code = $request->id;
        $id = $this->aes->decrypt($request->id);
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],
            ["link" => "/department/purchaseRequest", "name" => "Approve PPMPs"],
            ["name" => "Create PR"]
        ];
       
          // dd($id);
          // $campusinfo = DB::table("campusinfo")
          // ->where("campus", 1)
          // ->get();
          
          // $items =  Http::withToken(session('token'))->get(env('APP_API'). "/api/purchaseRequest/getItems",[
          //           'department_id' => session('department_id'),
          //           'id' => $id,
          //           ])->json();
                    // dd($items);

          $items = DB::table("ppmps")
                    ->select('id','item_name','item_description','quantity','unit_price','estimated_price','mode_of_procurement')
                    ->where('department_id',session('department_id'))
                    ->where('project_code',$id)
                    ->where('for_pr',0)
                    ->where('mode_of_procurement',"!=","Public Bidding")
                    ->get();
          $itemsForPR = DB::table("ppmps")
                    ->select('id','item_name','item_description','quantity','unit_price','estimated_price','mode_of_procurement')
                    ->where('department_id',session('department_id'))
                    ->where('project_code',$id)
                    ->where('for_pr',1)
                    ->where('mode_of_procurement',"!=","Public Bidding")
                    ->get();

          $ppmps = DB::table("ppmps as p")
                    ->select("pt.project_title", "d.department_name", "p.*","fs.fund_source","pt.project_code as ProjectCode")
                    ->join("project_titles as pt", "p.project_code", "=", "pt.id")
                    ->join("fund_sources as fs", "pt.fund_source", "=", "fs.id")
                    ->join("departments as d", "pt.department_id", "=", "d.id")
                    ->where("p.project_code", $id)
                    ->where("p.for_PR", 1)
                    // ->where("p.app_type", 'Non-CSE')
                    ->where("p.mode_of_procurement", "!=", "Public Bidding")
                    ->whereNull("p.deleted_at")
                    ->where("p.is_supplemental", "=", 0)
                    ->where("p.status", "=", 4)
                    ->orderBy("p.department_id", "ASC")
                    ->orderBy("p.project_code", "ASC")
                    ->get();
          $details = DB::table("project_titles as pt")
                    ->select("pt.campus","pt.project_title","pt.fund_source","d.department_name")
                    ->join("departments as d", "pt.department_id", "=", "d.id")
                    ->join("fund_sources as fs", "pt.fund_source", "=", "fs.id")
                    ->where("pt.id", $id)
                    ->get();
          $fund_source = DB::table("project_titles as pt")
                    ->select("fs.fund_source")
                    ->join("fund_sources as fs", "pt.fund_source", "=", "fs.id")
                    ->where("pt.id", $id)
                    ->get();
                    // dd($details);
          // $error="";
          // if($items['status']==400){
          //     $error=$items['message'];
          // }
        //   if(count($ppmps) == 0){
        //     $error=$items['message'];
        // }
          return view('pages.department.create-purchase-request',compact('items','itemsForPR','ppmps','date','details','fund_source','project_code'),
              ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], 
              # this will attache the data to view
              [
                //   'id' => $id,
                //   'ProjectTitleResponse'    => $ProjectTitleResponse['data'],
                //   'items' =>  $items['data'],
                //   'mode_of_procurements'  =>  $mode_of_procurements['data'],
                //   'unit_of_measurements'  =>  $unit_of_measurement['data'],
                //   'ppmp_response' => $ppmp_response['data'],
                //  'allocated_budgets' => $allocated_budgets['data']
              ]
              );
          # end
     } catch (\Throwable $th) {
         throw $th;
     }
  }

  public function add_Items_To_PR(Request $request){
    try {
      // dd($request->all());  
      $itemsArray = $request->items;
      for ($i = 0; $i < count($itemsArray); $i++) {
         $decryptedItem[$i] = $this->aes->decrypt($itemsArray[$i]);
        //  $itemsArrayToString.array_push($decryptedItem);
        $item = DB::update(
          'update ppmps set for_pr = 1 where id = ?',[$decryptedItem[$i]]
        );
      }
      // dd($item);

      if($item){
        return response()->json([
            'status' => 200,
            'message' => 'Items Added Successfully!',
        ]);
      }
      return response()->json([
          'status' => 400,
          'message' => 'Error'
      ]);
      // dd($item);

      // $itemsArrayToString = implode(',',$decryptedItem);
      // dd($itemsArrayToString);
    } catch (\Throwable $th) {
      dd('add_Items_To_PR FUNCTION '+$th);
    }
  }

  public function getEmployees(Request $request){
    try {
      // dd($request->all());  
      $department_id = session('department_id');
      $employees = DB::table('users')
                    ->select('users.id','users.name')
                    ->where('department_id','=',$department_id)
                    ->get();
      // dd($employees);  
      if($employees){
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $employees,
        ]);
      }
      return response()->json([
          'status' => 400,
          'message' => 'Error'
      ]);
    } catch (\Throwable $th) {
      dd('add_Items_To_PR FUNCTION '+$th);
    }
  }

  public function savePR(Request $request){
    try {
      // dd($request->all());  
      $current = Carbon::now();
      $department_id = session('department_id');
      $campus = session('campus');
      $employee = $request->employee;
      $purpose = $request->purpose;
      $designation = $request->designation;
      $fund_source = (new AESCipher)->decrypt($request->fund_source);
      $project_code = (new AESCipher)->decrypt($request->project_code);
      $date = $current->format('Y-m');
      // dd($date->format('Y-m'));  
      // $items = DB::update(
      //   'update ppmps set for_pr = 2 where project_code = ?, for_pr = ? ',[$project_code,1]
      // );

      // dd($items);  
      $purchaseRequestCount = DB::table('purchase_request as pr')
                              ->select('pr.*')->get();
      $purchaseRequestCounts =  str_pad(count($purchaseRequestCount)+1,4,"0",STR_PAD_LEFT);
      $pr_no = $date.'-'.$purchaseRequestCounts;

      $purchaseRequest = DB::insert('insert into purchase_request 
                        (department_id,pr_no,campus,fund_source_id,purpose,printed_name,designation,created_at)  values (?,?,?,?,?,?,?,?)',
                        [$department_id,$pr_no,$campus,$fund_source,$purpose,$employee,$designation,$current]);
      
      DB::table('ppmps')
                  ->where('project_code', $project_code)
                  ->where('for_pr', 1)
                  ->update(['for_pr' => 2]
                );
      DB::table('ppmps')
                ->where('project_code', $project_code)
                ->where('pr_no', 0)
                ->update(['pr_no' => $pr_no]
              );        
      // dd($purchaseRequest);  
      if($purchaseRequest){
        return response()->json([
            'status' => 200,
            'message' => 'Success',
        ]);
      }
      return response()->json([
          'status' => 400,
          'message' => 'Error'
      ]);
    } catch (\Throwable $th) {
      dd('add_Items_To_PR FUNCTION '+$th);
    }
  }

  public function view_status(Request $request){
    // dd($request->all());
    // dd($id);
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],
      ["link" => "/department/trackPR", "name" => "Track PR"],
      // ["link" => "/department/purchaseRequest/createPR", "name" => "Create PR"],
      ["name" => "View Status"]
    ];

    $id = (new AESCipher())->decrypt($request->id);

    return view('pages.department.view_status_page', [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]); 
  }

  public function view_pr(Request $request){
    // dd($request->all());
    // dd($id);
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],
      ["link" => "/department/trackPR", "name" => "Track PR"],
      // ["link" => "/department/purchaseRequest/createPR", "name" => "Create PR"],
      ["name" => "View PR"]
    ];

    $id = (new AESCipher())->decrypt($request->id);
    $date = Carbon::now()->format('Y-m-d');

    $purchase_request = DB::table("purchase_request as pr")
                          ->select("pr.*","fs.fund_source","d.department_name","u.name")
                          ->join("fund_sources as fs","pr.fund_source_id","fs.id")
                          ->join("departments as d","pr.department_id","d.id")
                          ->join("users as u","pr.printed_name","u.id")
                          ->where("pr.id",$id)
                          ->get();
                    // dd($purchase_request);
    $itemsForPR = DB::table("ppmps as p")
              ->select('p.*')
              ->join("purchase_request as pr", "p.pr_no", "=", "pr.pr_no")
              ->where('p.department_id',session('department_id'))
              ->where('pr.id',$id)
              ->where('p.for_pr',2)
              // ->where('mode_of_procurement',"!=","Public Bidding")
              ->get();
              // dd($itemsForPR);

          

    return view('pages.department.view_pr_page',compact('purchase_request','itemsForPR','date'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]); 
  }
}
