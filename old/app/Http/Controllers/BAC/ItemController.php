<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ItemController extends Controller
{
    public function additems(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Items"]
        ];
        $item =  Http::withToken(session('token'))->get(env('APP_API'). "/api/item/index")->json();
          
        // dd( $item);
        $items = $item['data'];
        $category =$item['data1'];
        $all = [$items,$category];
        return view('pages.bac.add-items',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
        [
          // 'data' => $item['data'],
          // 'data1' => $item['data1'],
          'data' =>[ $all]
        ] 
        );
      }

      public function store(Request $request){
        // dd( $request-> all()); 
        $item = $request->item_name;
        $public_bidding = $request->public_bidding;
        $item_category = $request->item_category;
        $app_type = $request->app_type;
        $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/item/store",[
        'item_name' => $item,
        'item_category' => $item_category,
        'app_type' => $app_type,
        'public_bidding' => $public_bidding,
        'campus'=> session('campus'),
        'name'=> session('name'),
      ])->json();
      if($response) 
      {
        if($response['status'] == 200){
        return response()->json([
          'status' => 200, 
      ]);    
        }

        if($response['status'] == 400){
          return response()->json([
          'status' => 400, 
      ]); 
          }
      }
      // dd($response);
      }

      public function show(Request $request){
        $id = $request->id;
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id1 = $aes->decrypt($id);
        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/item/show/".$id1,[
        'id' => $id1,
      ])->json();
      // dd($response);
            return $response; 
      }

      public function update(Request $request){
        // dd($request->all());
        $item_name = $request->item_name;
        $item_category = $request->item_category;
        $public_bidding = $request->public_bidding;
        $app_type = $request->app_type;
        $id = $request->id;
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id1 = $aes->decrypt($id);
        // dd(session('name'));
        $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/item/update",[
        'id' => $id1,
        'item_name' => $item_name,
        'name'=> session('name'),
        'item_category' => $item_category,
        'public_bidding' => $public_bidding,
        'app_type' => $app_type,
      ])->json();
          // dd($response);
      return $response;
      }

      public function delete(Request $request){
        $id = $request->id;
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id1 = $aes->decrypt($id);
        $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/item/delete/".$id1,[
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
