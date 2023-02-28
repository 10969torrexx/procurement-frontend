<?php

namespace App\Http\Controllers\BAC;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
Use Carbon\Carbon;
use App\Http\Controllers\HistoryLogController;

class BACResolutionController extends Controller
{
  public function generate(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "BAC RESOLUTION"]
    ];
    $users = DB::table("users")
          ->select("name","id")
          ->where("campus",session('campus'))
          ->whereNull("username")
          ->get();
          
    $president = DB::table("users")
          ->select("name","id")
          ->where("campus",session('campus'))
          ->where("role",12)
          ->whereNull("username")
          ->get();
          // dd($president);

    return view('pages.bac.resolution.generate',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs],compact('users','president')
    );
  }
}
