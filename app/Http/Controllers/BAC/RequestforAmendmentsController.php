<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\AESCipher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RequestforAmendmentsController extends Controller
{
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Requests For Amendments"]
        ];
        $ppmp =  Http::withToken(session('token'))->get(env('APP_API'). "/api/amendments/index")->json();
        //  dd($ppmp);
        // $table = $ppmp['data'];
        // $modal = $ppmp['data2'];
        // $all = [$table];
        // dd($ppmp);  
        if($ppmp==null)
        {
          return view('pages.bac.ppmp.request-for-amendments',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);
        }
        else{
          return view('pages.bac.ppmp.request-for-amendments',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],
          [
            'data' => $ppmp['data'],
            // 'data' => $ppmp['data'],
          ] 
          );
        }
        
      }

      public function show(Request $request){
        $id = $request->id;
        $aes = new AESCipher();
        $id1 = $aes->decrypt($id);
        $response = Http::withToken(session('token'))->get(env('APP_API'). "/api/approved-ppmp/show/".$id1,[
        'id' => $id1,
      ])->json();
      // dd($response);  
            return $response; 
      }
}
