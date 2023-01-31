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
      ["link" => "/", "name" => "Home"],["name" => "Approve PPMPs"]
    ];
    $year = Carbon::now()->format('Y');
    $department_id = session('department_id');
    $ppmps = DB::table("project_titles")
                ->select('project_titles.id','project_titles.project_title','project_titles.project_year','fund_sources.fund_source')
                ->join('fund_sources', 'fund_sources.id', '=', 'project_titles.fund_source')
                ->where('department_id',$department_id)
                // ->where('project_year',$year+1)
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
        // dd($this->aes->decrypt($request->id));
        $date = Carbon::now()->format('m/d/Y');
        $project_code = $request->id;
        $id = $this->aes->decrypt($request->id);
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],
            ["link" => "/department/purchaseRequest", "name" => "Approve PPMPs"],
            ["name" => "Create PR"]
        ];
       
          $itemsForPR = DB::table("purchase_request_items as pri")
                    ->select('pri.*','p.item_name','p.item_description','p.unit_price')
                    ->join("ppmps as p", "pri.item_id", "=", "p.id")
                    ->where('pri.project_code',$id)
                    ->where('pri.pr_no',0)
                    ->get();
          $itemsForPRCount = count($itemsForPR);
          $itemsFromPRI = DB::table("purchase_request_items as pri")
                    ->select('pri.*','p.unit_of_measurement','p.item_description','p.unit_price')
                    ->join("ppmps as p", "pri.item_id", "=", "p.id")
                    ->where('pri.project_code',$id)
                    ->where('pri.pr_no',0)
                    ->get();;
                    // dd($itemsFromPRI);

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

          $items = DB::table('ppmps')
                    ->select('id','item_name','quantity')
                    ->where('project_code','=',$id)
                    ->where('mode_of_procurement','!=',33)
                    // ->where('quantity','!=',0)
                    ->whereNull('deleted_at')
                    ->orderBy('item_name')
                    ->get();

          $pri = DB::table('purchase_request_items')
                    ->select('item_id','quantity')
                    ->where('project_code','=',$id)
                    ->whereNull('deleted_at')
                    ->get();

          $quantity = 0;
          $quantityPRI = 0;

          foreach($items as $data){
            $quantity += $data->quantity;
          }

          foreach($pri as $pri){
            $quantityPRI += $pri->quantity;
          }

          $remaining = $quantity - $quantityPRI;
          if($remaining == 0){
            session(['globalerror' => "Sorry! There are no remaining items for you to PR in this Project! "]);
          }else{
              Session::forget('globalerror');
          }
          // dd($remaining);
          // if(count($ppmp_deadlines)==0){
          //       session(['globalerror' => "Please set deadline first"]);
          // }else{
          //       Session::forget('globalerror');
          // }
          return view('pages.department.create-purchase-request',compact('itemsForPR','itemsForPRCount','itemsFromPRI','date','details','fund_source','project_code'),
              ['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]
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
  
  public function addItem(Request $request){
    try {
      // dd($request->all());
      $file = $request->file('file');
      $extension = $request->file('file')->getClientOriginalExtension();
      // dd($file);
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

      $id = $request->item;
      $quantityToPR = $request->quantity;
      $specification = $request->specification;
      $project_code = $this->aes->decrypt($request->project_code);

      $quantityFromPPMP = DB::table("ppmps")
                ->select('quantity')
                ->where('project_code',$project_code)
                ->where('id',$id)
                ->get();
      $quantityFromPRI = DB::table("purchase_request_items")
                ->select('quantity')
                ->where('project_code',$project_code)
                ->where('item_id',$id)
                ->get();
      
      $quantity = $quantityToPR;
      for($i=0; $i < count($quantityFromPRI); $i++){
                $quantity += $quantityFromPRI[$i]->quantity;
      }
      
      if($quantityFromPPMP[0]->quantity < $quantity){
        return back()->with([
          'error' => 'The quantity exceeds the remaining item(s)!'
        ]);
        return response()->json([
          'status' => 400, 
          'message' => 'The quantity exceeds the remaining item(s)!',
        ]);  
      }else{
          $itemCheck = DB::table('purchase_request_items')
                        ->select('*')
                        ->where('project_code',$project_code)
                        ->where('item_id',$id)
                        ->where('pr_no',0)
                        ->count();
                        // dd($itemCheck);
          if($itemCheck == 1){
            return back()->with([
              'error' => 'Item already exist in the draft!'
            ]);
            // return response()->json([
            //     'status' => 400, 
            //     'message' => 'Item already exist in the draft!',
            // ]); 
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

              $response = DB::table('purchase_request_items')
                            ->insert([
                                'project_code' => $project_code,
                                'item_id' => $id,
                                'quantity' => $quantityToPR,
                                'specification' => $specification,
                                'file_name' => $file_name.'.'.$extension,
                                'created_at' =>  Carbon::now()
                            ]);
              if($response){
                return back()->with([
                  'success' => 'Item added successfully!'
                ]);
                // return response()->json([
                //   'status' => 200, 
                //   'message' => 'Item Added Successfully!',
                // ]); 
              }else{
                return back()->with([
                  'error' => 'Failed!'
                ]);
                // return response()->json([
                //   'status' => 400, 
                //   'message' => 'Failed!',
                // ]);    
              }
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

  public function getItems(Request $request){
    try {
      // dd($request->all()); 
      $project_code = $this->aes->decrypt($request->project_code);

      // $department_id = session('department_id');
      $items = DB::table('ppmps')
                    ->select('id','item_name','quantity')
                    ->where('project_code','=',$project_code)
                    ->where('mode_of_procurement','!=',33)
                    // ->where('quantity','!=',0)
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
                          ->get();
      
      $quantity = 0;
      for($i=0; $i < count($quantityFromPRI); $i++){
                $quantity += $quantityFromPRI[$i]->quantity;
      }

      // $department_id = session('department_id');
      $item = DB::table('ppmps')
                    ->select('quantity','item_name')
                    ->where('project_code','=',$project_code)
                    ->where('id','=',$id)
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
      $purchaseRequestCount = DB::table('purchase_request as pr')
                              ->select('pr.*')
                              ->get();
      $purchaseRequestCounts =  str_pad(count($purchaseRequestCount)+1,4,"0",STR_PAD_LEFT);
      $pr_no = $date.'-'.$purchaseRequestCounts;

      $purchaseRequest = DB::table('purchase_request')
                        ->insert([
                          'department_id' => $department_id,
                          'pr_no' => $pr_no,
                          'campus' => $campus,
                          'fund_source_id' => $fund_source,
                          'purpose' => $purpose,
                          'printed_name' => $employee,
                          'designation' => $designation,
                          'created_at' =>  Carbon::now()
                      ]);
      DB::table('purchase_request_items')
                  ->where('project_code', $project_code)
                  ->where('pr_no', 0)
                  ->update([
                    'pr_no' => $pr_no
                  ]);

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

    $purchase_request = DB::table("purchase_request as pr")
                        ->select("pr.*","fs.fund_source","d.department_name","u.name")
                        ->join("fund_sources as fs","pr.fund_source_id","fs.id")
                        ->join("departments as d","pr.department_id","d.id")
                        ->join("users as u","pr.printed_name","u.id")
                        ->where("pr.id",$id)
                        ->get();

    return view('pages.department.view_status_page',compact('purchase_request'),  [
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
    // $date = Carbon::now()->format('m/d/Y');

    $purchase_request = DB::table("purchase_request as pr")
                          ->select("pr.*","fs.fund_source","d.department_name","u.name")
                          ->join("fund_sources as fs","pr.fund_source_id","fs.id")
                          ->join("departments as d","pr.department_id","d.id")
                          ->join("users as u","pr.printed_name","u.id")
                          ->where("pr.id",$id)
                          ->get();

    $pr_no = '';
    foreach($purchase_request as $data){
      $pr_no = $data->pr_no;
    }
                    // dd($pr_no);
    $itemsForPR = DB::table("purchase_request_items as pri")
              ->select('pri.*','p.unit_of_measurement','p.item_description','p.unit_price')
              ->join('ppmps as p','pri.item_id','p.id')
              ->where('pri.pr_no',$pr_no)
              ->whereNull('pri.deleted_at')
              ->get();
              // dd($itemsForPR);

          

    return view('pages.department.view_pr_page',compact('purchase_request','itemsForPR','id'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]); 
  }

  public function remove_item(Request $request){
    dd($request->all());
    $id = (new AESCipher())->decrypt($request->id);
    $item = DB::table('purchase_request_items')
              ->select('file_name')
              ->where('id',$id)
              ->where('pr_no',0)
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
    
    $response = DB::table('purchase_request_items')
            ->where('id',$id)
            ->delete();
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
                          ->select("pr.*","fs.fund_source","d.department_name","u.name")
                          ->join("fund_sources as fs","pr.fund_source_id","fs.id")
                          ->join("departments as d","pr.department_id","d.id")
                          ->join("users as u","pr.printed_name","u.id")
                          ->where("pr.id",$id)
                          ->get();

    $pr_no = '';
    foreach($purchase_request as $data){
      $pr_no = $data->pr_no;
    }
                    // dd($pr_no);
    $itemsForPR = DB::table("purchase_request_items as pri")
              ->select('pri.*','p.unit_of_measurement','p.item_description','p.unit_price')
              ->join('ppmps as p','pri.item_id','p.id')
              ->where('pri.pr_no',$pr_no)
              ->whereNull('pri.deleted_at')
              ->get();
              // dd($itemsForPR);

          

    return view('pages.department.print_pr',compact('purchase_request','itemsForPR','date','id'), [
                // 'pageConfigs'=>$pageConfigs,
                // 'breadcrumbs'=>$breadcrumbs,
                // 'error' => $error,
            ]); 
}

# Torrexx Additionals
    /**
     * ! Get item from purchase request items
     * ? KEY while it's still on draft
     * ? TODO get item by id from purchase request table
     */
      // public function get_item(Request $request) {
      //   try {
      //     $response = \DB::table('purchase_request_items')
      //       ->join('items', 'items.id', 'purchase_request_items.item_id')
      //       ->where('purchase_request_items.id', (new AESCipher)->decrypt($request->id))
      //       ->where('purchase_request_items.pr_no', 0) 
      //       ->get([
      //           'purchase_request_items.*',
      //           'items.item_name'
      //       ]);

      //     if(count($response) > 0) {
      //       return [
      //         'status'  => 200,
      //         'data'  => $response
      //       ];
      //     }

      //     return [
      //       'status'  => 400,
      //       'message'  => 'Data not Found!'
      //     ];
      //   } catch (\Throwable $th) {
      //       // return view('pages.error-500');
      //       throw $th;
      //   }
      // }
    /**
      * ! Update Purchase Request
      * ? KEY while it's still on draft status
      */
      // public function update_purchase_request(Request $request) {
      //   try {
      //       $file = $request->file('file');
      //       $extension = $request->file('file')->getClientOriginalExtension();
      //       $is_valid = false;
      //       # validate extension
      //           $allowed_extensions = ['pdf', 'jpeg', 'jpg', 'png'];
      //           for ($i = 0; $i < count($allowed_extensions) ; $i++) { 
      //             if($allowed_extensions[$i] == $extension) {
      //                   $is_valid = true;
      //             }
      //           }
      //           if($is_valid == false) {
      //               return back()->with([
      //                   'error' => 'Invalid file format!'
      //               ]);
      //           }

      //       $id = $request->item;
      //       $quantityToPR = $request->quantity;
      //       $specification = $request->specification;
      //       $project_code = $this->aes->decrypt($request->project_code);

      //       $quantityFromPPMP = DB::table("ppmps")
      //                 ->select('quantity')
      //                 ->where('project_code',$project_code)
      //                 ->where('id',$id)
      //                 ->get();
      //       $quantityFromPRI = DB::table("purchase_request_items")
      //                 ->select('quantity')
      //                 ->where('project_code',$project_code)
      //                 ->where('item_id',$id)
      //                 ->get();
            
      //       $quantity = $quantityToPR;
      //       for($i=0; $i < count($quantityFromPRI); $i++){
      //                 $quantity += $quantityFromPRI[$i]->quantity;
      //       }
            
      //       if($quantityFromPPMP[0]->quantity < $quantity){
      //         return back()->with([
      //           'error' => 'The quantity exceeds the remaining item(s)!'
      //         ]);
      //         return response()->json([
      //           'status' => 400, 
      //           'message' => 'The quantity exceeds the remaining item(s)!',
      //         ]);  
      //       }else{
      //           $itemCheck = DB::table('purchase_request_items')
      //                         ->select('*')
      //                         ->where('project_code',$project_code)
      //                         ->where('item_id',$id)
      //                         ->where('pr_no',0)
      //                         ->count();
      //           if($itemCheck == 1){
      //             return back()->with([
      //               'error' => 'Item already exist in the draft!'
      //             ]);
                
      //           }else{
      //               $item = DB::table('ppmps')
      //                     ->select('item_name')
      //                     ->where('id',$id)
      //                     ->get();
      //               $item_name = '';
      //               foreach($item as $data){
      //                 $item_name = $data->item_name;
      //               }

      //               $file_name = $item_name.'-'.time();
      //               $destination_path = env('APP_NAME').'\\purchase_request\\item_upload\\';
      //               if (!Storage::exists($destination_path)) {
      //                 Storage::makeDirectory($destination_path);
      //               }
      //               $file->storeAs($destination_path, $file_name.'.'.$extension);
      //               $file->move('storage/'. $destination_path, $file_name.'.'.$extension); 

      //               $response = DB::table('purchase_request_items')
      //                             ->where('id', $request->id)
      //                             ->update([
      //                                 'project_code' => $project_code,
      //                                 'item_id' => $id,
      //                                 'quantity' => $quantityToPR,
      //                                 'specification' => $specification,
      //                                 'file_name' => $file_name.'.'.$extension,
      //                                 'updated_at' =>  Carbon::now()
      //                             ]);
      //               if($response){
      //                 return back()->with([
      //                   'success' => 'Item Updated successfully!'
      //                 ]);
                    
      //               }else{
      //                 return back()->with([
      //                   'error' => 'Failed!'
      //                 ]);
      //               }
      //           }
      //       }
      //   } catch (\Throwable $th) {
      //       //throw $th;
      //       return view('pages.error-500');
      //   }
      // }
    
    
# END
}
