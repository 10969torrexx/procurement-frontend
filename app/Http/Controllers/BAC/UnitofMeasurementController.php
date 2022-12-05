<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
Use Carbon\Carbon;

class UnitofMeasurementController extends Controller
{
  // public function additems(){
  //   $item =  Http::withToken(session('token'))->get(env('APP_API'). "/api/item/index")->json();
  //   return view('pages.bac.add-unit-of-measurement',
  //   [
  //     'data' => $item['data'],

  //   ] 
  //   );
  // }
    // public function index(){
    //     $pageConfigs = ['pageHeader' => true];
    //     $breadcrumbs = [
    //       ["link" => "/", "name" => "Home"],["name" => "Unit of Measurement"]
    //     ];
        

    //     $unitofmeasurement =  Http::withToken(session('token'))->post(env('APP_API'). "/api/unit-of-measurement/index"[
    //       'campus' => session('campus')
    //     ])->json();
    //     // dd($unitofmeasurement);
    //     return view('pages.bac.add-unit-of-measurement',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    //     [
    //       // 'data' => $unitofmeasurement['data'],
    //       'data' => $unitofmeasurement['data'],
    //       'data1' => $unitofmeasurement['data1'],
    //     ] 
    //     );
    // }

    //   public function store(Request $request){
    //     // dd( $request -> all()); 
    //     $aes = new AESCipher();
    //     $global = new GlobalDeclare();
    //     $item_id = $aes->decrypt($request->item_id);
    //     $unitofmeasurement = $request->unitofmeasurement;
    //     $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/unit-of-measurement/store",[
    //     'campus'=> session('campus'),
    //     'name'=> session('name'),
    //     'item_id' => $item_id,
    //     'unit_of_measurement' => $unitofmeasurement,
    //     ])->json();
    //       // dd( $response); 
    //     if($response) 
    //     {
    //     if($response['status'] == 200){
    //     // return redirect('/superadmin/items')->with('success', 'Added Successfully!!');
    //     return response()->json([
    //       'status' => 200, 
    //     ]);    
    //     }

    //     if($response['status'] == 400){
    //       return response()->json([
    //       'status' => 400, 
    //     ]); 
    //       // return redirect('/superadmin/items')->with('error', 'Item Already Exist!');
    //       }
    //     }
    //   }

    //   public function show(Request $request){
    //     $id = $request->id;
    //     $aes = new AESCipher();
    //     $global = new GlobalDeclare();
    //     $id1 = $aes->decrypt($id);
    //     $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/unit/show/".$id1,[
    //     'id' => $id1,
    //     ])->json();
    //         return $response; 
    //   }

    //   public function update(Request $request){
    //     $unit_of_measurement = $request->unit_of_measurement;
    //     $id = $request->id;
    //     $aes = new AESCipher();
    //     $global = new GlobalDeclare();
    //     $id1 = $aes->decrypt($id);
    //     $response = Http::withToken(session('token'))->put(env('APP_API'). "/api/unit/update/".$id1,[
    //     'id' => $id1,
    //     'unit_of_measurement' => $unit_of_measurement,
    //     ])->json();
    //     return $response;
    //         // dd($response);
    //   }
    //   public function delete(Request $request){
    //     $id = $request->id;
    //     $aes = new AESCipher();
    //     $global = new GlobalDeclare();
    //     $id1 = $aes->decrypt($id);
    //     // dd($id1);
    //     $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/unit/delete/".$id1,[
    //       'id' => $id1,
    //     ])->json();
    //     if($response) 
    //     {
    //       // dd( $response);
    //       if($response['status'] == 200){
    //       return response()->json([
    //         'status' => 200, 
    //         ]);     
    //       }
    //       if($response['status'] == 400){
    //         return response()->json([
    //           'status' => 400, 
    //         ]);    
    //       }else{
    //         return response()->json([
    //           'status' => 500, 
    //         ]);   
    //       }
    //     }
    //   // dd($response);
    //   }

  public function index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Unit of Measurement"]
    ];

    $unitofmeasurement = DB::table("unit_of_measurements")
              ->where("campus", session('campus'))
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
                'unit_of_measurements' => $request->unit_of_measurements,
                'name'     =>session('name'),
                'updated_at'=> Carbon::now(),
              ]);
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

