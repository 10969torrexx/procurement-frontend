<?php

namespace App\Http\Controllers\BOR_Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BOR_SecretaryPagesController extends Controller
{
    // ! announcements
        public function show_announcements() {
           try {
                return view('pages.bor_secretary.announcements');
           } catch (\Throwable $th) {
                //throw $th;
                return view('pages.error-500');
           }
        }
    /** 
     * ! BOR Resolution
     * ? TODO - 1 get departments list based on campus
     * ? TODO - 2 get determine the departemnt type / department scope of BOR resolution
     */
        public function show_bor_resolution() {
           try {
                // ? TODO list 1
                    $departments = \DB::Table('departments')
                        ->where('campus', session('campus'))
                        ->whereNull('deleted_at')
                        ->get();
                // ? END
                // ? TODO list  2
                    $response = \DB::table('bor_resolution')
                        ->join('departments', 'departments.id', '=','bor_resolution.department')
                        ->where('bor_resolution.campus', session('campus'))
                        ->whereNull('bor_resolution.deleted_at')
                        ->whereNull('departments.deleted_at')
                        ->get([
                            'bor_resolution.*',
                            'departments.department_name as department_name',
                            'departments.description as description',
                        ]);
                // ? END
                // return view('', compact('response', 'departments'));
                    $pageConfigs = ['pageHeader' => true];
                    $breadcrumbs = [
                        ["link" => "/", "name" => "Home"],
                        ["name" => "BOR Resolution"]
                    ];
                    # this will return the department.my-PPMP 
                    return view('pages.bor_secretary.bor-resolution',
                        [
                            'pageConfigs' => $pageConfigs,
                            'breadcrumbs' => $breadcrumbs
                        ], 
                        # this will attache the data to view
                        [
                            'response' => $response,
                            'departments'   => $departments,
                        ]
                    );
           } catch (\Throwable $th) {
                // throw $th;
                return view('pages.error-500');
           }
        }
    /**
     * ! Recommended APP
     * ? TODO - get all recommended app by bac secretariat
     */
    public function show_recommended_app() {
        try {
            // ? TODO list - 1
                
            // ? END 
        } catch (\Throwable $th) {
                return view('pages.error-500');
                //throw $th;
        }
    }
}
