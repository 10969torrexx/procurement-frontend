<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
Use Carbon\Carbon;

class ItemController extends Controller
{
    // public function additems(){
    //     $pageConfigs = ['pageHeader' => true];
    //     $breadcrumbs = [
    //       ["link" => "/", "name" => "Home"],["name" => "Items"]
    //     ];
    //     $item =  Http::withToken(session('token'))->post(env('APP_API'). "/api/item/index",[
    //       'campus' => session('campus')
    //     ])->json();
          
    //     // dd( $item);
    //     $items = $item['data'];
    //     $category =$item['data1'];
    //     $all = [$items,$category];
    //     return view('pages.bac.add-items',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
    //     [
    //       // 'data' => $item['data'],
    //       // 'data1' => $item['data1'],
    //       'data' =>[ $all]
    //     ] 
    //     );
    // }

    //   public function store(Request $request){
    //     // dd( $request-> all()); 
    //     $item = $request->item_name;
    //     $public_bidding = $request->public_bidding;
    //     $item_category = $request->item_category;
    //     $app_type = $request->app_type;
    //     $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/item/store",[
    //     'item_name' => $item,
    //     'item_category' => $item_category,
    //     'app_type' => $app_type,
    //     'public_bidding' => $public_bidding,
    //     'campus'=> session('campus'),
    //     'name'=> session('name'),
    //     ])->json();
    //     if($response) 
    //     {
    //       if($response['status'] == 200){
    //       return response()->json([
    //         'status' => 200, 
    //     ]);    
    //       }

    //       if($response['status'] == 400){
    //         return response()->json([
    //         'status' => 400, 
    //     ]); 
    //         }
    //     }
    //     // dd($response);
    //   }

    //   public function show(Request $request){
    //     $id = $request->id;
    //     $aes = new AESCipher();
    //     $global = new GlobalDeclare();
    //     $id1 = $aes->decrypt($id);
    //     $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/item/show/".$id1,[
    //     'id' => $id1,
    //     ])->json();
    //     // dd($response);
    //         return $response; 
    //   }

    //   public function update(Request $request){
    //     // dd($request->all());
    //     $item_name = $request->item_name;
    //     $item_category = $request->item_category;
    //     $public_bidding = $request->public_bidding;
    //     $app_type = $request->app_type;
    //     $id = $request->id;
    //     $aes = new AESCipher();
    //     $global = new GlobalDeclare();
    //     $id1 = $aes->decrypt($id);
    //     // dd(session('name'));
    //     $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/item/update",[
    //     'id' => $id1,
    //     'item_name' => $item_name,
    //     'name'=> session('name'),
    //     'item_category' => $item_category,
    //     'public_bidding' => $public_bidding,
    //     'app_type' => $app_type,
    //     ])->json();
    //         // dd($response);
    //     return $response;
    //   }

    //   public function delete(Request $request){
    //     $id = $request->id;
    //     $aes = new AESCipher();
    //     $global = new GlobalDeclare();
    //     $id1 = $aes->decrypt($id);
    //     $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/item/delete/".$id1,[
    //     'id' => $id1,
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

  public function additems(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Items"]
    ];

    // $item =  Http::withToken(session('token'))->get(env('APP_API'). "/api/item/index")->json();
    $category = DB::table("categories")
              ->where("campus", session('campus'))
              ->whereNull("deleted_at")
              ->get();

    $item = DB::table("items")
              ->where("campus", session('campus'))
              ->whereNull("deleted_at")
              ->get();

    // $items = $item['data'];
    // $category =$item['data1'];
    $all = [$item,$category];
    // dd( $all);
    return view('pages.bac.add-items',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],compact('category', 'item')
    );
  }

  public function store(Request $request){
    // dd( $request-> all()); 
    $item = $request->item_name;
    $public_bidding = $request->public_bidding;
    $item_category = $request->item_category;
    $app_type = $request->app_type;

    $check = DB::table("items")
          ->where('item_name',$item)
          ->where('campus',session('campus'))
          ->whereNull('deleted_at')
          ->get();
    if(count($check) > 0)
    {
        return response()->json([
        'status' => 400, 
        'message' => 'Item Already Exist!.',
    ]);    
    }
    else{
        DB::table("items")
        ->insert([
            'item_name'=>  $item,
            'item_category' => $item_category,
            'app_type' => $app_type,
            'public_bidding' => $public_bidding,
            'campus'=>  session('campus'),
            'name'=>  session('name'),
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
        return response()->json([
                    'status' => 200, 
                    'message' => 'Save Succesfully!.',
                ]); 
    }
  }

  public function show(Request $request){
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $id = $aes->decrypt($request->id);
    $item = DB::table("items")
          ->where('id',$id)
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
        $aes = new AESCipher();
        $id = $aes->decrypt($request->id);
        $public_bidding =  intval($request->public_bidding);
        $item = DB::table("items")
                    ->where("id",$id)
                    ->whereNull('deleted_at')
                    ->get();
                    // dd($items);

        foreach($item as $items){
          if($items->item_name != $request->item_name){
              DB::table("items")
                      ->where("id",$id)
                      ->update([
                          'item_name' => $request->item_name,
                          'name' => session('name'),
                          'item_category' => $request->item_category,
                          'app_type' => $request->app_type,
                          'public_bidding' =>  $public_bidding,
                          'updated_at'=> Carbon::now(),
                      ]);
              return response()->json([
                  'status' => 200, 
                  // 'data' => $supplier,
              ]); 

          }else if($items->item_name == $request->item_name && ($items->app_type != $request->app_type || $items->item_category != $request->item_category || $items->public_bidding != $public_bidding) ){
            DB::table("items")
              ->where("id",$id)
              ->update([
                  'item_name' => $request->item_name,
                  'name' => session('name'),
                  'item_category' => $request->item_category,
                  'app_type' => $request->app_type,
                  'public_bidding' =>  $public_bidding,
                  'updated_at'=> Carbon::now(),
              ]);
              return response()->json([
                  'status' => 200, 
                  // 'data' => $supplier,
              ]); 
          }else  {
            // dd("true");
              return response()->json([
                'status' => 400, 
                'message' => 'Error!.',
              ]); 
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
