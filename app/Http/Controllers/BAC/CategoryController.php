<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\HistoryLogController;

class CategoryController extends Controller
{
    public function index(){
      $pageConfigs = ['pageHeader' => true];
      $breadcrumbs = [
        ["link" => "/", "name" => "Home"],["name" => "Category"]
      ];

      $category = DB::table("categories")
                // ->where("campus", session('campus'))
                ->whereNull("deleted_at")
                ->get();

      return view('pages.bac.add-category',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
      [
        'data' => $category,
      ] 
      );
    }
    
    public function store(Request $request){
      $aes = new AESCipher();
      $global = new GlobalDeclare();
      $cat = DB::table("categories")
            ->where('category',$request->category)
            ->where("campus", session('campus'))
            ->whereNull("deleted_at")
                  ->get();

      if(count($cat) > 0){
          return response()->json([
          'status' => 400, 
          'message' => 'Item Already Exist!.',
            ]);    
      }
      else{
        DB::table("categories")
        ->insert([
        'category'=> $request->category,
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
                'Added Category',
                'ADD',
                $request->ip(),
            );
        # end 

        return response()->json([
          'status' => 200, 
        ]);    
      }     
    }

    public function show(Request $request){
      // $id = ;
      // dd($request->all());        
      
      $aes = new AESCipher();
      $global = new GlobalDeclare();
      $id = $aes->decrypt($request->id);
      // $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/category/show/".$id1,[
      // 'id' => $id1,
      // ])->json();

      $response = DB::table("categories")
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
      // $category = $request->category;
      // $id = $request->id;
      $aes = new AESCipher();
      $global = new GlobalDeclare();
      $id = $aes->decrypt($request->id);

      $check = DB::table("categories")
                  ->where('category',$request->category )
                  ->get();
      if(count($check) > 0 )
      {
        return response()->json([
          'status' => 400, 
          'message' => 'error',
        ]); 

      }else{
        $category = DB::table("categories")
                ->where('id',$id)
                ->update([
                  'category' => $request->category,
                  'name'     =>session('name'),
                  'updated_at'=> Carbon::now(),
                ]);
          
        # this will created history_log
              (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                $id,
                'Updated Category',
                'UPDATED',
                $request->ip(),
            );
        # end 

        return response()->json([
          'status' => 200, 
          'message' => 'Updated!.',
        ]);  
      }
    }

    public function delete(Request $request){
      // $id = $request->id;
      $aes = new AESCipher();
      $global = new GlobalDeclare();
      $id = $aes->decrypt($request->id);
      $cat = DB::table("categories")
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
              'Deleted Category',
              'Deleted',
              $request->ip(),
          );
      # end 

      if( $cat)
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
