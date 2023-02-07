<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
Use Carbon\Carbon;
use App\Http\Controllers\HistoryLogController;

class ModeofProcurementController extends Controller
{
  public function index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Mode of Procurement"]
    ];
    
    // $modeofprocurement =  Http::withToken(session('token'))->get(env('APP_API'). "/api/mode-of-procurement/index")->json();
    // dd($modeofprocurement);

    $modeofprocurement = DB::table("mode_of_procurement")
                  ->whereNull("deleted_at")
                  ->get();

    return view('pages.bac.add-mode-of-procurement',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    [
      'data' => $modeofprocurement,
    ] 
    );
  }

  public function store(Request $request){
    // dd( $request -> all()); 
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    // $item_id = $aes->decrypt($request->item_id);
    // $modeofprocurement = $request->modeofprocurement;
    // $abbreviation = $request->abbreviation;
    // dd($abbreviation);
    $mode = DB::table("mode_of_procurement")
            ->where('mode_of_procurement',$request->modeofprocurement)
            ->where("campus", session('campus'))
            ->whereNull("deleted_at")
                  ->get();

    if(count($mode) > 0){
        return response()->json([
        'status' => 400, 
        'message' => 'Item Already Exist!.',
          ]);    
    }
    else{
      DB::table("mode_of_procurement")
      ->insert([
      'mode_of_procurement'=> $request->modeofprocurement,
      'abbreviation'=> $request->abbreviation,
      'campus'=> session('campus'),
      'name'=> session('name'),
      'created_at'=> Carbon::now(),
      'updated_at'=> Carbon::now(),
        ]); 

        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'Added Mode Of Procurement: '.$request->modeofprocurement,
                'Add',
                $request->ip(),
            );
        # end   

      return response()->json([
        'status' => 200, 
      ]);    
    }    
  }

  public function show(Request $request){
    // $id = $request->id;
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $id = $aes->decrypt($request->id);
    // $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/category/show/".$id1,[
    // 'id' => $id1,
    // ])->json();

    $response = DB::table("mode_of_procurement")
              -> where('id',$id)
              ->get();
    // dd($response);          
    // return $response; 
    if($response){
      return response()->json([
        'status' => 200,
        'data'   => $response,
        'id'=>$aes->encrypt($id),
        ]);     
    }else{
      return response()->json([
        'status' => 400, 
        ]); 
    }
  }

  public function update(Request $request){
      // $mode_of_procurement = $request->mode_of_procurement;
      // $abbreviation = $request->abbreviation;
      // $id = $request->id;
      $aes = new AESCipher();
      $global = new GlobalDeclare();
      $id = $aes->decrypt($request->id);

      $check = DB::table("mode_of_procurement")
                  ->where('mode_of_procurement',$request->mode_of_procurement )
                  ->get();
      if(count($check) > 0 )
      {
        return response()->json([
          'status' => 400, 
          'message' => 'error',
        ]); 

      }else{
        $MOP = DB::table("mode_of_procurement")
                ->where('id',$id)
                ->update([
                  'mode_of_procurement' => $request->mode_of_procurement,
                  'abbreviation' => $request->abbreviation,
                  'name'     =>session('name'),
                  'updated_at'=> Carbon::now(),
                ]);

                # this will created history_log
                      (new HistoryLogController)->store(
                        session('department_id'),
                        session('employee_id'),
                        session('campus'),
                        $id,
                        'Updated Mode Of Procurement: '.$request->mode_of_procurement,
                        'Updated',
                        $request->ip(),
                    );
                # end   
        

        return response()->json([
          'status' => 200, 
          'message' => 'Updated!.',
        ]);  
      }
      // dd($response);
  }
  
  public function delete(Request $request){
    $id = $request->id;
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $id = $aes->decrypt($request->id);
    $mode = DB::table("mode_of_procurement")
                  ->where('id',$id )
                  ->update([
                    'deleted_at' => Carbon::now(),
                  ]);

                  # this will created history_log
                        (new HistoryLogController)->store(
                          session('department_id'),
                          session('employee_id'),
                          session('campus'),
                          $id,
                          'Deleted Mode Of Procurement',
                          'Deleted',
                          $request->ip(),
                      );
                  # end   

    if( $mode)
      {
        return response()->json([
        'status' => 200, 
        'message' => 'Deleted!.',
    ]);    
    }
    else{
        return response()->json([
        'status' => 400, 
        'message' => 'error',
        ]); 
    }
  // dd($response);
  }
}
