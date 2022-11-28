<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Unit of Measurement"]
        ];
        
        $unitofmeasurement =  Http::withToken(session('token'))->post(env('APP_API'). "/api/unit-of-measurement/index"[
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

      public function store(Request $request){
        // dd( $request -> all()); 
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $item_id = $aes->decrypt($request->item_id);
        $unitofmeasurement = $request->unitofmeasurement;
        $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/unit-of-measurement/store",[
        'campus'=> session('campus'),
        'name'=> session('name'),
        'item_id' => $item_id,
        'unit_of_measurement' => $unitofmeasurement,
      ])->json();
        // dd( $response); 
      if($response) 
      {
        if($response['status'] == 200){
        // return redirect('/superadmin/items')->with('success', 'Added Successfully!!');
        return response()->json([
          'status' => 200, 
        ]);    
        }

        if($response['status'] == 400){
          return response()->json([
          'status' => 400, 
        ]); 
          // return redirect('/superadmin/items')->with('error', 'Item Already Exist!');
          }
      }
      }

      public function show(Request $request){
        $id = $request->id;
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id1 = $aes->decrypt($id);
        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/unit/show/".$id1,[
        'id' => $id1,
      ])->json();
            return $response; 
      }

      public function update(Request $request){
        $unit_of_measurement = $request->unit_of_measurement;
        $id = $request->id;
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id1 = $aes->decrypt($id);
        $response = Http::withToken(session('token'))->put(env('APP_API'). "/api/unit/update/".$id1,[
        'id' => $id1,
        'unit_of_measurement' => $unit_of_measurement,
      ])->json();
      return $response;
          // dd($response);
      }
      public function delete(Request $request){
        $id = $request->id;
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id1 = $aes->decrypt($id);
        // dd($id1);
        $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/unit/delete/".$id1,[
        'id' => $id1,
      ])->json();
      if($response) 
      {
        // dd( $response);
        if($response['status'] == 200){
        return response()->json([
          'status' => 200, 
          ]);     
        }
        if($response['status'] == 400){
          return response()->json([
            'status' => 400, 
          ]);    
        }else{
          return response()->json([
            'status' => 500, 
          ]);   
        }
      }
      // dd($response);
      }
}

