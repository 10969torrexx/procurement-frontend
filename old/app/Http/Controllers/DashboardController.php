<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //users
    public function dashboard(){
        return view('pages.users-dashboard');

    //    if(session('role') == 1){
    //     return view('pages.admin.admin-dashboard');
    //    } else if(session('role') == 2){
    //     return view('pages.employee-dashboard');
    //   } else if(session('role') == 3){
    //     return view('pages.employee-dashboard');
    //   } else if(session('role') == 4){
    //     return view('pages.employee-dashboard');
    //   } else if(session('role') == 5){
    //     return view('pages.employee-dashboard');
    //   } else if(session('role') == 6){
    //     return view('pages.employee-dashboard');
    //   } else if(session('role') == 7){
    //     return view('pages.employee-dashboard');
    //   } else if(session('role') == 8){
    //     return view('pages.employee-dashboard');
    //   } else if(session('role') == 9){
    //     return view('pages.employee-dashboard');
    //   }
    }
    // analystic
    public function dashboardAnalytics(){
        return view('pages.dashboard-analytics');
    }
}
