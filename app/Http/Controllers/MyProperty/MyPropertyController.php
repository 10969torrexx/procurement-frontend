<?php

namespace App\Http\Controllers\MyProperty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
// use Illuminate\Support\Facades\DB;
use App\Employee;
use App\PropertySub;
use DB;
use Carbon\Carbon;

class MyPropertyController extends Controller
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
    
    public function index($id){
        $check = 1;
        $type = $id;
        $propertys = DB::table("property as p")
            ->select('p.*','u.name','s.SupplierName','um.unit_of_measurement as unit')
            ->join('users as u','u.id','=','p.EmployeeID')
            ->join('store as s','s.id','=','p.StoreName')
            ->join('unit_of_measurements as um','um.id','=','p.Unit')
            ->whereNull('p.deleted_at')
            ->where("p.propertytype", $id)
            ->where("p.EmployeeID", session('user_id'))
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
        
        return view('pages.my-property.index',compact('propertys'/* ,'propertysubs' */,'type','check','users','issuedby', 'supplier','unit'),[
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
        ]);
    }

    public function search(Request $request){
        // $search = $request->Search;
        $type = $request->Type;
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
            ->where("p.propertytype", $request->Type)
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
            ->where("p.EmployeeID",session('user_id'))
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
        
        return view('pages.my-property.index',compact('propertys','type','check','users','issuedby', 'supplier','unit'),[
            'pageConfigs'=>$pageConfigs,
            'breadcrumbs'=>$breadcrumbs,
        ]);
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
            // ->where("p.propertytype", "PAR")
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
            // ->where("p.propertytype", "PAR")
            ->where("p.campus", session('campus'))
            ->orderBy('p.DateIssued', "desc")
            ->get();

        // $par = DB::table("property")
        //     ->where("id",$id)
        //     ->get();
        // dd($par);
        return view('pages.my-property.print',compact('par'));
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

    public function current_user(Request $request){
        // dd($request->all());  
        $aes = new AESCipher();
        $global = new GlobalDeclare();
        $id = $aes->decrypt($request->id);
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["link" => "#", "name" => "Property Custodian"],["link" =>"#",  "name" => "View"]
        ];
        $property = DB::table("property_current_user as pc")
            ->select('pc.*','p.id','p.quantity as Quantity','p.ItemName')
            ->join('property as p','p.id','=','pc.property_id')
            ->where('pc.property_id',$id)
            ->whereNull('pc.deleted_at')
            ->where("pc.campus", session('campus'))
            // ->orderBy('pc.DateIssued', "desc")
            ->get();

        // dd($property);
        $PropertyQuantity = "";
        foreach ($property as $properties) {
            $PropertyQuantity = $properties->Quantity;
        }
        // dd($PropertyQuantity);
        return response()->json([
            'status' => 200, 
            'data' => $property,
            'quantity' => $PropertyQuantity,
        ]); 
    }

    public function add_current_user(Request $request){
        // dd($request->all());  
        try {
            $aes = new AESCipher();
            $global = new GlobalDeclare();
            $id = $aes->decrypt($request->id);

            $property = DB::table("property_current_user as pc")
                ->select('pc.*','p.id','p.quantity as Quantity','p.ItemName')
                ->join('property as p','p.id','=','pc.property_id')
                ->where('pc.property_id',$id)
                ->whereNull('pc.deleted_at')
                ->where("pc.campus", session('campus'))
                ->orderBy('pc.created_at', "desc")
                ->get();
                dd($id);

            $PropertyQuantity = 0;
            $userQuantity = 0;
            // $compute = 0;
            foreach ($property as $property) {
                $PropertyQuantity = $property->Quantity;
                $userQuantity += $property->quantity;
            }
            $compute =  $PropertyQuantity - $userQuantity;
            dd($compute);
            if($request->quantity > $compute){
                return response()->json([
                    'status' => 300, 
                ]);
            }else{
                $add = DB::table("property_current_user")
                    ->insert([
                        'property_id' => $id,
                        'name'        => $request->name,
                        'quantity'    => $request->quantity,
                        'campus'      => session('campus'),
                        'created_at'  => Carbon::now(),
                        ]);
                if ($add) {
                    return response()->json([
                        'status' => 200, 
                        'data' => $property,
                        'quantity' => $PropertyQuantity,
                    ]);
                }else{
                    return response()->json([
                        'status' => 400, 
                        'data' => $property,
                        'quantity' => $PropertyQuantity,
                    ]);
                }
            }

        } catch (\Throwable $th) {
            throw $th;
        } 
    }


}
