<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Category"]
        ];
        
        $category =  Http::withToken(session('token'))->get(env('APP_API'). "/api/category/index")->json();
        // dd($category);
        return view('pages.bac.add-category',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
        [
          // // 'data' => $unitofmeasurement['data'],
          // 'data' => $unitofmeasurement['data'],
          'data' => $category['data'],
        ] 
        );
      }
    
      public function store(Request $request){
        // dd( $request -> all()); 
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        // $item_id = $aes->decrypt($request->item_id);
        $category = $request->category;
        //change campus later
        
      //   $response = DB::table("categories")
      //         ->where('category',$category)
      //         ->whereNull("deleted_at")
      //         ->where("campus", 1)
      //         ->first();

      //   $item_a = Category::where('category',$category)->get();
      //   if(count($item_a) > 0)
      //   {
      //       return response()->json([
      //           'status' => 400, 
      //           'message' => 'Item Already Exist!.',
      //       ]);    
      //   }
      //   else{
      //       Category::create([
      //       'category'=> $category,
      //       'campus'=> $campus,
      //       'name'=> $name
      //   ]);
      //       return response()->json([
      //       'status' => 200, 
      //       'message' => 'Save Succesfully!.',
      //   ]); 
      // }
      $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/category/store",[
        'campus'=> 1,
        'name'=> "admin",
        'category' => $category,
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
        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/category/show/".$id1,[
        'id' => $id1,
      ])->json();
            return $response; 
      }
    
      public function update(Request $request){
        $category = $request->category;
        $id = $request->id;
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id1 = $aes->decrypt($id);
        $response = Http::withToken(session('token'))->put(env('APP_API'). "/api/category/update/".$id1,[
        'id' => $id1,
        'category' => $category,
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
        $response = Http::withToken(session('token'))->delete(env('APP_API'). "/api/category/delete/".$id1,[
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
