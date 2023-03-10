<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class DashboardController extends Controller
{
    //users
    // public function dashboard(){
    //     if(\session('role') == 4) {
    //         /** Torrexx Changes */
    //             $departmentID = \session('department_id');
    //             $array_fund_id = [];
    //             $mandatory_expeditures = [];
    //             $total_expenses = [];
    //             $response = \DB::table('allocated__budgets')
    //                 ->join('fund_sources', 'allocated__budgets.fund_source_id', 'fund_sources.id')
    //                 ->where('allocated__budgets.department_id', $departmentID)
    //                 ->where('allocated__budgets.campus', session('campus'))
    //                 ->groupBy('fund_sources.fund_source', 'allocated__budgets.year')
    //                 ->orderBy('allocated__budgets.year')
    //                 ->get([
    //                     'fund_sources.id as fund_source_id', 'allocated__budgets.*', 'fund_sources.fund_source', \DB::raw('sum(allocated__budgets.allocated_budget) as SumBudget')
    //                 ]); 

    //             $mandatory_expeditures = \DB::table("mandatory_expenditures as me")
    //                 ->select("me.year", "me.fund_source_id", \DB::raw('sum(me.price) as SumMandatory'))
    //                 ->where("me.department_id", $departmentID)
    //                 ->where("me.campus", session('campus'))
    //                 ->groupBy("me.year")
    //                 ->groupBy("me.fund_source_id")
    //                 ->get();
                
    //             $ppmp_expenses = \DB::table('project_titles')
    //                 ->join('ppmps', 'ppmps.project_code', 'project_titles.id')
    //                 ->where('project_titles.department_id', session('department_id'))
    //                 ->where('project_titles.employee_id', session('employee_id'))
    //                 ->where('project_titles.campus', session('campus'))
    //                 ->where('ppmps.status', 4)
    //                 ->whereNull('ppmps.deleted_at')
    //                 ->whereNull('project_titles.deleted_at')
    //                 ->get([
    //                     'ppmps.estimated_price',
    //                     'project_titles.fund_source as f_source'
    //                 ]);

    //                 return view('pages.users-dashboard', compact('mandatory_expeditures',  'response', 'ppmp_expenses'));
    //     }else if(\session('role') == 11) {
    //             /** Torrexx Changes */
    //                 $departmentID = \session('department_id');
    //                 $array_fund_id = [];
    //                 $mandatory_expeditures = [];
    //                 $total_expenses = [];
    //                 $response = \DB::table('allocated__budgets')
    //                     ->join('fund_sources', 'allocated__budgets.fund_source_id', 'fund_sources.id')
    //                     ->where('allocated__budgets.department_id', $departmentID)
    //                     ->where('allocated__budgets.campus', session('campus'))
    //                     ->groupBy('fund_sources.fund_source', 'allocated__budgets.year')
    //                     ->orderBy('allocated__budgets.year')
    //                     ->get([
    //                         'fund_sources.id as fund_source_id', 'allocated__budgets.*', 'fund_sources.fund_source', \DB::raw('sum(allocated__budgets.allocated_budget) as SumBudget')
    //                     ]); 
    
    //                 $mandatory_expeditures = \DB::table("mandatory_expenditures as me")
    //                     ->select("me.year", "me.fund_source_id", \DB::raw('sum(me.price) as SumMandatory'))
    //                     ->where("me.department_id", $departmentID)
    //                     ->where("me.campus", session('campus'))
    //                     ->groupBy("me.year")
    //                     ->groupBy("me.fund_source_id")
    //                     ->get();
                    
    //                 $ppmp_expenses = \DB::table('project_titles')
    //                     ->join('ppmps', 'ppmps.project_code', 'project_titles.id')
    //                     ->where('project_titles.department_id', session('department_id'))
    //                     ->where('project_titles.employee_id', session('employee_id'))
    //                     ->where('project_titles.campus', session('campus'))
    //                     ->where('ppmps.status', 4)
    //                     ->whereNull('ppmps.deleted_at')
    //                     ->whereNull('project_titles.deleted_at')
    //                     ->get([
    //                         'ppmps.estimated_price',
    //                         'project_titles.fund_source as f_source'
    //                     ]);
    
    //                     return view('pages.users-dashboard', compact('mandatory_expeditures',  'response', 'ppmp_expenses'));
    //         }
    //     }
        
    //     return view('pages.users-dashboard');
    // }
    public function dashboard(){
        //jerald

        if(\session('role') == 4) {
            /** This will join the allocated__budgets table and fundsources table
                     * - this will be displayed on the users dashboard page
                     * - table joined [ alocated_budgets, fundsources, mandatory_expenditures, mandatory_expenditures_list ]
                    */
                    $departmentID = \session('department_id');
                    $array_fund_id = [];
                    $mandatory_expeditures = [];
                    $total_estimated_price = [];
                    $allocated_budgets = \DB::table('allocated__budgets')
                        ->join('fund_sources', 'allocated__budgets.fund_source_id', 'fund_sources.id')
                        ->where('allocated__budgets.department_id', $departmentID)
                        ->where('allocated__budgets.campus', session('campus'))
                        ->whereNull('allocated__budgets.deleted_at')
                        ->groupBy('fund_sources.fund_source', 'allocated__budgets.year')
                        ->orderBy('allocated__budgets.year')
                        ->get([
                            'fund_sources.id', 
                            'allocated__budgets.*', 
                            'fund_sources.fund_source', 
                            \DB::raw('sum(allocated__budgets.allocated_budget) as SumBudget')
                        ]); 
                    
                    $mandatory_expeditures = \DB::table("mandatory_expenditures as me")
                        ->select("me.year", "me.fund_source_id", \DB::raw('sum(me.price) as SumMandatory'))
                        // ->where('me.campus', session('campus'))
                        // ->where('me.department_id', session('department_id'))
                        ->where('me.year', Carbon::now()->addYears(1)->format('Y'))
                        ->whereNull('me.deleted_at')
                        ->groupBy("me.year")
                        ->groupBy("me.fund_source_id")
                        ->get();

                   foreach ($allocated_budgets as $item) {
                        $ppmps = \DB::table('ppmps')
                            ->join('project_titles', 'project_titles.id', 'ppmps.project_code')
                            ->whereNull('ppmps.deleted_at')
                            ->where('project_titles.allocated_budget', $item->id)
                            ->get([
                                'estimated_price'
                            ]);
                        $_estimated_price = 0.0;
                        if(count($ppmps) > 0) {
                            // * get the total estimated prices of ppmps based on the project
                            foreach ($ppmps as $_ppmp) {
                                $_estimated_price += $_ppmp->estimated_price;
                            }
                        } else {
                            $_estimated_price = 0.0;
                        }
                        array_push($total_estimated_price, $_estimated_price);
                   }
                  
                return view('pages.users-dashboard', compact('mandatory_expeditures',  'allocated_budgets', 'total_estimated_price'));
        } 
        else if(session('role') == 11) {
            //
            $departmentID = \session('department_id');
                    $array_fund_id = [];
                    $mandatory_expeditures = [];
                    $response = \DB::table('allocated__budgets')
                        ->join('fund_sources', 'allocated__budgets.fund_source_id', 'fund_sources.id')
                        ->where('allocated__budgets.department_id', $departmentID)

                        ->whereNull('allocated__budgets.deleted_at')
                        ->where('allocated__budgets.campus', session('campus'))

                        ->groupBy('fund_sources.fund_source', 'allocated__budgets.year')
                        ->orderBy('allocated__budgets.year')
                        ->get([
                            'fund_sources.id', 'allocated__budgets.*', 'fund_sources.fund_source', \DB::raw('sum(allocated__budgets.allocated_budget) as SumBudget')
                        ]); 
                    
                    $mandatory_expeditures = \DB::table("mandatory_expenditures as me")
                        ->select("me.year", "me.fund_source_id", \DB::raw('sum(me.price) as SumMandatory'))
                        ->where("me.department_id", $departmentID)

                        ->whereNull('me.deleted_at')

                        ->where("me.campus", session('campus'))
                        ->groupBy("me.year")
                        ->groupBy("me.fund_source_id")
                        ->get();

            return view('pages.users-dashboard',compact('mandatory_expeditures','response'));
        }
        return view('pages.users-dashboard');
    }
    // analystic
    public function dashboardAnalytics(){
        return view('pages.dashboard-analytics');
    }
}
