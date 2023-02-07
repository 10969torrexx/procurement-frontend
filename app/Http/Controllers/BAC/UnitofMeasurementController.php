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

class UnitofMeasurementController extends Controller
{
  public function index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Unit of Measurement"]
    ];

    $unitofmeasurement = DB::table("unit_of_measurements")
              // ->where("campus", session('campus'))
              ->whereNull("deleted_at")
              ->get();

    return view('pages.bac.add-unit-of-measurement',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    [
      'data1' => $unitofmeasurement,
    ] 
    );
  }
/*
        $unitofmeasurement =  Http::withToken(session('token'))->post(env('APP_API'). "/api/unit-of-measurement/index",[
          'campus' => session('campus')
        ])->json();
        // dd($unitofmeasurement);
        return view('pages.bac.add-unit-of-measurement',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
        [
          // 'data' => $unitofmeasurement['data'],
          'data' => $unitofmeasurement['data'],
          'data1' => $unitofmeasurement['data1'],
        ] 
        );
      }
*/

  public function store(Request $request){
    // dd( $request -> all()); 
    $unit_of_measurement = DB::table("unit_of_measurements")
        ->where('unit_of_measurement',$request->unitofmeasurement)
        ->where("campus", session('campus'))
        ->whereNull("deleted_at")
        ->get();

    if(count($unit_of_measurement) > 0){
        return response()->json([
        'status' => 400, 
        'message' => 'Item Already Exist!.',
          ]);    
    }
    else{
        DB::table("unit_of_measurements")
        ->insert([
        'unit_of_measurement'=> $request->unitofmeasurement,
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
                  'Added UnitofMeasurements: '. $request->unitofmeasurement,
                  'Added',
                  $request->ip(),
              );
          # end 

        return response()->json([
          'status' => 200, 
        ]);    
    } 
  }

  public function show(Request $request){
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $id = $aes->decrypt($request->id);
    // $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/category/show/".$id1,[
    // 'id' => $id1,
    // ])->json();

    $response = DB::table("unit_of_measurements")
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
    // $unit_of_measurement = $request->unit_of_measurement;
    // $id = $request->id;
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $id = $aes->decrypt($request->id);

    $check = DB::table("unit_of_measurements")
                ->where('unit_of_measurement',$request->unit_of_measurement )
                ->get();
    if(count($check) > 0 )
    {
      return response()->json([
        'status' => 400, 
        'message' => 'error',
      ]); 

    }else{
      $unit = DB::table("unit_of_measurements")
              ->where('id',$id)
              ->update([
                'unit_of_measurement' => $request->unit_of_measurement,
                'name'     =>session('name'),
                'updated_at'=> Carbon::now(),
              ]);

              # this will created history_log
                    (new HistoryLogController)->store(
                      session('department_id'),
                      session('employee_id'),
                      session('campus'),
                      $id,
                      'Update UnitofMeasurements: '. $request->unit_of_measurement,
                      'update',
                      $request->ip(),
                  );
              # end 
      if($unit){
        return response()->json([
          'status' => 200, 
          'message' => 'Updated!.',
        ]); 
      }

    };
  }
  public function delete(Request $request){
    // $id = $request->id;
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $id = $aes->decrypt($request->id);
    $unit = DB::table("unit_of_measurements")
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
                          'Delete UnitofMeasurements ',
                          'Delete',
                          $request->ip(),
                      );
                  # end 

    if( $unit)
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

