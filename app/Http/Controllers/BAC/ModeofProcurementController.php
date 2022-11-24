<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ModeofProcurementController extends Controller
{
  public function index(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Mode of Procurement"]
    ];
    
    $modeofprocurement =  Http::withToken(session('token'))->get(env('APP_API'). "/api/mode-of-procurement/index")->json();
    // dd($modeofprocurement);
    return view('pages.bac.add-mode-of-procurement',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    [
      // // 'data' => $unitofmeasurement['data'],
      // 'data' => $unitofmeasurement['data'],
      'data' => $modeofprocurement['data'],
    ] 
    );
  }

  public function store(Request $request){
    // dd( $request -> all()); 
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    // $item_id = $aes->decrypt($request->item_id);
    $modeofprocurement = $request->modeofprocurement;
    $abbreviation = $request->abbreviation;
    // dd($abbreviation);
    $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/mode-of-procurement/store",[
    'campus'=> session('campus'),
    'name'=> session('name'),
    'mode_of_procurement' => $modeofprocurement,
    'abbreviation' => $abbreviation,
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
    $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/procurement/show/".$id1,[
    'id' => $id1,
  ])->json();
        return $response; 
  }

  public function update(Request $request){
    $mode_of_procurement = $request->mode_of_procurement;
    $abbreviation = $request->abbreviation;
    $id = $request->id;
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $id1 = $aes->decrypt($id);
    $response = Http::withToken(session('token'))->put(env('APP_API'). "/api/procurement/update/".$id1,[
    'id' => $id1,
    'mode_of_procurement' => $mode_of_procurement,
    'abbreviation' => $abbreviation,
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
    $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/procurement/delete/".$id1,[
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
