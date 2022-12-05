<?php

namespace App\Http\Controllers\SupplyCustodian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use DB;
use Carbon\Carbon;
use App\Employee;
use App\PropertySub;

class ICSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        // $propertys = DB::table('item as i')
        //     ->select('i.*',DB::raw("Concat(e2.FirstName , ' ', e2.LastName) as IssuedBy"), 's.SupplierName as SupplierNameSTR')
        //     ->leftjoin('employee as e2','i.IssuedBy','=','e2.id')
        //     ->leftjoin('store as s','i.StoreName','=','s.id')
        //     ->whereNull('i.deleted_at')
        //     ->where("propertytype", "PAR")
        //     ->orderBy('DateIssued', "desc")
        //     ->get();

        // $propertysubs = DB::table('item as i')
        //     ->select("isub.*",DB::raw("Concat(e1.FirstName , ' ', e1.LastName) as EmployeeName"))
        //     ->leftjoin('items_sub as isub','i.id','=','isub.ItemID')
        //     ->leftjoin('employee as e1','isub.EmployeeID','=','e1.id')
        //     ->whereNull('isub.deleted_at')
        //     ->where("i.propertytype", "PAR")
        //     ->get();
            
        $propertys = DB::table("property as p")
            ->select('p.*','u.name','s.SupplierName')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('store as s','s.id','=','p.StoreName')
            ->whereNull('p.deleted_at')
            ->where("p.propertytype", "ICS")
            ->where("p.campus", session('campus'))
            ->orderBy('p.DateIssued', "desc")
            ->get();
            // dd($propertys );

        $supplier = DB::table('store')
            ->whereNull('deleted_at')
            ->where("campus", session('campus'))
            // ->where("campus",session('campus'))
            ->get();

        $users = DB::table("users")
            ->where("campus",session('campus'))
            ->whereNull("username")
            ->whereNull("deleted_at")
            ->get();

        $issuedby = DB::table("users")
            ->where("campus",session('campus'))
            ->whereNull("username")
            ->get();

        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["link" => "#", "name" => "Property Custodian"]
        ];
        
        return view('pages.supplycustodian.ics.index',compact('propertys'/* ,'propertysubs' */,'users','issuedby', 'supplier'),[
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
        ]);
    }

    public function assign_ics(Request $request){
        // dd($request->all());

        $check = DB::table("property")
            ->where("PONumber",$request->PONumber)
            ->where("propertytype", "ICS")
            ->where("campus", session('campus'))
            ->get();

        if(count($check) > 0)
        {
            return response()->json([
            'status' => 500, 
            // 'data' => $supplier,
            ]); 
        }else{
            $par = DB::table("property")
            ->insert([
                'PropertyNumber'        => "",
                'DateAcquired'          => $request->DateAcquired,
                'PONumber'              => $request->PONumber,
                'IssuedBy'              => $request->Issuedby,
                'DateReceived'          => $request->DateReceived,
                'DateIssued'            => $request->DateIssued,
                'PARNo'                 => $request->PONumber,
                'StoreName'             => $request->Supplier,
                'propertytype'          => $request->Type,
                'Quantity'              => $request->Quantity,
                'Unit'                  => $request->Unit,
                'ItemName'              => $request->ItemName,
                'Description'           => $request->Description,
                'UnitPrice'             => $request->UnitPrice,
                'TotalAmount'           => ($request->Quantity * $request->UnitPrice),
                'remarks'               => "",
                'Estimatedusefullife'    => $request->Estimatedusefullife,
                'FundCluster'           => $request->FundCluster,
                'EmployeeID'            => $request->EmployeeId,
                'DateTransferred'       => $request->DateAcquired,
                'campus'                => session('campus'),
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ]);
        
            if($par){
                return response()->json([
                    'status' => 200, 
                    // 'data' => $supplier,
                ]); 

            }else{
                return response()->json([
                    'status' => 400, 
                    'message' => 'Error!.',
                ]); 
            
            }
        }
    
    }

    public function finalize_ics(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        // dd($id);
        $par = DB::table("property")
            ->where("id", $id)
            ->update([
                'finalize'        => 1,
            ]);
        
        if($par){
            return response()->json([
                'status' => 200, 
                // 'data' => $supplier,
            ]); 

        }else{
            return response()->json([
                'status' => 400, 
                'message' => 'Error!.',
            ]); 
        }
    
    }

    public function delete_ics(Request $request){
        // dd($request->all());
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        // dd($id);
        $par = DB::table("property")
            ->where("id", $id)
            ->update([
                'deleted_at'     => Carbon::now(),
            ]);
        
        if($par){
            return response()->json([
                'status' => 200, 
                // 'data' => $supplier,
            ]); 

        }else{
            return response()->json([
                'status' => 400, 
                'message' => 'Error!.',
            ]); 
        }
    
    }

    public function edit_ics(Request $request){
        // dd($request->all());
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        // dd($id);
        $par = DB::table("property as p")
            ->select('p.*','u.name as EmployeeName','us.name as IssuedbyName','s.SupplierName as Storename')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('users as us','us.id','=','p.IssuedBy')
            ->join('store as s','s.id','=','p.StoreName')
            ->where("p.id", $id)
            ->get();
        
        return $par;
    }

    public function submitEdit_ics(Request $request){
        // dd($request->all());
        $par = DB::table("property")
            ->where("id",$request->id)
            ->update([
                'DateAcquired'          => $request->DateAcquired,
                'PONumber'              => $request->PONumber,
                'IssuedBy'              => $request->Issuedby,
                'DateReceived'          => $request->DateReceived,
                'DateIssued'            => $request->DateIssued,
                'PARNo'                 => $request->PONumber,
                'StoreName'             => $request->Supplier,
                'propertytype'          => $request->Type,
                'Quantity'              => $request->Quantity,
                'Unit'                  => $request->Unit,
                'ItemName'              => $request->ItemName,
                'Description'           => $request->Description,
                'UnitPrice'             => $request->UnitPrice,
                'TotalAmount'           => ($request->Quantity * $request->UnitPrice),
                'Estimatedusefullife'    => $request->Estimatedusefullife,
                'FundCluster'           => $request->FundCluster,
                'EmployeeID'            => $request->EmployeeId,
                'DateTransferred'       => $request->DateAcquired,
                'updated_at'     => Carbon::now(),
            ]);
        
        if($par){
            return response()->json([
                'status' => 200, 
                // 'data' => $supplier,
            ]); 

        }else{
            return response()->json([
                'status' => 400, 
                'message' => 'Error!.',
            ]); 
        }
    
    }

    public function additem_ics(Request $request){
        // dd($request->all());
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        // dd($id);
        $par = DB::table("property as p")
            // ->select('p.*','u.name as EmployeeName','us.name as IssuedbyName','s.SupplierName as Storename')
            // ->join('users as u','u.id','=','p.EmployeeID')
            // ->join('users as us','us.id','=','p.IssuedBy')
            // ->join('store as s','s.id','=','p.StoreName')
            ->where("p.id", $id)
            ->get();
        
        return $par;
    }

    public function submitadd_ics(Request $request){
        // dd($request->all());
        $par = DB::table("property")
            ->insert([
                'PropertyNumber'        => "",
                'DateAcquired'          => $request->DateAcquired,
                'PONumber'              => $request->PONumber,
                'IssuedBy'              => $request->Issuedby,
                'DateReceived'          => $request->DateReceived,
                'DateIssued'            => $request->DateIssued,
                'PARNo'                 => $request->PARNo,
                'StoreName'             => $request->Supplier,
                'propertytype'          => $request->Type,
                'Quantity'              => $request->Quantity,
                'Unit'                  => $request->Unit,
                'ItemName'              => $request->ItemName,
                'Description'           => $request->Description,
                'UnitPrice'             => $request->UnitPrice,
                'TotalAmount'           => ($request->Quantity * $request->UnitPrice),
                'remarks'               => "",
                'Estimatedusefullife'    => $request->Estimatedusefullife,
                'FundCluster'           => $request->FundCluster,
                'EmployeeID'            => $request->EmployeeId,
                'DateTransferred'       => $request->DateAcquired,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);
        
        if($par){
            return response()->json([
                'status' => 200, 
                // 'data' => $supplier,
            ]); 

        }else{
            return response()->json([
                'status' => 400, 
                'message' => 'Error!.',
            ]); 
        }
    
    }

    public function print(Request $request){
        $id = $this->aes->decrypt($request->id);
        

        $property = DB::table('item as i')
            ->select('i.*',DB::raw("Concat(e2.FirstName , ' ', e2.LastName) as IssuedBy"),
                        's.SupplierName as SupplierNameSTR')
            ->leftjoin('employee as e2','i.IssuedBy','=','e2.id')
            ->leftjoin('store as s','i.StoreName','=','s.id')
            ->where("i.id", $id)
            ->first();

        $subs = DB::table("items_sub as isub")
            ->select("isub.*",DB::raw("Concat(e1.FirstName , ' ', e1.LastName) as EmployeeName"),
            'e1.PositionTitle as EmpPositionTitle')
            ->leftjoin('employee as e1','isub.EmployeeID','=','e1.id')
            ->where("isub.ItemID", $id)
            ->where("isub.FundCluster", $request->fund)
            ->where("isub.EmployeeID",$request->empid)->get();
        
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["link" => "/property", "name" => "Back to search"],["link" => "#", "name" => "Print PAR"]
        ];
        // dd($propertys);
        return view('property.print',compact('property','subs'),[
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
            'fund' => $request->fund
        ]);
    }


}
