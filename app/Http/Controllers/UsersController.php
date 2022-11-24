<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use Illuminate\Http\Request;

class UsersController extends Controller
{

  protected $api;
  protected $subf;
  protected $global;

  public function __construct(){
      $this->api = $_ENV['APP_API'];
      $this->aes = new AESCipher();
      $this->global = new GlobalDeclare(); 
      $this->subf = $this->global->RouteRole(session('role'));
  }

  public function getUsers() {
    $users = Http::withToken(session('token'))->post($this->api."/api".$this->subf."/users",[
        'campus' => session('campus')
    ])->json();
  }

  public function users(){
    $pageConfigs = ['pageHeader' => true];
    $breadcrumbs = [
      ["link" => "/", "name" => "Home"],["name" => "Users"]
    ];
    return view('pages.users',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);
  }
  
 
  #BUDGET OFFICER
 /**/ 
  // public function setdeadline(){
    //   $pageConfigs = ['pageHeader' => true];
    //   $breadcrumbs = [
    //     ["link" => "/", "name" => "Home"],["name" => "Set PPMP Deadline"]
    //   ];
    //   return view('pages.budgetofficer.setdeadline',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);
    // }
    #BUDGET OFFICER

    #DEPARTMENT OFFICE
    // public function department(){
    //   $pageConfigs = ['pageHeader' => true];
    //   $breadcrumbs = [
    //     ["link" => "/", "name" => "Home"],["name" => "Department"]
    //   ];
    //   return view('pages.department.department-dashboard',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);
    // }

    // public function supplemental(){
    //   $pageConfigs = ['pageHeader' => true];
    //   $breadcrumbs = [
    //     ["link" => "/", "name" => "Home"],["name" => "Supplemental"]
    //   ];
    //   return view('pages.department.supplemental-dashboard',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);
    // }
    // public function approvedsupplemental(){
    //   $pageConfigs = ['pageHeader' => true];
    //   $breadcrumbs = [
    //     ["link" => "/", "name" => "Home"],["name" => "Approved Supplemental"]
    //   ];
    //   return view('pages.department.view-approved-supplemental',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs]);
    // }
  #DEPARTMENT OFFICE
}
