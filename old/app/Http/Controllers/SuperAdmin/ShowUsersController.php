<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\User;

class ShowUsersController extends Controller
{
  // public function employee(){
  //   $pageConfigs = ['pageHeader' => true];
  //   $breadcrumbs = [
  //     ["link" => "/", "name" => "Home"],["name" => "Users"]
  //   ];

  //   $products =  Http::withToken(session('token'))->get(env('APP_API'). "/api/product/index")->json();

  // //  $users = Http::get($this->api."/api".$this->subf."/product/index")->json(); 

  //   //dd($products['data']);
  //   return view('pages.employee-dashboard',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], ['data' => $products['data']]);
  // }

    public function show(){
    //    $data = User::all();
    //    return view('pages.admin.users', compact('data'));

       $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Add User"]
        ];
        $users =  Http::withToken(session('token'))->get(env('APP_API'). "/api/users/index")->json();
        // dd($users);

        return view('pages.admin.users',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], ['data' => $users['data']]);
    }

    public function adduser(){
        
    }
}
