<?php

namespace App\Http\Controllers\SupplyCustodian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
// use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HistoryLogController;
use App\Employee;
use App\PropertySub;
use DB;
use Carbon\Carbon;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // protected $global;
    // protected $aes;
    // public function __construct(){
    //     $this->aes = new AESCipher();
    //     $this->global = new GlobalDeclare();
    // }
    
    public function index(){
        $check = 1;/* DB::table("property as p")
            ->select('p.*','u.name','s.SupplierName','um.unit_of_measurement as unit')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('store as s','s.id','=','p.StoreName')
            ->join('unit_of_measurements as um','um.id','=','p.Unit')
            ->whereNull('p.deleted_at')
            ->where("p.propertytype", "PAR")
            ->where("p.campus", session('campus'))
            ->orderBy('p.DateIssued', "desc")
            ->get(); */

        $propertys = DB::table("property as p")
            ->select('p.*','u.name','s.SupplierName','um.unit_of_measurement as unit')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('store as s','s.id','=','p.StoreName')
            ->join('unit_of_measurements as um','um.id','=','p.Unit')
            ->whereNull('p.deleted_at')
            ->where("p.propertytype", "PAR")
            ->where("p.campus", session('campus'))
            ->orderBy('p.DateIssued', "desc")
            ->paginate(10);
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

        $unit = DB::table("unit_of_measurements")
            ->whereNull("deleted_at")
            ->get();

        $issuedby = DB::table("users")
            ->where("campus",session('campus'))
            ->whereNull("deleted_at")
            ->whereNull("username")
            ->get();

        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["link" => "#", "name" => "Property Custodian"]
        ];
        
        return view('pages.supplycustodian.property.index',compact('propertys'/* ,'propertysubs' */,'check','users','issuedby', 'supplier','unit'),[
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
        ]);
    }

    public function assign_par(Request $request){
        // dd($request->all());

        // $par =  $this->generatePARNo($request->DateIssued,$request->Type);

        $check = DB::table("property")
            ->where("PONumber",$request->PONumber)
            ->where("propertytype", "PAR")
            ->whereNull("deleted_at")
            ->where("campus", session('campus'))
            ->get();

        $check2 = DB::table("property")
            ->where("PARNo",$request->PANumber)
            ->whereNull("deleted_at")
            ->where("propertytype", "PAR")
            ->where("campus", session('campus'))
            ->get();

        if(count($check) > 0)
        {
            return response()->json([
            'status' => 500, 
            // 'data' => $supplier,
            ]); 
        }else{
            if(count($check2) > 0)
            {
                return response()->json([
                'status' => 550, 
                // 'data' => $supplier,
                ]); 
            }else{
                $Estimatedusefullife = "";
                if($request->Estimatedusefullife == null){
                    $Estimatedusefullife = "";
                }else{
                    $Estimatedusefullife = $request->Estimatedusefullife;
                }
                $par = DB::table("property")
                ->insert([
                    'PropertyNumber'        => substr($request->DateIssued, 0, 4)."-".$request->PONumber,
                    'DateAcquired'          => $request->DateAcquired,
                    'PONumber'              => $request->PONumber,
                    'IssuedBy'              => $request->Issuedby,
                    'DateReceived'          => $request->DateReceived,
                    'DateIssued'            => $request->DateIssued,
                    'PARNo'                 => $request->PANumber,
                    'StoreName'             => $request->Supplier,
                    'propertytype'          => $request->Type,
                    'Quantity'              => $request->Quantity,
                    'Unit'                  => $request->Unit,
                    'ItemName'              => $request->ItemName,
                    'Description'           => $request->Description,
                    'UnitPrice'             => $request->UnitPrice,
                    'TotalAmount'           => ($request->Quantity * $request->UnitPrice),
                    'remarks'               => "",
                    'Estimatedusefullife'    => $Estimatedusefullife,
                    'FundCluster'           => $request->FundCluster,
                    'EmployeeID'            => $request->EmployeeId,
                    'DateTransferred'       => $request->DateAcquired,
                    'campus'                => session('campus'),
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ]);

                # this will created history_log
                  (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    NULL,
                    'Add PAR Property',
                    'Add',
                    $request->ip(),
                  );
                # end 
            
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
    
    }
    public function search(Request $request){
        // $search = $request->Search;
        $employees = "";
        $employee = DB::table("users")
            ->where("name",$request->Search)
            ->whereNull("deleted_at")
            ->where("campus", session('campus'))
            ->get();
            // dd($employee);
        if(count($employee)>0){
            foreach($employee as $employee){
                $employees = $employee->id;
            }
        }else{
            $employees = $request->Search;
        }

        $stores = "";
        $store = DB::table("store")
            ->where("SupplierName",$request->Search)
            ->whereNull("deleted_at")
            ->where("campus", session('campus'))
            ->get();
            // dd($employee);
        if(count($store)>0){
            foreach($store as $store){
                $stores = $store->id;
            }
        }else{
            $stores = $request->Search;
        }
        $check = 2;/* DB::table("property as p")
            ->select('p.*','u.name','s.SupplierName','um.unit_of_measurement as unit')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('store as s','s.id','=','p.StoreName')
            ->join('unit_of_measurements as um','um.id','=','p.Unit')
            ->whereNull('p.deleted_at')
            ->where("p.propertytype", "PAR")
            ->where("p.campus", session('campus'))
            ->orderBy('p.DateIssued', "desc")
            ->get(); */

        $propertys = DB::table("property as p")
            ->select('p.*','u.name','s.SupplierName','um.unit_of_measurement as unit')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('store as s','s.id','=','p.StoreName')
            ->join('unit_of_measurements as um','um.id','=','p.Unit')
            ->where("p.propertytype", "PAR")
            ->whereNull("p.deleted_at")
            ->where(function ($query) use ($request,$stores,$employees){
                $query->where("p.PONumber",$request->Search)
                   ->orwhere("p.PropertyNumber",$request->Search)
                //    ->orwhere("p.IssuedBy",$employees)
                   ->orwhere("p.PARNo",$request->Search)
                   ->orwhere("p.StoreName",$stores)
                   ->orwhere("p.Unit",$request->Search)
                   ->orwhere("p.ItemName",$request->Search)
                   ->orwhere("p.EmployeeID",$employees);
             })
            // ->where("p.PONumber",$request->Search)
            // ->orwhere("p.PropertyNumber",$request->Search)
            // // ->orwhere("p.IssuedBy",$request->Search)
            // ->orwhere("p.PARNo",$request->Search)
            // ->orwhere("p.StoreName",$stores)
            // ->orwhere("p.Unit",$request->Search)
            // ->orwhere("p.ItemName",$request->Search)
            // ->orwhere("p.EmployeeID",$employees)
            ->where("p.campus", session('campus'))
            ->orderBy('p.DateIssued', "desc")
            ->paginate(10);

            // dd($propertys);

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

        $unit = DB::table("unit_of_measurements")
            ->whereNull("deleted_at")
            ->get();

        $issuedby = DB::table("users")
            ->where("campus",session('campus'))
            ->whereNull("deleted_at")
            ->whereNull("username")
            ->get();

        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["link" => "#", "name" => "Property Custodian"]
        ];
        
        return view('pages.supplycustodian.property.index',compact('propertys'/* ,'propertysubs' */,'check','users','issuedby', 'supplier','unit'),[
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
        ]);
    }

    public function finalize_par(Request $request){
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        // dd($id);
        $par = DB::table("property")
            ->where("id", $id)
            ->update([
                'finalize'        => 1,
            ]);

        # this will created history_log
            (new HistoryLogController)->store(
            session('department_id'),
            session('employee_id'),
            session('campus'),
            $id,
            'Finalize PAR Property',
            'Finalize',
            $request->ip(),
            );
        # end 
        
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

    public function delete_par(Request $request){
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
            # this will created history_log
                (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                $id,
                'Delete PAR Property',
                'Delete',
                $request->ip(),
                );
            # end 
        
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

    public function edit_par(Request $request){
        // dd($request->all());
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        // dd($id);
        $par = DB::table("property as p")
            ->select('p.*','u.name as EmployeeName','us.name as IssuedbyName','s.SupplierName as Storename','um.unit_of_measurement as unit','um.id as umID')
            ->join('unit_of_measurements as um','um.id','=','p.Unit')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('users as us','us.id','=','p.IssuedBy')
            ->join('store as s','s.id','=','p.StoreName')
            ->where("p.id", $id)
            ->get();
        
        return $par;
    }

    public function submitEdit_par(Request $request){
        // dd($request->all());
        $Estimatedusefullife = "";
        if($request->Estimatedusefullife == null){
            $Estimatedusefullife = "";
        }else{
            $Estimatedusefullife = $request->Estimatedusefullife;
        }
        
        $check = DB::table("property")
            ->where("PONumber",$request->PONumber)
            ->where("id","!=",$request->id)
            ->where("propertytype", "PAR")
            ->where("campus", session('campus'))
            ->get();

        $check2 = DB::table("property")
            ->where("PARNo",$request->PANumber)
            ->where("id","!=",$request->id)
            ->where("propertytype", "PAR")
            ->where("campus", session('campus'))
            ->get();

        if(count($check) > 0)
        {
            return response()->json([
            'status' => 500, 
            // 'data' => $supplier,
            ]); 
        }else{
            if(count($check2) > 0)
            {
                return response()->json([
                'status' => 550, 
                // 'data' => $supplier,
                ]); 
            }else{
                $par = DB::table("property")
                    ->where("id",$request->id)
                    ->update([
                        'DateAcquired'          => $request->DateAcquired,
                        'PONumber'              => $request->PONumber,
                        'IssuedBy'              => $request->Issuedby,
                        'DateReceived'          => $request->DateReceived,
                        'DateIssued'            => $request->DateIssued,
                        'PARNo'                 => $request->PANumber,
                        'StoreName'             => $request->Supplier,
                        'propertytype'          => $request->Type,
                        'Quantity'              => $request->Quantity,
                        'Unit'                  => $request->Unit,
                        'ItemName'              => $request->ItemName,
                        'Description'           => $request->Description,
                        'UnitPrice'             => $request->UnitPrice,
                        'TotalAmount'           => ($request->Quantity * $request->UnitPrice),
                        'Estimatedusefullife'    => $Estimatedusefullife,
                        'FundCluster'           => $request->FundCluster,
                        'EmployeeID'            => $request->EmployeeId,
                        'DateTransferred'       => $request->DateAcquired,
                        'updated_at'     => Carbon::now(),
                    ]);

                    # this will created history_log
                        (new HistoryLogController)->store(
                        session('department_id'),
                        session('employee_id'),
                        session('campus'),
                        $request->id,
                        'Edit PAR Property',
                        'Edit',
                        $request->ip(),
                        );
                    # end 
                
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
    
    }
    
    public function additem_par(Request $request){
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

    public function submitadd_par(Request $request){
        // dd($request->all());
        
        $Estimatedusefullife = "";
        if($request->Estimatedusefullife == null){
            $Estimatedusefullife = "";
        }else{
            $Estimatedusefullife = $request->Estimatedusefullife;
        }
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
                'Estimatedusefullife'    => $Estimatedusefullife,
                'FundCluster'           => $request->FundCluster,
                'EmployeeID'            => $request->EmployeeId,
                'DateTransferred'       => $request->DateAcquired,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);

            # this will created history_log
                (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'Add Item for this PAR Num.'.$request->PARNo,
                'Add Item',
                $request->ip(),
                );
            # end 
        
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

    public function finaldelete_par(Request $request){
        // dd($request->all());
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        // dd($id);
        $par = DB::table("property as p")
            ->select('p.*','u.name as EmployeeName','us.name as IssuedbyName','s.SupplierName as Storename','um.unit_of_measurement as unit','um.id as umID')
            ->join('unit_of_measurements as um','um.id','=','p.Unit')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('users as us','us.id','=','p.IssuedBy')
            ->join('store as s','s.id','=','p.StoreName')
            ->where("p.id", $id)
            ->get();
        
        return $par;
    }

    public function submitdelete_par(Request $request){
        // dd($request->all());
        $check = DB::table("property")
                // ->where("id",$request->id)
                ->get();
        $assign = "";
        $price = "";
        $total = "";
        foreach($check as $check){
            if($check->id == $request->id){
                $assign = $check->Quantity;
                $price = $check->UnitPrice;
                $total = $check->TotalAmount;
            }
        }
        $compute = ($request->Quantity * $price);
        $newtotal = ($total - $compute);
        $calc = ($assign -  $request->Quantity);
        // dd($calc);
        if($calc == 0){
            $par = DB::table("property")
            ->where("id",$request->id)
            ->update([
                'deleted_at'     => Carbon::now(),
            ]);
        }
        else{
            $par = DB::table("property")
            ->where("id",$request->id)
            ->update([
                'Quantity'              => $calc,
                'TotalAmount'              => $newtotal,
                'remarks'               => $request->Remarks,
                'updated_at'            => Carbon::now(),
            ]);
        }
        # this will created history_log
            (new HistoryLogController)->store(
            session('department_id'),
            session('employee_id'),
            session('campus'),
            $request->id,
            'Dispose PAR Property.',
            'Dispose',
            $request->ip(),
            );
        # end 
        
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

    public function transfer_par(Request $request){
        // dd($request->all());
        try {
            $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        // dd($request->Employee_id);
        $par = DB::table("property as p")
            ->select('p.*','u.name as EmployeeName','us.name as IssuedbyName','s.SupplierName as Storename','um.unit_of_measurement as unit','um.id as umID')
            ->join('unit_of_measurements as um','um.id','=','p.Unit')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('users as us','us.id','=','p.IssuedBy')
            ->join('store as s','s.id','=','p.StoreName')
            ->where("p.id", $id)
            ->get();
        // dd($par);
        $emp_id = 0;
        foreach($par as $par){
            $emp_id = $par->EmployeeID;
        }

        $users = DB::table("users")
            ->where("campus",session('campus'))
            ->where("id","!=", $emp_id)
            ->whereNull("username")
            ->whereNull("deleted_at")
            ->get();
            // dd($users);
        
        return ([$par,$users]);
        // return response()->json([
        //     // 'status' => 400, 
        //     'data1' => $par,
        //     'data1' => $users,
        // ]); 
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function submittransfer_par(Request $request){
        // dd($request->all());
        $check = DB::table("property")
                ->where("id",$request->id)
                ->get();
        $assign = "";
        foreach($check as $check){
            $assign = $check->Quantity;
            // $parno =  $this->generatePARNo($check->DateIssued,$check->propertytype);

            $Insertpar = DB::table("property")
                ->insert([
                    'PropertyNumber'        => "",
                    'DateAcquired'          => $check->DateAcquired,
                    'PONumber'              => $check->PONumber,
                    'IssuedBy'              => $check->IssuedBy,
                    'DateReceived'          => $check->DateReceived,
                    'DateIssued'            => $check->DateIssued,
                    'PARNo'                 => $check->PARNo,
                    'StoreName'             => $check->StoreName,
                    'propertytype'          => $check->propertytype,
                    'Quantity'              => $request->Quantity,
                    'Unit'                  => $check->Unit,
                    'ItemName'              => $check->ItemName,
                    'ItemStatus'            => 1,
                    'Description'           => $check->Description,
                    'UnitPrice'             => $check->UnitPrice,
                    'TotalAmount'           => ($request->Quantity * $check->UnitPrice),
                    'remarks'               => $request->Remarks,
                    'Estimatedusefullife'    => $check->Estimatedusefullife,
                    'FundCluster'           => $check->FundCluster,
                    'EmployeeID'            => $request->Employeeto,
                    'finalize'              => 1,
                    'DateTransferred'       => Carbon::now(),
                    'campus'                => session('campus'),
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ]);
        }

        $calc = ($assign -  $request->Quantity);
        // dd($calc);
        if($calc == 0){
            $par = DB::table("property")
            ->where("id",$request->id)
            ->update([
                'deleted_at'     => Carbon::now(),
            ]);
        }
        else{
            $par = DB::table("property")
            ->where("id",$request->id)
            ->update([
                'Quantity'              => $calc,
                // 'remarks'               => $request->Remarks,
                'updated_at'            => Carbon::now(),
            ]);
        }
        
                // dd($check);

        # this will created history_log
            (new HistoryLogController)->store(
                session('department_id'),
                session('employee_id'),
                session('campus'),
                NULL,
                'Transfer PAR Property.',
                'Transfer',
                $request->ip(),
                );
        # end 
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

    public function print_par(Request $request){
        // dd($request->all());  
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["link" => "#", "name" => "Property Custodian"],["link" =>"#",  "name" => "View"]
        ];
        $par = DB::table("property as p")
            ->select('p.*','u.name','us.name as Issuedby','u.position as EmpPosition', 'us.position as IsPosition','s.SupplierName','um.unit_of_measurement as unit','um.id as umID')
            ->join('unit_of_measurements as um','um.id','=','p.Unit')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('users as us','us.id','=','p.IssuedBy')
            ->join('store as s','s.id','=','p.StoreName')
            ->where('p.id',$id)
            ->whereNull('p.deleted_at')
            ->where("p.propertytype", "PAR")
            ->where("p.campus", session('campus'))
            ->orderBy('p.DateIssued', "desc")
            ->get();

        // $par = DB::table("property")
        //     ->where("id",$id)
        //     ->get();
        // dd($par);
        // return view('pages.supplycustodian.property.print',compact('par'),[
        //     'pageConfigs'=>$pageConfigs,
        //     'breadcrumbs'=>$breadcrumbs,
        // ]);
        return response()->json([
            'status' => 200, 
            'data' => $par,
        ]); 
    }

    public function print(Request $request){
        // dd($request->all());  
        $par = DB::table("property as p")
            ->select('p.*','u.name','us.name as Issuedby','u.position as EmpPosition', 'us.position as IsPosition','s.SupplierName','um.unit_of_measurement as unit','um.id as umID')
            ->join('unit_of_measurements as um','um.id','=','p.Unit')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('users as us','us.id','=','p.IssuedBy')
            ->join('store as s','s.id','=','p.StoreName')
            ->where('p.id',$request->id)
            ->whereNull('p.deleted_at')
            ->where("p.propertytype", "PAR")
            ->where("p.campus", session('campus'))
            ->orderBy('p.DateIssued', "desc")
            ->get();

            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    $request->id,
                    'Print PAR Property.',
                    'Print',
                    $request->ip(),
                    );
            # end 

        // $par = DB::table("property")
        //     ->where("id",$id)
        //     ->get();
        // dd($par);
        return view('pages.supplycustodian.property.print',compact('par'));
    }

    private function generatePARNo($DateIssued, $type){
        $date = substr($DateIssued, 0, 7);
        $out = 1;
        $tmp = DB::table("property")
                ->where("DateIssued", "like", $date."%")
                ->where("propertytype", $type)
                ->orderBy("PARNo", "DESC")->first();
                // dd($tmp);
        if (!empty($tmp)){
            $out = ($tmp->PARNo + 1);
        }
        return $out;
    }


}
