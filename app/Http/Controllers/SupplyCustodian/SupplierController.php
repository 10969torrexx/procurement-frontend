<?php

namespace App\Http\Controllers\SupplyCustodian;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $global;
    protected $aes;
    public function __construct(){
        $this->aes = new AESCipher();
        $this->global = new GlobalDeclare();
    }
    
    public function index()
    {

        // $suppliers = Supplier::all();
        $suppliers = DB::table("store")
                    ->whereNull("deleted_at")
                    ->where("campus", session('campus'))
                    ->get();
        

        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["link" => "#", "name" => "Suppliers"]
        ];
        
        return view('pages.supplycustodian.setup.supplier.index',compact('suppliers'),[
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
        ]);
    }

    public function add_supplier(Request $request){
            // dd( session('campus'));
        try{
            $supplier = DB::table("store")
                        ->insert([
                            'Description' => $request->Description,
                            'Address'     => $request->Address,
                            'ContactNo'   => $request->ContactNumber,
                            'SupplierName'=> $request->SupplierName,
                            'campus'      => session('campus'),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

            if($supplier){
                return response()->json([
                    'status' => 200, 
                    'message' => 'Added',
                ]); 

            }else{
                return response()->json([
                    'status' => 400, 
                    'message' => 'Error!.',
                ]); 
            }

        }catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message'   => $th
            ]);
        }
    }

    public function edit_supplier(Request $request){
        // dd($request->all());
        try{
            // $id = $request->id;
            $aes = new AESCipher();
            $global = new GlobalDeclare();
            // $id1 = $aes->decrypt($id);

            $supplier = DB::table("store")
                        ->where("id",$aes->decrypt($request->id))
                        ->get();

            if($supplier){
                return response()->json([
                    'status' => 200, 
                    'data' => $supplier,
                ]); 

            }else{
                return response()->json([
                    'status' => 400, 
                    'message' => 'Error!.',
                ]); 
            }

        }catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message'   => $th
            ]);
        }
    }

    public function submitedit_supplier(Request $request){
        // dd($request->all());
        try{
            // $id = $request->id;
            $aes = new AESCipher();
            $global = new GlobalDeclare();
            // $id1 = $aes->decrypt($id);

            $supplier = DB::table("store")
                        ->where("id",$request->id)
                        ->get();
            // dd(count($supplier) );

            foreach($supplier as $supplier){
                if($supplier->SupplierName != $request->SupplierName){
                    DB::table("store")
                            ->where("id",$request->id)
                            ->update([
                                'SupplierName' => $request->SupplierName,
                                'Address' => $request->Address,
                                'ContactNo' => $request->ContactNumber,
                                'Description' => $request->Description,
                                'update_at' => Carbon::now(),
                            ]);
                    return response()->json([
                        'status' => 200, 
                        // 'data' => $supplier,
                    ]); 
    
                }else if($supplier->SupplierName == $request->SupplierName && ($supplier->Address != $request->Address || $supplier->ContactNo != $request->ContactNumber || $supplier->Description != $request->Description) ){
                    DB::table("store")
                            ->where("id",$request->id)
                            ->update([
                                'SupplierName' => $request->SupplierName,
                                'Address' => $request->Address,
                                'ContactNo' => $request->ContactNumber,
                                'Description' => $request->Description,
                                'update_at' => Carbon::now(),
                            ]);
                    return response()->json([
                        'status' => 200, 
                        // 'data' => $supplier,
                    ]); 
                }else  {
                    return response()->json([
                        'status' => 400, 
                        'message' => 'Error!.',
                    ]); 
                }
    
            }
        }catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message'   => $th
            ]);
        }
    }

    public function delete_supplier(Request $request){
        // dd($request->all());
        try{
            // $id = $request->id;
            $aes = new AESCipher();
            $global = new GlobalDeclare();
            // $id1 = $aes->decrypt($id);

            $supplier = DB::table("store")
                        ->where("id",$aes->decrypt($request->id))
                        ->update(['deleted_at' => Carbon::now()]);

            if($supplier){
                return response()->json([
                    'status' => 200, 
                    'data' => $supplier,
                ]); 

            }else{
                return response()->json([
                    'status' => 400, 
                    'message' => 'Error!.',
                ]); 
            }

        }catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message'   => $th
            ]);
        }
    }


}
