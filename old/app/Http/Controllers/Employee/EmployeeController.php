<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(){
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
}
