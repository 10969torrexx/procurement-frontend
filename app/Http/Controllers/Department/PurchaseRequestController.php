<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use Illuminate\Support\Facades\Session;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Http\Controllers\HistoryLogController;

use Illuminate\Support\Facades\DB;
class PurchaseRequestController extends Controller
{
  protected $aes;
        public function __construct(){
            $this->aes = new AESCipher();
        }

  public function PurchaseRequestIndex(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Approved PPMPs"]
    ];
    $year = Carbon::now()->format('Y');
    $department_id = session('department_id');
    $campus = session('campus');
    if(session('role') != null){
      $ppmps = DB::table("project_titles")
                ->select('project_titles.id','project_titles.project_title','project_titles.project_year','fund_sources.fund_source')
                ->join('fund_sources', 'fund_sources.id', '=', 'project_titles.fund_source')
                ->where('department_id',$department_id)
                ->where('campus',$campus)
                // ->where('project_year',$year)
                ->where('status',4)
                ->orderBy('project_year','DESC')
                ->get();
    }else{
      $ppmps = DB::table("project_titles")
              ->select('project_titles.id','project_titles.project_title','project_titles.project_year','fund_sources.fund_source')
              ->join('fund_sources', 'fund_sources.id', '=', 'project_titles.fund_source')
              // ->where('project_year',$year)
              ->where('status',4)
              ->orderBy('project_year','DESC')
              ->get();
    }
    
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
      $hope = DB::table('users')
      ->where('role',12)
      ->where('campus',session('campus'))
      ->get();
      // dd($users);
      $department_id = session('department_id');
      $campus = session('campus');
      if(session('role') != null){
      $pr = DB::table("purchase_request as pr")
            ->select("pr.*","fs.fund_source","u.name","d.department_name")
            ->join("departments as d", "pr.department_id", "=", "d.id")
            ->join("fund_sources as fs", "pr.fund_source_id", "=", "fs.id")
            ->join("users as u", "pr.printed_name", "=", "u.id")
            ->where("pr.department_id", $department_id)
            ->where("pr.campus", $campus)
            ->whereNull("pr.deleted_at")
            ->orderBy('pr.created_at')
            ->get();
            // dd($pr);
      }else{
        $pr = DB::table("purchase_request as pr")
                ->select("pr.*","fs.fund_source","u.name","d.department_name")
                ->join("departments as d", "pr.department_id", "=", "d.id")
                ->join("fund_sources as fs", "pr.fund_source_id", "=", "fs.id")
                ->join("users as u", "pr.printed_name", "=", "u.id")
                ->whereNull("pr.deleted_at")
                ->orderBy('pr.created_at')
                ->get();
      }
          return view('pages.department.track-pr-index',compact('pr','hope'), [
              'pageConfigs'=>$pageConfigs,
              'breadcrumbs'=>$breadcrumbs,
              // 'error' => $error,
          ]); 
  }

  public function RoutingSlipIndex(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Approved PRs "]
    ];
    // return view('pages.budgetofficer.allocatebudget',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);
    $hope = DB::table('users')
    ->where('role',12)
    ->where('campus',session('campus'))
    ->get();
    // dd($users);
    $department_id = session('department_id');
    if(session('role') != null){
      $pr = DB::table("purchase_request as pr")
      ->select("pr.*","fs.fund_source","u.name","d.department_name")
      ->join("departments as d", "pr.department_id", "=", "d.id")
      ->join("fund_sources as fs", "pr.fund_source_id", "=", "fs.id")
      ->join("users as u", "pr.printed_name", "=", "u.id")
      ->where("pr.department_id", $department_id)
      ->where("pr.status", 2)
      ->whereNull("pr.deleted_at")
      ->orderBy('pr.created_at')
      ->get();
    }else{
      $pr = DB::table("purchase_request as pr")
      ->select("pr.*","fs.fund_source","u.name","d.department_name")
      ->join("departments as d", "pr.department_id", "=", "d.id")
      ->join("fund_sources as fs", "pr.fund_source_id", "=", "fs.id")
      ->join("users as u", "pr.printed_name", "=", "u.id")
      ->where("pr.status", 2)
      ->whereNull("pr.deleted_at")
      ->orderBy('pr.created_at')
      ->get();
    }
   
          // dd($pr);

        return view('pages.department.pr-routing-slip-index',compact('pr','hope'), [
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
            // 'error' => $error,
        ]); 
}

  public function SignedPRIndex(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Signed PR"]
    ];
    if(session('role') != null){
    $response = DB::table("signed_purchase_request as spr")
          ->select('spr.*','u.name')
          ->join('users as u','spr.employee_id','u.employee_id')
          ->where("spr.department_id", session('department_id'))
          ->where("spr.campus", session('campus'))
          ->whereNull("spr.deleted_at")
          ->get();
          // dd($response);
    }else{
      $response = DB::table("signed_purchase_request as spr")
                    ->select('spr.*','u.name')
                    ->join('users as u','spr.employee_id','u.employee_id')
                    ->whereNull("spr.deleted_at")
                    ->get();
    }
        return view('pages.department.signed-pr-index',compact('response'), [
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
        // dd($this->aes->decrypt($request->id));
        $date = Carbon::now()->format('m/d/Y');
        $project_code = $request->id;
        $pr_no = "0000-00-0000";
        $id = $this->aes->decrypt($request->id);
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],
            ["link" => "/PR/purchaseRequest", "name" => "Approve PPMPs"],
            ["name" => "Create PR"]
        ];
          $hope = DB::table('users')
                    ->where('role',12)
                    ->where('campus',session('campus'))
                    ->get();
          foreach($hope as $data){
            $hope = $data->name;
          }
          $itemsForPR = DB::table("purchase_request_items as pri")
                    ->select('pri.*','p.item_name','p.item_description','p.unit_price')
                    ->join("ppmps as p", "pri.item_id", "=", "p.id")
                    // ->where('p.app_type','Non-CSE')
                    ->where('pri.project_code',$id)
                    ->where('pri.pr_no',0)
                    ->whereNull('pri.deleted_at')
                    ->get();
          $itemsForPRCount = count($itemsForPR);
          $itemsFromPRI = DB::table("purchase_request_items as pri")
                    ->select('pri.*','p.unit_of_measurement','p.item_description','p.unit_price')
                    ->join("ppmps as p", "pri.item_id", "=", "p.id")
                    ->where('p.app_type','Non-CSE')
                    ->where('pri.project_code',$id)
                    ->where('pri.pr_no',0)
                    ->whereNull('pri.deleted_at')
                    ->get();

          $details = DB::table("project_titles as pt")
                    ->select("pt.campus","pt.project_title","pt.fund_source","d.department_name")
                    ->join("departments as d", "pt.department_id", "=", "d.id")
                    ->join("fund_sources as fs", "pt.fund_source", "=", "fs.id")
                    ->where("pt.id", $id)
                    ->whereNull('pt.deleted_at')
                    ->get();
          $fund_source = DB::table("project_titles as pt")
                    ->select("fs.fund_source")
                    ->join("fund_sources as fs", "pt.fund_source", "=", "fs.id")
                    ->where("pt.id", $id)
                    ->whereNull('pt.deleted_at')
                    ->get();  

          $items = DB::table('ppmps')
                    // ->select('id','item_name','quantity')
                    ->where('project_code','=',$id)
                    ->where('mode_of_procurement','!=',33)
                    ->where('app_type','Non-CSE')
                    ->orderBy('item_name')
                    ->whereNull('deleted_at')
                    ->sum('quantity');

          $pri = DB::table('purchase_request_items')
                    // ->select('item_id','quantity')
                    ->where('project_code','=',$id)
                    // ->where('quantity','!=',0)
                    ->whereNull('deleted_at')
                    ->sum('quantity');
                    // dd($pri);

          // $quantity = 0;
          // $quantityPRI = 0;

          // foreach($items as $data){
          //   $quantity += $data->quantity;
          // }

          // foreach($pri as $pri){
          //   $quantityPRI += $pri->quantity;
          // }
          $purpose = "";
          $name = "";
          $ao_name = "";
          $designation = "";
          $ao_designation = "";
          $remaining = $items - $pri;
          if($remaining == 0){
            session(['globalerror' => "There are no remaining items for you to PR in this Project."]);
          }else{
              Session::forget('globalerror');
          }
          // dd($information);
          // if(count($ppmp_deadlines)==0){
          //       session(['globalerror' => "Please set deadline first"]);
          // }else{
          //       Session::forget('globalerror');
          // }
          return view('pages.department.create-purchase-request',compact('hope','purpose','name','designation','ao_name','ao_designation','itemsForPR','pr_no','itemsForPRCount','itemsFromPRI','date','details','fund_source','project_code'),
              ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]
              );
          # end
     } catch (\Throwable $th) {
         throw $th;
     }
  }

  public function editPR(Request $request) {
    try {
      // dd($request->all());
      // dd($this->aes->decrypt($request->id));
      $date = Carbon::now()->format('m/d/Y');
      $pr_no = $this->aes->decrypt($request->pr_no);
      $id = $this->aes->decrypt($request->id);
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
          ["link" => "/", "name" => "Home"],
          ["link" => "/PR/trackPR", "name" => "Track PR"],
          ["name" => "Edit PR"]
      ];
     
        $hope = DB::table('users')
                  ->where('role',12)
                  ->where('campus',session('campus'))
                  ->get();
        foreach($hope as $data){
          $hope = $data->name;
        }
        $itemsForPR = DB::table("purchase_request_items as pri")
                  ->select('pri.*','p.item_name','p.item_description','p.unit_price')
                  ->join("ppmps as p", "pri.item_id", "=", "p.id")
                  ->where('pri.pr_no',$pr_no)
                  ->whereNull('pri.deleted_at')
                  ->get();
        $itemsForPRCount = count($itemsForPR);
        $itemsFromPRI = DB::table("purchase_request_items as pri")
                  ->select('pri.*','p.unit_of_measurement','p.item_description','p.unit_price')
                  ->join("ppmps as p", "pri.item_id", "=", "p.id")
                  ->where('pri.pr_no',$pr_no)
                  ->whereNull('pri.deleted_at')
                  ->get();;
        $pc = DB::table('purchase_request')
                          ->select('project_code')
                          ->where('pr_no',$pr_no)
                          ->whereNull('deleted_at')
                          ->first();
        $project_code = $pc->project_code;
        $details = DB::table("project_titles as pt")
                  ->select("pt.campus","pt.project_title","pt.fund_source","d.department_name")
                  ->join("departments as d", "pt.department_id", "=", "d.id")
                  ->join("fund_sources as fs", "pt.fund_source", "=", "fs.id")
                  ->where("pt.id", $project_code)
                  ->whereNull('pt.deleted_at')
                  ->get();

        $fund_source = DB::table("project_titles as pt")
                  ->select("fs.fund_source")
                  ->join("fund_sources as fs", "pt.fund_source", "=", "fs.id")
                  ->where("pt.id", $project_code)
                  ->whereNull('pt.deleted_at')
                  ->get();

        $items = DB::table('ppmps')
                  // ->select('id','item_name','quantity')
                  ->where('project_code','=',$project_code)
                  ->where('mode_of_procurement','!=',33)
                  ->where('app_type','Non-CSE')
                  ->where('pr_no','!=',0)
                  ->whereNull('deleted_at')
                  ->sum('quantity');

        $pri = DB::table('purchase_request_items')
                  // ->select('item_id','quantity')
                  ->where('project_code','=',$project_code)
                  // ->where('pr_no','!=',0)
                  ->whereNull('deleted_at')
                  ->sum('quantity');
                  // dd($pri);
        $information = DB::table('purchase_request as pr')
                    ->select('pr.purpose','u.name','pr.designation','ps.name as ao_name','ps.designation as ao_designation')
                    ->join('users as u','pr.printed_name','=','u.id')
                    ->join('pr_signatories as ps','pr.approving_officer','=','ps.id')
                    ->where('pr.id',$id)
                    ->get();
                    
          foreach($information as $data){
            $purpose = $data->purpose;
            $name = $data->name;
            $ao_name = $data->ao_name;
            $designation = $data->designation;
            $ao_designation = $data->ao_designation;
          }
        $remaining = $items - $pri;
        // dd($information); 
        if($remaining == 0){
          session(['globalerror' => "There are no remaining items for you to PR in this Project! "]);
        }else{
            Session::forget('globalerror');
        }
                  // dd($remaining);
        // if(count($ppmp_deadlines)==0){
        //       session(['globalerror' => "Please set deadline first"]);
        // }else{
        //       Session::forget('globalerror');
        // }
        $project_code = $this->aes->encrypt($project_code);
        // dd($project_code); 

        return view('pages.department.edit-purchase-request',compact('hope','purpose','name','designation','ao_name','ao_designation','itemsForPR','pr_no','itemsForPRCount','itemsFromPRI','date','details','fund_source','project_code'),
            ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]
            );
        # end
   } catch (\Throwable $th) {
       throw $th;
   }
  }

  public function view_signed_pr(Request $request) {
    // dd($request->all());
    try {
        $id = (new AESCipher)->decrypt($request->id);
        $response = DB::table('signed_purchase_request')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->get([
                'file_name'
            ]);
        # this will create history_log
            (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                $id,
                'Viewed Uploaded Signed PR',
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

  public function download_signed_PR(Request $request) {
    // dd($request->all());
    try {
        $id = (new AESCipher)->decrypt($request->id);
        $response = DB::table('signed_purchase_request')
        ->where('id', $id)
        ->whereNull('deleted_at')
        ->get([
            'file_name'
        ]);
        // dd($response );
        # this will created history_log
            (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                $id,
                'Downloaded Signed PR',
                'Download',
                $request->ip(),
            );
        # end
        $path = 'PMIS/signed_purchase_request/';
        return Storage::download($path.$response[0]->file_name);
    } catch (\Throwable $th) {
        throw $th;
        return view('pages.error-500');
    }
  }

  public function edit_signed_pr(Request $request) {
    try {
      // dd($request->all());
      $id = $this->aes->decrypt($request->id);

      $response = DB::table('signed_purchase_request')
                    ->where('id',$id)
                    ->get();
      foreach($response as $data){
        $filename = $data->file_name;
      }
      $file_name = explode('-',$filename );
      if($response){
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $response,
            'file_name' => $file_name,
        ]);
      }else{
        return response()->json([
                  'status' => 400,
                  'message' => 'Failed',
          ]);
      }
   } catch (\Throwable $th) {
       throw $th;
   }
  }

  public function update_signed_pr(Request $request){
    try {
      $id = $request->signedPR_id;
      $pr_no = $request->update_pr_no;
      $file_name = $request->update_file_name.'-'.time();
      $file = $request->file('update_file');
      $extension = $request->file('update_file')->getClientOriginalExtension();
      $is_valid = false;

      $checkPRNo = DB::table('signed_purchase_request')
          ->where('id','!=', $id)
          ->where('pr_no',$pr_no)
          ->whereNull('deleted_at')
          ->get();
      $checkFileName = DB::table('signed_purchase_request')
          ->select('file_name')
          ->where('id', $id)
          ->whereNull('deleted_at')
          ->get();
      foreach($checkFileName as $data){
        $oldFileName = $data->file_name;
      }

      // dd($oldFileName);
      if(count($checkPRNo) == 1){
        return response()->json([
          'status' => 400, 
          'message' => 'Signed PR for '.$pr_no.' is already uploaded!',
        ]);
      }
      // dd($request->all());
      # validate extension
      $allowed_extensions = 'pdf';
      if($allowed_extensions == $extension) {
          $is_valid = true;
      }
      if($is_valid == false) {
        return response()->json([
          'status' => 400,
          'message' => 'Please upload .pdf file!',
        ]);
      }

      $destination_path = env('APP_NAME').'\\signed_purchase_request\\';
      Storage::delete($destination_path.$oldFileName);

      if (!Storage::exists($destination_path)) {
        Storage::makeDirectory($destination_path);
      }
      $file->storeAs($destination_path, $file_name.'.'.$extension);
      $file->move('storage/'. $destination_path, $file_name.'.'.$extension); 

      (new HistoryLogController)
      ->store(
            session('department_id'),
            session('employee_id'),
            session('campus'),
            $id,
            'Updated the Uploaded Signed PR',
            'Update',
            $request->ip()
          );

      $response = DB::table('signed_purchase_request')
                    ->where('id',$id)
                    ->update([
                        'pr_no' => $pr_no,
                        'file_name' => $file_name.'.pdf',
                        'updated_at' =>  Carbon::now()
                    ]);
                    
      if($response){
        return response()->json([
          'status' => 200, 
          'message' => 'Signed PR Updated Successfully!',
        ]); 
      }else{
        return response()->json([
          'status' => 400, 
          'message' => 'Error!',
        ]); 
      }
      
    } catch (\Throwable $th) {
      dd('add_Items_To_PR FUNCTION '+$th);
    }
  }

  public function delete_signed_pr(Request $request){
    // dd($request->all());
    $id = (new AESCipher())->decrypt($request->id);
    $checkFileName = DB::table('signed_purchase_request')
        ->select('file_name')
        ->where('id', $id)
        ->get();
    foreach($checkFileName as $data){
      $oldFileName = $data->file_name;
    }
    $response = DB::table('signed_purchase_request')
                  ->where('id',$id)
                  ->update([
                      'deleted_at' =>  Carbon::now()
                  ]);
    

    $destination_path = env('APP_NAME').'\\signed_purchase_request\\';
    Storage::delete($destination_path.$oldFileName);

    if($response)
    {
        (new HistoryLogController)
          ->store(
            session('department_id'),
            session('employee_id'),
            session('campus'),
            $id,
            'Deleted Uploaded Signed PR',
            'Delete',
            $request->ip()
          );

        return response()->json([
            'status'=>200,
            'message'=>'PR Deleted Successfully!'
        ]);
    }
    else
    {
        return response()->json([
            'status'=>400,
            'message'=>'Error!'
        ]);
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
  
  public function upload_signed_pr(Request $request){
    try {
      // dd($request->all());
      $pr_no = $request->pr_no;
      $file_name = $request->file_name.'-'.time();
      $file = $request->file('file');
      $extension = $request->file('file')->getClientOriginalExtension();
      $is_valid = false;
      # validate extension
          $allowed_extensions = ['pdf'];
          for ($i = 0; $i < count($allowed_extensions) ; $i++) { 
             if($allowed_extensions[$i] == $extension) {
                  $is_valid = true;
             }
          }
          if($is_valid == false) {
            return response()->json([
                'status' => 400, 
                'message' => 'Please upload .pdf file!',
            ]);
          }

          $validatePRNo = DB::table('purchase_request')
                          ->where('pr_no',$pr_no)
                          ->where('status',2)
                          ->where('department_id', session('department_id'))
                          ->where('campus', session('campus'))
                          ->whereNull('deleted_at')
                          ->get();

          if(count($validatePRNo)==0){
            return response()->json([
              'status' => 400, 
              'message' => 'PR '.$pr_no.' is not yet approved!',
            ]);
          }   
          
          $checkPRNo = DB::table('signed_purchase_request')
                          ->where('pr_no',$pr_no)
                          ->where('department_id', session('department_id'))
                          ->where('campus', session('campus'))
                          ->whereNull('deleted_at')
                          ->get();

          if(count($checkPRNo) == 1){
            return response()->json([
              'status' => 400, 
              'message' => 'Signed PR for '.$pr_no.' is already uploaded!',
            ]);
          }
          // $file_name = $item_name.'-'.time();
          $destination_path = env('APP_NAME').'\\signed_purchase_request\\';
          if (!Storage::exists($destination_path)) {
            Storage::makeDirectory($destination_path);
          }
          $file->storeAs($destination_path, $file_name.'.'.$extension);
          $file->move('storage/'. $destination_path, $file_name.'.'.$extension); 

          (new HistoryLogController)
          ->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                null,
                'Uploaded a File',
                'Upload',
                $request->ip()
              );

          $response = DB::table('signed_purchase_request')
                              ->insert([
                                  'pr_no' => $pr_no,
                                  'campus' => session('campus'),
                                  'department_id' => session('department_id'),
                                  'employee_id' => session('employee_id'),
                                  'file_name' => $file_name.'.'.$extension,
                                  'created_at' =>  Carbon::now()
                              ]);

          if($response){
            return response()->json([
              'status' => 200, 
              'message' => 'File uploaded succesfully!',
          ]);

            return response()->json([
              'status' => 400, 
              'message' => 'Error!',
            ]);
          }
    } catch (\Throwable $th) {
      dd('upload_signed_pr FUNCTION '+$th);
    }
  }

  public function addItem(Request $request){
    try {
      // dd($request->all());
      $file = $request->file('file');
      $extension = $request->file('file')->getClientOriginalExtension();
      $pr_no = (new AESCipher())->decrypt($request->pr_no);
    // dd($pr_no);
      $is_valid = false;
      # validate extension
          $allowed_extensions = ['pdf', 'jpeg', 'jpg', 'png'];
          for ($i = 0; $i < count($allowed_extensions) ; $i++) { 
             if($allowed_extensions[$i] == $extension) {
                  $is_valid = true;
             }
          }
          if($is_valid == false) {
            return response()->json([
                  'status' => 400, 
                  'message' => 'Invalid file format!',
              ]); 
          }

      $id = $request->item;
      $quantityToPR = $request->quantity;
      if($quantityToPR == 0){
        return response()->json([
          'status' => 400, 
          'message' => 'Quantity cannot be zero!',
        ]); 
      }
      $specification = $request->specification;
      $project_code = $this->aes->decrypt($request->project_code);

      $quantityFromPPMP = DB::table("ppmps")
                ->select('quantity')
                ->where('project_code',$project_code)
                ->where('id',$id)
                ->whereNull('deleted_at')
                ->get();
      $quantityFromPRI = DB::table("purchase_request_items")
                ->select('quantity')
                ->where('project_code',$project_code)
                ->where('item_id',$id)
                ->whereNull('deleted_at')
                ->get();
      
      $quantity = $quantityToPR;
      for($i=0; $i < count($quantityFromPRI); $i++){
                $quantity += $quantityFromPRI[$i]->quantity;
      }
      
      if($quantityFromPPMP[0]->quantity < $quantity){
        return response()->json([
          'status' => 400, 
          'message' => 'The quantity exceeds the remaining item(s)!',
        ]);  
      }else{
          if($pr_no == "0000-00-0000"){
            $pr_no = 0;
          }
          $itemCheck = DB::table('purchase_request_items')
                        ->select('*')
                        ->where('project_code',$project_code)
                        ->where('item_id',$id)
                        ->where('pr_no', $pr_no)
                        ->whereNull('deleted_at')
                        ->count();
                        // dd($itemCheck);
          if($itemCheck == 1){
            return response()->json([
                'status' => 400, 
                'message' => 'Item already exist in the draft!',
            ]); 
          }else{
              $item = DB::table('ppmps')
                    ->select('item_name')
                    ->where('id',$id)
                    ->get();
              $item_name = '';
              foreach($item as $data){
                $item_name = $data->item_name;
              }

              $file_name = $item_name.'-'.time();
              $destination_path = env('APP_NAME').'\\purchase_request\\item_upload\\';
              if (!Storage::exists($destination_path)) {
                Storage::makeDirectory($destination_path);
              }
              $file->storeAs($destination_path, $file_name.'.'.$extension);
              $file->move('storage/'. $destination_path, $file_name.'.'.$extension); 

              if($pr_no == "0000-00-0000"){
                $response = DB::table('purchase_request_items')
                              ->insert([
                                  'project_code' => $project_code,
                                  'item_id' => $id,
                                  'quantity' => $quantityToPR,
                                  'specification' => $specification,
                                  'file_name' => $file_name.'.'.$extension,
                                  'created_at' =>  Carbon::now()
                              ]);
              }else{
                $response = DB::table('purchase_request_items')
                              ->insert([
                                  'pr_no' => $pr_no,
                                  'project_code' => $project_code,
                                  'item_id' => $id,
                                  'quantity' => $quantityToPR,
                                  'specification' => $specification,
                                  'file_name' => $file_name.'.'.$extension,
                                  'created_at' =>  Carbon::now()
                              ]);
              }
              if($response){
                return response()->json([
                  'status' => 200, 
                  'message' => 'Item Added Successfully!',
                ]); 
              }else{
                return response()->json([
                  'status' => 400, 
                  'message' => 'Failed!',
                ]);    
              }
          }
      }
    } catch (\Throwable $th) {
      dd('add_Items_To_PR FUNCTION '+$th);
    }
  }

  public function updateItem(Request $request){
    try {
      // dd($request->all());
      $filename = $request->file('updatefile')->getClientOriginalName();
      $id = $request->id;
      $itemid = $this->aes->decrypt($request->itemid);
      $project_code = $this->aes->decrypt($request->project_code);
      $updatequantity = $request->updatequantity;
      $updatespecification = $request->updatespecification;
      
      $checkItem = DB::table('purchase_request_items')
                      ->where('id',$itemid)
                      ->where('quantity',$request->updatequantity)
                      ->where('file_name',$filename)
                      ->where('specification',$request->updatespecification)
                      ->whereNull('deleted_at')
                      ->get();
      if($updatequantity == 0){
        return response()->json([
          'status' => 400, 
          'message' => 'Quantity cannot be zero!',
        ]); 
      }
      if(count($checkItem) == 1){
        return response()->json([
          'status' => 400,
          'message' => 'No changes made!',
        ]);
      }

      $file = $request->file('updatefile');
      $extension = $request->file('updatefile')->getClientOriginalExtension();
          $is_valid = false;
      # validate extension
          $allowed_extensions = ['pdf', 'jpeg', 'jpg', 'png'];
          for ($i = 0; $i < count($allowed_extensions) ; $i++) { 
             if($allowed_extensions[$i] == $extension) {
                  $is_valid = true;
             }
          }
          if($is_valid == false) {
            return response()->json([
              'status' => 400,
              'message' => 'Invalid Format!',
            ]);
          }

      $quantityFromPPMP = DB::table("ppmps")
                // ->select('quantity')
                ->where('project_code',$project_code)
                ->where('id',$id)
                ->whereNull('deleted_at')
                ->sum('quantity');
      $quantityFromPRI = DB::table("purchase_request_items")
                // ->select('quantity')
                ->where('project_code',$project_code)
                ->where('item_id',$id)
                ->whereNull('deleted_at')
                ->sum('quantity');
      $quantity = DB::table("purchase_request_items")
                // ->select('quantity')
                ->where('project_code',$project_code)
                ->where('id',$itemid)
                ->whereNull('deleted_at')
                ->sum('quantity');
      // dd($quantityFromPRI-$quantity);
      // $quantity = $updatequantity;
      // for($i=0; $i < count($quantityFromPRI); $i++){
      //           $quantity += $quantityFromPRI[$i]->quantity;
      // }
      if($quantityFromPPMP < ($quantityFromPRI-$quantity) + $updatequantity){
        return response()->json([
          'status' => 400,
          'message' => 'The quantity exceeds the remaining item(s)!',
        ]);
      } 
      else{
              $oldfile = DB::table('purchase_request_items')
                            ->select('file_name')
                            ->where('id',$itemid)
                            ->get();
              foreach($oldfile as $data){
                $oldfilename = $data->file_name;
              }

              $item_name = $request->updatename;
              $file_name = $item_name.'-'.time();
              $destination_path = env('APP_NAME').'\\purchase_request\\item_upload\\';
              Storage::delete($destination_path.$oldfilename);

              if (!Storage::exists($destination_path)) {
                Storage::makeDirectory($destination_path);
              }
              $file->storeAs($destination_path, $file_name.'.'.$extension);
              $file->move('storage/'. $destination_path, $file_name.'.'.$extension); 


              $response = DB::table('purchase_request_items')
                            ->where('id',$itemid)
                            ->update([
                                'project_code' => $project_code,
                                'quantity' => $updatequantity,
                                'specification' => $updatespecification,
                                'file_name' => $file_name.'.'.$extension,
                                'updated_at' =>  Carbon::now()
                            ]);
                            
              if($response){
                return response()->json([
                  'status' => 200, 
                  'message' => 'Item Updated Successfully!',
                ]); 
              }else{
                return response()->json([
                  'status' => 400, 
                  'message' => 'Error!',
                ]); 
              }
      }
      
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
                      ->orderBy('users.name')
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

  public function getApprovingOfficers(Request $request){
    try {
      // dd($request->all()); 
      $total = $request->total;

      if($total > 100000){
        $ApprovingOfficer = DB::table('pr_signatories')
                ->where('amount','>',100000)
                ->orderBy('name')
                ->get();
      }else if($total > 25000 && $total <= 100000){
        // dd('sadfdsfg');
        $ApprovingOfficer = DB::table('pr_signatories')
                ->where([
                  ['amount','>','25000'],
                  ['amount','<=','100000'],
                ])
                ->orderBy('name')
                ->get();
      }else if($total <= 25000){
        $ApprovingOfficer = DB::table('pr_signatories')
                ->where('amount','<=',25000)
                ->orderBy('name')
                ->get();
      }
      // $department_id = session('department_id');
      // $employees = DB::table('users')
      //               ->select('users.id','users.name')
      //               ->where('department_id','=',$department_id)
      //               ->orderBy('users.name')
      //               ->get();
      // dd($ApprovingOfficer);  
      if($ApprovingOfficer){
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $ApprovingOfficer,
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

  public function getItems(Request $request){
      try {
        // dd($request->all()); 
        $project_code = $this->aes->decrypt($request->project_code);

        // $department_id = session('department_id');
        $items = DB::table('ppmps')
                      ->select('id','item_name','quantity')
                      ->where('project_code','=',$project_code)
                      ->where('mode_of_procurement','!=',33)
                      ->where('app_type','Non-CSE')
                      ->whereNull('deleted_at')
                      ->orderBy('item_name')
                      ->get();
        $pri = DB::table('purchase_request_items')
                      ->select('item_id','quantity')
                      ->where('project_code','=',$project_code)
                      ->whereNull('deleted_at')
                      ->get();
              // Session::forget('error');

        $quantity = 0;
        $quantityPRI = 0;

        foreach($items as $data){
          $quantity += $data->quantity;
        }

        foreach($pri as $pri){
          $quantityPRI += $pri->quantity;
        }
        $remaining = $quantity - $quantityPRI;
        // dd($remaining);
        // Session::forget('error');
        
        // if($remaining != 0){
          if($items){
            // if($quantity > $quantityPRI){
            // Session::forget('message');
                return response()->json([
                  'status' => 200,
                  'message' => 'Success',
                  'data' => $items,
                  // 'remaining' => $remaining
                  // Session::forget('message')
                ]);
            }
            // else{
            //   Session::flash('message', 'This is a message!'); 
            //   Session::flash('alert-class', 'alert-danger');
            // }
          // }
          return response()->json([
              'status' => 400,
              'message' => 'Error'
          ]);
        
        // }if($remaining == 0){
          // Session::flash(['message', "Please set deadline first"]);
        // }
        
        
        // dd($quantity-$quantityPRI);  
        
        
        
      } catch (\Throwable $th) {
        dd('getItems FUNCTION '+$th);
      }
  }

  public function getItem(Request $request){
      try {
        // dd($request->all()); 
        $id = $request->item;
        $project_code = $this->aes->decrypt($request->project_code);

        $quantityFromPRI = DB::table("purchase_request_items")
                            ->select('quantity')
                            ->where('project_code',$project_code)
                            ->where('item_id',$id)
                            ->whereNull('deleted_at')
                            ->get();
        
        $quantity = 0;
        for($i=0; $i < count($quantityFromPRI); $i++){
                  $quantity += $quantityFromPRI[$i]->quantity;
        }

        // $department_id = session('department_id');
        $item = DB::table('ppmps')
                      ->select('quantity','item_name')
                      ->where('project_code','=',$project_code)
                      ->where('app_type','Non-CSE')
                      ->where('id','=',$id)
                      ->whereNull('deleted_at')
                      ->get();
                      // dd($quantity);  
        // dd($item[0]->quantity);
        if($quantity == $item[0]->quantity){
            return response()->json([
                'status' => 400,
                'message' => $item[0]->item_name.' are already consumed!',
            ]); 
        }else{
            return response()->json([
                'status' => 200,
                'message' => 'Success',
                'data' => ($item[0]->quantity)-$quantity,
            ]);
        }


      } catch (\Throwable $th) {
        dd('getItems FUNCTION '+$th);
      }
  }

  public function editPRItem(Request $request){
    try {
      // dd($request->all()); 
      $id = $this->aes->decrypt($request->item);
      $project_code = $this->aes->decrypt($request->project_code);
      // dd($this->aes->decrypt($request->item)); 

      $response = DB::table("purchase_request_items as pri")
                          ->select('pri.*','p.item_name','p.id as pID')
                          ->join("ppmps as p", "pri.item_id", "=", "p.id")
                          ->where('pri.project_code',$project_code)
                          ->where('pri.id',$id)
                          ->get();
      foreach($response as $data){
        $item_name = $data->item_name;
        $quantity = $data->quantity;
        $file_name = $data->file_name;
        $specification = $data->specification;
        $id = $data->pID;
      }
      if($response){
          return response()->json([
              'status' => 200,
              'message' => 'Success',
              'id' => $id,
              'item_id' => $request->item,
              'item_name' => $item_name,
              'quantity' => $quantity,
              'file_name' => $file_name,
              'specification' => $specification,
              // 'data' => [$id,$quantity,$file_name,$specification],
          ]);

      }else{
        return response()->json([
                  'status' => 400,
                  'message' => 'failed',
          ]);
      }
      } catch (\Throwable $th) {
        dd('getItems FUNCTION '+$th);
      }
  }

  public function savePR(Request $request){
      try {
        // dd($request->all());  
        $has_pr_no = (new AESCipher)->decrypt($request->pr_no);
        $approvingOfficer = $request->approvingOfficer;
        $approving_officer = explode('*',$approvingOfficer);
        
        // dd($approving_officer[0]);
        $current = Carbon::now();
        $department_id = session('department_id');
        $campus = session('campus');
        $employee = $request->employee;
        $purpose = $request->purpose;
        $designation = $request->designation;
        $fund_source = (new AESCipher)->decrypt($request->fund_source);
        $project_code = (new AESCipher)->decrypt($request->project_code);
        $date = $current->format('Y-m');
        $pr_no_check = DB::table('purchase_request as pr')
                          ->select('pr.*')
                          ->whereNull('deleted_at')
                          ->orderBy('pr.pr_no')
                          ->get();

        #START Code For Replacing pr_no that has been Deleted              
        $count = 1;
        foreach($pr_no_check as $data){
          $pr_no = $date.'-'.str_pad(0000+$count,4,"0",STR_PAD_LEFT);
          if($pr_no == $data->pr_no){
            $count++;}
        }

        #END Code For Replacing the Deleted PR   
        
        #NOTE: DON'T DELETE THIS CODE
        #THIS Continues the sequence of PR No
        $pr_no = $date.'-'.str_pad(0000+$count,4,"0",STR_PAD_LEFT);

        if($has_pr_no=="0000-00-0000"){
          // dd($pr_no);

          $purchaseRequest = DB::table('purchase_request')
                              ->insert([
                                'department_id' => $department_id,
                                'project_code' => $project_code,
                                'pr_no' => $pr_no,
                                'status' => 1,
                                'campus' => $campus,
                                'fund_source_id' => $fund_source,
                                'purpose' => $purpose,
                                'printed_name' => $employee,
                                'approving_officer' => $approving_officer[0],
                                'designation' => $designation,
                                'created_at' =>  Carbon::now()
                            ]);

          DB::table('purchase_request_items')
              ->where('project_code', $project_code)
              ->where('pr_no', 0)
              ->update([
                'pr_no' => $pr_no
              ]);

              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                null,
                'Created Purchase Request with PR No '.$pr_no,
                'Create',
                $request->ip(),
            );

          if($purchaseRequest){
            return response()->json([
              'status' => 200,
              'message' => 'PR Completed Successfully',
            ]);
          }
          return response()->json([
            'status' => 400,
            'message' => 'Error'
          ]);
        }else{
          $purchaseRequest = DB::table('purchase_request')
                                ->where('pr_no',$has_pr_no)
                                ->update([
                                  // 'department_id' => $department_id,
                                  // 'pr_no' => $pr_no,
                                  // 'campus' => $campus,
                                  'status' => 1,
                                  'purpose' => $purpose,
                                  'printed_name' => $employee,
                                  'approving_officer' => $approving_officer[0],
                                  'designation' => $designation,
                                  'updated_at' =>  Carbon::now()
                                ]);

          (new HistoryLogController)->store(
            session('department_id'),
            session('employee_id'),
            session('campus'),
            null,
            'Updated Purchase Request with PR No '.$pr_no,
            'Update',
            $request->ip(),
        );

          if($purchaseRequest){
            return response()->json([
              'status' => 200,
              'message' => 'PR Updated Successfully',
            ]);
          }
            return response()->json([
              'status' => 400,
              'message' => 'Error'
            ]);
        }
        
        
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

      $purchase_request = DB::table("purchase_request as pr")
                          ->select("pr.*","fs.fund_source","d.department_name","u.name")
                          ->join("fund_sources as fs","pr.fund_source_id","fs.id")
                          ->join("departments as d","pr.department_id","d.id")
                          ->join("users as u","pr.printed_name","u.id")
                          ->where("pr.id",$id)
                          ->get();

                          (new HistoryLogController)->store(
                            session('department_id'),
                            session('employee_id'),
                            session('campus'),
                            $id,
                            'Viewed Purchase Request Status',
                            'View',
                            $request->ip(),
                        );

      return view('pages.department.view_status_page',compact('purchase_request'),  [
                  'pageConfigs'=>$pageConfigs,
                  'breadcrumbs'=>$breadcrumbs,
                  // 'error' => $error,
              ]); 
  }

  public function pr_routing_slip(Request $request){
    // dd($request->all());
    // dd($id);
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],
      ["link" => "/department/trackPR", "name" => "Approved PRs"],
      // ["link" => "/department/purchaseRequest/createPR", "name" => "Create PR"],
      ["name" => "Routing Slip"]
    ];

    $id = (new AESCipher())->decrypt($request->id);
    
    $pr_no = DB::table('purchase_request')
                ->where('id',$id)
                ->get(['pr_no']);

    foreach($pr_no as $data){
      $pr_no = $data->pr_no;
    }

    $purchase_request = DB::table("purchase_request as pr")
                        ->select("pr.*","fs.fund_source","d.department_name","u.name")
                        ->join("fund_sources as fs","pr.fund_source_id","fs.id")
                        ->join("departments as d","pr.department_id","d.id")
                        ->join("users as u","pr.printed_name","u.id")
                        ->where("pr.id",$id)
                        ->get();

    $response = DB::table('routing_slip')
                  ->where('pr_no',$pr_no)
                  ->get();

                  // dd($response);
              
      (new HistoryLogController)->store(
        session('department_id'),
        session('employee_id'),
        session('campus'),
        $id,
        'Viewed Purchase Request Status',
        'View',
        $request->ip(),
      );

    return view('pages.department.pr-routing-slip',compact('purchase_request','response'),  [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]); 
  }

  public function saveChanges(Request $request){
    try {
      // dd($request->all());
      $pr_no = $request->pr_no;
      $activityNumber = $request->activityNumber;
      $date_received = $request->date_received;
      $date_released = $request->date_released;      
      $time_received = $request->time_received;
      $time_released = $request->time_released;
      $remark = $request->remark;
      $role = session('role');
      $campus = session('campus');
      $employee_id = session('employee_id');

      $checkActivity = DB::table('routing_slip')
                        ->where('activity',$activityNumber)
                        ->where('pr_no',$pr_no)
                        ->where('campus',$campus)
                        ->whereNull('deleted_at')
                        ->count();

      if($checkActivity==1){
        $response = DB::table('routing_slip')
        ->where('pr_no',$pr_no)
        ->where('activity',$activityNumber)
        ->where('campus',$campus)
        ->update([
          'date_received' => $date_received.' '.$time_received,
          'date_released' => $date_released.' '.$time_released,
          'remark' => $remark,
          'updated_at' => Carbon::now(),
          
        ]);
        (new HistoryLogController)->store(
          session('department_id'),
          session('employee_id'),
          session('campus'),
          null,
          'Updated Purchase Status',
          'Update',
          $request->ip(),
        );
        return response()->json([
          'status' => 200,
          'message' => 'Updated Successfully!'
        ]);

      }else{
        $response = DB::table('routing_slip')
        ->insert([
          'pr_no' => $pr_no,
          'employee_id' => $employee_id,
          'role' => $role,
          'campus' => $campus,
          'activity' => $activityNumber,
          'date_received' => $date_received.' '.$time_received,
          'date_released' => $date_released.' '.$time_released,
          'remark' => $remark,
          'created_at' => Carbon::now(),
        ]);

            (new HistoryLogController)->store(
              session('department_id'),
              session('employee_id'),
              session('campus'),
              null,
              'Saved Purchase Status',
              'Save',
              $request->ip(),
            );

        return response()->json([
          'status' => 200,
          'message' => 'Saved Successfully!'
        ]);

      }
      
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getData(Request $request){
    try {
      // dd($request->all()); 
      $pr_no = $request->pr_no;
      $response = DB::table('routing_slip as rs')
                    ->select('rs.*','u.name')
                    ->join('users as u','rs.employee_id','u.employee_id')
                    ->where('rs.pr_no',$pr_no)
                    ->where('rs.campus',session('campus'))
                    ->get();
            //  dd($response);       

      return response()->json([
        'status' => 200,
        'message' => 'Success',
        'data' => $response,
      ]);

      } catch (\Throwable $th) {
        dd('getData FUNCTION '+$th);
      }
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
      // $hope = DB::table('users')
      // ->where('role',12)
      // ->where('campus',session('campus'))
      // ->get();
      $id = (new AESCipher())->decrypt($request->id);
      // $date = Carbon::now()->format('m/d/Y');

      $purchase_request = DB::table("purchase_request as pr")
                            ->select("pr.*","fs.fund_source","u.name","d.department_name","ps.name as ao_name","ps.designation as ao_designation","ps.title as ao_title")
                            ->join("fund_sources as fs","pr.fund_source_id","fs.id")
                            ->join("departments as d","pr.department_id","d.id")
                            ->join("users as u","pr.printed_name","u.id")
                            ->join("pr_signatories as ps","pr.approving_officer","ps.id")
                            ->where("pr.id",$id)
                            ->get();
                            
      $pr_no = '';
      foreach($purchase_request as $data){
        $pr_no = $data->pr_no;
      }
      $itemsForPR = DB::table("purchase_request_items as pri")
                ->select('pri.*','p.unit_of_measurement','p.item_description','p.unit_price')
                ->join('ppmps as p','pri.item_id','p.id')
                ->where('pri.pr_no',$pr_no)
                ->whereNull('pri.deleted_at')
                ->get();

      $totalcost = 0;
      foreach($itemsForPR as $data){
        $totalcost += $data->unit_price * $data->quantity ;
      }


      // $signatory 
                // $sum = DB::table("purchase_request_items as pri")
                // ->select('pri.*','p.unit_of_measurement','p.item_description','p.unit_price')
                // ->join('ppmps as p','pri.item_id','p.id')
                // ->where('pri.pr_no',$pr_no)
                // ->whereNull('pri.deleted_at')
                // ->get
                // dd($totalcost);

                (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  null,
                  'Viewed Purchase Request with PR No '.$pr_no,
                  'View',
                  $request->ip(),
              );

      return view('pages.department.view_pr_page',compact('purchase_request','itemsForPR','id'), [
                  'pageConfigs'=>$pageConfigs,
                  'breadcrumbs'=>$breadcrumbs,
                  // 'error' => $error,
              ]); 
  }

  public function remove_item(Request $request){
    // dd($request->all());
    $id = (new AESCipher())->decrypt($request->id);
    $pr_no = (new AESCipher())->decrypt($request->pr_no);
    $last = DB::table('purchase_request_items')
              ->where('pr_no',$pr_no)
              ->whereNull('deleted_at')
              ->count();
    // dd($last);
    $item = DB::table('purchase_request_items')
              ->select('file_name')
              ->where('id',$id)
              // ->where('pr_no',0)
              ->get();
    $file_name = '';
    foreach($item as $data){
      $file_name = $data->file_name;
    }
    // dd($file_name);
    $destination_path = env('APP_NAME').'\\purchase_request\\item_upload\\';
    Storage::delete($destination_path.$file_name);
    // unlink($destination_path.$file_name);
    // Storage::disk('local')->delete($destination_path.$file_name);
    // Storage::delete('storage/'.$destination_path.$file_name);
    if($last == 1){
      DB::table('purchase_request')
            ->where('pr_no',$pr_no)
            ->update([
              'status' => 0,
            ]);
    }
    $response = DB::table('purchase_request_items')
            ->where('id',$id)
            ->update([
              'deleted_at' => Carbon::now(),
            ]);
    if($response)
    {
        return response()->json([
            'status'=>200,
            'message'=>'Item Removed Successfully!'
        ]);
    }
    else
    {
        return response()->json([
            'status'=>400,
            'message'=>'No Item Found!'
        ]);
    }
  }

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
            $file_name = (new GlobalDeclare)->project_category((new AESCipher)->decrypt($request->project_category)) .'-'. time();
            $destination_path = env('APP_NAME').'\\department_upload\\signed_ppmp\\';
            if (!Storage::exists($destination_path)) {
                Storage::makeDirectory($destination_path);
            }
            $file->storeAs($destination_path, $file_name.'.'.$extension);
            // \Storage::put($destination_path, $file_name.'.'.$extension);
            $file->move('storage/'. $destination_path, $file_name.'.'.$extension);
            DB::table('signed_ppmp')
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

            # storing data to signed_ppmp table
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

  public function printPR(Request $request) {
    // dd($request->all());
    $id = (new AESCipher())->decrypt($request->id);
    $date = Carbon::now()->format('Y-m-d');

    $purchase_request = DB::table("purchase_request as pr")
                          ->select("pr.*","fs.fund_source","d.department_name","u.name","ps.name as ao_name","ps.designation as ao_designation","ps.title as ao_title")
                          ->join("fund_sources as fs","pr.fund_source_id","fs.id")
                          ->join("departments as d","pr.department_id","d.id")
                          ->join("users as u","pr.printed_name","u.id")
                          ->join("pr_signatories as ps","pr.approving_officer","ps.id")
                          ->where("pr.id",$id)
                          ->get();
   
    $pr_no = '';
    foreach($purchase_request as $data){
      $pr_no = $data->pr_no;
    }
                    // dd($hope);
    $itemsForPR = DB::table("purchase_request_items as pri")
              ->select('pri.*','p.unit_of_measurement','p.item_description','p.unit_price')
              ->join('ppmps as p','pri.item_id','p.id')
              ->where('pri.pr_no',$pr_no)
              ->whereNull('pri.deleted_at')
              ->get();
              // dd($itemsForPR);

    // $itemCount = 150;
    $itemCount = count($itemsForPR);

            //   (new HistoryLogController)->store(
            //     session('department_id'),
            //     session('employee_id'),
            //     session('campus'),
            //     null,
            //     'Printed Purchase Request with PR No '.$pr_no,
            //     'Print',
            //     $request->ip(),
            // );

    return view('pages.department.print_pr',compact('purchase_request','itemsForPR','itemCount','date','id'), [
                // 'pageConfigs'=>$pageConfigs,
                // 'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]); 
  }

  public function delete_pr(Request $request){
    // dd($request->all());

    $id = (new AESCipher())->decrypt($request->id);
    $pr_no = (new AESCipher())->decrypt($request->pr_no);

    $response = DB::table('purchase_request')
                  ->where('id',$id)
                  ->update([
                      'deleted_at' =>  Carbon::now()
                  ]);
    
    DB::table('purchase_request_items')
      ->where('pr_no',$pr_no)
      ->update([
          'deleted_at' =>  Carbon::now()
    ]);   

    $destination_path = env('APP_NAME').'\\purchase_request\\item_upload\\';
  
    $oldfile = DB::table('purchase_request_items')
                  ->select('file_name')
                  ->where('pr_no',$pr_no)
                  ->get();
    // dd($oldfile);
    foreach($oldfile as $data){
      $oldfilename = $data->file_name;
      Storage::delete($destination_path.$oldfilename);
    }

    (new HistoryLogController)->store(
      session('department_id'),
      session('employee_id'),
      session('campus'),
      null,
      'Deleted Purchase Request with PR No '.$pr_no,
      'Delete',
      $request->ip(),
    );

    if($response)
    {
        // return back()->with([
        //   'success' => 'PR Deleted Successfully!'
        // ]);
        return response()->json([
            'status'=>200,
            'message'=>'PR Deleted Successfully!'
        ]);
    }
    else
    {
      // return back()->with([
      //   'error' => 'Error!'
      // ]);
        return response()->json([
            'status'=>400,
            'message'=>'Error!'
        ]);
    }
  }

  
  // public function edit_pr(Request $request){
  //   dd('success');
  //   $id = (new AESCipher())->decrypt($request->id);
  //   $response = DB::table('purchase_request')
  //                 ->where('id',$id)
  //                 ->update([
  //                     'deleted_at' =>  Carbon::now()
  //                 ]);
  //   if($response)
  //   {
  //       return back()->with([
  //         'success' => 'PR Deleted Successfully!'
  //       ]);
  //       // return response()->json([
  //       //     'status'=>200,
  //       //     'message'=>'PR Deleted Successfully!'
  //       // ]);
  //   }
  //   else
  //   {
  //     return back()->with([
  //       'error' => 'Error!'
  //     ]);
  //       // return response()->json([
  //       //     'status'=>400,
  //       //     'message'=>'Error!'
  //       // ]);
  //   }
  // }

}
