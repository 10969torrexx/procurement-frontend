<?php

namespace App\Http\Controllers\BudgetOfficer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Deadline;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Support\Facades\Validator;
use DB;

class ViewSignedPPMPController extends Controller
{
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Signed PPMP"]
        ];
        // return view('pages.budgetofficer.allocatebudget',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);

        $ppmp_deadline =  Http::withToken(session('token'))->get(env('APP_API'). "/api/budget/get_deadline")->json();
            // dd($ppmp_deadline);
            $error="";
            if($ppmp_deadline['status']==400){
                $error=$ppmp_deadline['message'];
            }

            return view('pages.budgetofficer.view-signed-ppmp',compact('ppmp_deadline'), [
                'pageConfigs'=>$pageConfigs,
                'breadcrumbs'=>$breadcrumbs,
                'error' => $error,
                'allocated_budgets' => $ppmp_deadline['data'],
            ]); 
            
    }
}
