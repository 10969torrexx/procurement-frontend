<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Category"]
        ];
        
        // $category =  Http::withToken(session('token'))->post(env('APP_API'). "/api/category/index",[
        //   'campus' => session('campus')
        // ])->json();
        // dd(session('campus'));
        $category = DB::table("categories")
                  ->where("campus", session('campus'))
                  ->whereNull("deleted_at")
                  ->get();
        // if(empty($category)){
        //   return view('pages.bac.add-category',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]
        //   // [
        //   //   // // 'data' => $unitofmeasurement['data'],
        //   //   // 'data' => $unitofmeasurement['data'],
        //   //   'data' => 
        //   // ] 
        //   );
        // }
        // else{
          return view('pages.bac.add-category',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
          [
            // // 'data' => $unitofmeasurement['data'],
            // 'data' => $unitofmeasurement['data'],
            'data' => $category,
          ] 
          );
        // }
        
      }
    
      public function store(Request $request){
        // dd( $request -> all()); 
        // $aes = new AESCipher();
        // $global = new GlobalDeclare();
        // $item_id = $aes->decrypt($request->item_id);
        // $category = $request->category;

        $category = $request->category;
        // $campus = $request->campus;
        $name = $request->name;

        // $item_a = Category::where('category',$category)->get();
        
        $item_a = DB::table("categories")
                ->where("category",$category)
                ->where("campus",session('campus'))
                ->whereNull('deleted_at')
                ->get();
        if(count($item_a) > 0)
        {
            return response()->json([
                'status' => 400, 
                'message' => 'Item Already Exist!.',
            ]);    
        }
        else{
          
        $response = DB::table("categories")
                  ->insert([
                    'category'=> $category,
                    'campus'=> session('campus'),
                    'name'=> session('name'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                  ]);

            return response()->json([
            'status' => 200, 
            'message' => 'Save Succesfully!.',
        ]); 
        }
      // $response = Http::withToken(session('token'))->post(env('APP_API'). "/api/category/store",[
      //   'campus'=> session('campus'),
      //   'name'=> "admin",
      //   'category' => $category,
      //   ])->json();
        // dd( $response); 
      
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
