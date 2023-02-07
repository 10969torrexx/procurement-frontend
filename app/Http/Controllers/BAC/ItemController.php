<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
Use Carbon\Carbon;
use App\Http\Controllers\HistoryLogController;

class ItemController extends Controller
{
  public function additems(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Items"]
    ];

    // $item =  Http::withToken(session('token'))->get(env('APP_API'). "/api/item/index")->json();
    $category = DB::table("categories")
              // ->where("campus", session('campus'))
              ->whereNull("deleted_at") 
              ->get();

    $item = DB::table("items as i")
              ->select("i.*","m.id as mid", "m.mode_of_procurement")
              ->join("mode_of_procurement as m","m.id","=", "i.mode_of_procurement_id" )
              ->whereNull("i.deleted_at")
              ->get();

    $mode = DB::table("mode_of_procurement")
              ->whereNull("deleted_at")
              ->orderBy("mode_of_procurement","ASC")
              ->get();      

    $mode2 = DB::table("mode_of_procurement")
              ->whereNull("deleted_at")
              ->orderBy("mode_of_procurement","ASC")
              ->get();
    return view('pages.bac.add-items',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],compact('category', 'item','mode','mode2')
    );
  }

  public function store(Request $request){
    // dd( $request-> all()); 
    $item = $request->item_name;
    $mode_of_procurement = $request->mode_of_procurement;
    $item_category = $request->item_category;
    $app_type = $request->app_type;

    $check = DB::table("items")
          ->where('item_name',$item)
          ->whereNull('deleted_at')
          ->get();

    $mode = DB::table("mode_of_procurement")
          ->where("id",$mode_of_procurement)
          ->whereNull("deleted_at")
          ->orderBy("mode_of_procurement","ASC")
          ->get();

      $procurement = "";      
      foreach($mode as $procure){
        $procurement = $procure->mode_of_procurement;
      }     

    if(count($check) > 0)
    {
        return response()->json([
        'status' => 400, 
        'message' => 'Item Already Exist!.',
    ]);    
    }
    else{
      if($procurement == "Public Bidding" ){
        DB::table("items")
        ->insert([
            'item_name'=>  $item,
            'item_category' => $item_category,
            'app_type' => $app_type,
            'mode_of_procurement_id' => $mode_of_procurement,
            'public_bidding' => 1,
            'campus'=>  session('campus'),
            'name'=>  session('name'),
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
          
        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'Added Item: '. $item,
                'Added',
                $request->ip(),
            );
        # end 
        return response()->json([
                    'status' => 200, 
                    'message' => 'Save Succesfully!.',
                ]); 
      }else{
        DB::table("items")
        ->insert([
            'item_name'=>  $item,
            'item_category' => $item_category,
            'app_type' => $app_type,
            'mode_of_procurement_id' => $mode_of_procurement,
            'public_bidding' => 0,
            'campus'=>  session('campus'),
            'name'=>  session('name'),
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
          
        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'Added Item: '. $item,
                'Added',
                $request->ip(),
            );
        # end 
        return response()->json([
                    'status' => 200, 
                    'message' => 'Save Succesfully!.',
                ]); 
      }
    }
  }

  public function show(Request $request){
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $id = $aes->decrypt($request->id);
    $item = DB::table("items as i")
          ->select("i.*","m.id as mid", "m.mode_of_procurement")
          ->join("mode_of_procurement as m","m.id","=", "i.mode_of_procurement_id" )
          ->where('i.id',$id)
          ->get();
    if(count($item) > 0)
    {
        return response()->json([
        'status' => 200, 
        'data'=>$item,
        'id'=>$aes->encrypt($id),
        ]);    
    }
    else{
        return response()->json([
                'status' => 400, 
                'message' => 'Does not exist!.',
        ]); 
    }
  }

  public function update(Request $request){
    try{
      // dd($request->all());
        $aes = new AESCipher();
        $id = $aes->decrypt($request->id);
        $mode_of_procurement =  $request->mode_of_procurement;
        // $public_bidding =  $request->public_bidding;
        $item = DB::table("items")
                    ->where("id",$id)
                    ->whereNull('deleted_at')
                    ->get();
                    // dd($item);
        $mode = DB::table("mode_of_procurement")
                ->where("id",$mode_of_procurement)
                ->whereNull("deleted_at")
                ->orderBy("mode_of_procurement","ASC")
                ->get();

      $procurement = "";      
      foreach($mode as $procure){
        $procurement = $procure->mode_of_procurement;
      } 

        foreach($item as $items){
          if($items->item_name != $request->item_name){
            if($procurement == "Public Bidding"){
              $save = DB::table("items")
                      ->where("id",$id)
                      ->update([
                          'item_name' => $request->item_name,
                          'name' => session('name'),
                          'item_category' => $request->item_category,
                          'app_type' => $request->app_type,
                          'mode_of_procurement_id' =>  $mode_of_procurement,
                          'public_bidding' =>  1,
                          'updated_at'=> Carbon::now(),
                      ]);
            }else{
              $save = DB::table("items")
                      ->where("id",$id)
                      ->update([
                          'item_name' => $request->item_name,
                          'name' => session('name'),
                          'item_category' => $request->item_category,
                          'app_type' => $request->app_type,
                          'mode_of_procurement_id' =>  $mode_of_procurement,
                          'public_bidding' =>  0,
                          'updated_at'=> Carbon::now(),
                      ]);
            }
            # this will created history_log
                  (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    $id,
                    'Update Item: '. $request->item_name,
                    'Update',
                    $request->ip(),
                );
            # end 
              if($save){
                return response()->json([
                  'status' => 200, 
                  // 'data' => $supplier,
              ]); 
              }else{
                return response()->json([
                  'status' => 400, 
                  'message' => 'Error!.',
                ]); 
              }
          }else if($items->item_name == $request->item_name){
            if($items->app_type != $request->app_type || $items->item_category != $request->item_category || $items->mode_of_procurement_id != $mode_of_procurement|| $items->public_bidding != $public_bidding){
              if($procurement == "Public Bidding"){
                $save = DB::table("items")
                        ->where("id",$id)
                        ->update([
                            'item_name' => $request->item_name,
                            'name' => session('name'),
                            'item_category' => $request->item_category,
                            'app_type' => $request->app_type,
                            'mode_of_procurement_id' =>  $mode_of_procurement,
                            'public_bidding' =>  1,
                            'updated_at'=> Carbon::now(),
                        ]);
              }else{
                $save = DB::table("items")
                        ->where("id",$id)
                        ->update([
                            'item_name' => $request->item_name,
                            'name' => session('name'),
                            'item_category' => $request->item_category,
                            'app_type' => $request->app_type,
                            'mode_of_procurement_id' =>  $mode_of_procurement,
                            'public_bidding' =>  0,
                            'updated_at'=> Carbon::now(),
                        ]);
              }
              # this will created history_log
                    (new HistoryLogController)->store(
                      session('department_id'),
                      session('employee_id'),
                      session('campus'),
                      $id,
                      'Update Item: '. $request->item_name,
                      'Update',
                      $request->ip(),
                  );
              # end 
              if($save){
                return response()->json([
                  'status' => 200, 
                  // 'data' => $supplier,
              ]); 
              }else{
                return response()->json([
                  'status' => 400, 
                  'message' => 'Error!.',
                ]); 
              }
            }else{
              return response()->json([
                'status' => 400, 
                'message' => 'Error!.',
              ]);
            }
          }
          
        }
      }catch (\Throwable $th) {
          return response()->json([
              'status' => 600,
              'message'   => $th
          ]);
      }
  }

  public function delete(Request $request){
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $id = $aes->decrypt($request->id);
  
    $response = DB::table("items")
        ->where("id",$id)
        ->update([
          'deleted_at'=> Carbon::now(),
      ]);
      # this will created history_log
            (new HistoryLogController)->store(
              session('department_id'),
              session('employee_id'),
              session('campus'),
              $id,
              'Delete Item',
              'Delete',
              $request->ip(),
          );
      # end 
    if($response){
      return response()->json([
      'status' => 200, 
      ]);     
    }else{
      return response()->json([
        'status' => 400, 
      ]);   
    }
  }
}
