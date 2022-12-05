<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //users
    public function dashboard(){
        if(\session('role') == 4) {
            /** Torrexx Changes */
                $departmentID = \session('department_id');
                $array_fund_id = [];
                $mandatory_expeditures = [];
                $total_expenses = [];
                $response = \DB::table('allocated__budgets')
                    ->join('fund_sources', 'allocated__budgets.fund_source_id', 'fund_sources.id')
                    ->where('allocated__budgets.department_id', $departmentID)
                    ->where('allocated__budgets.campus', session('campus'))
                    ->groupBy('fund_sources.fund_source', 'allocated__budgets.year')
                    ->orderBy('allocated__budgets.year')
                    ->get([
                        'fund_sources.id as fund_source_id', 'allocated__budgets.*', 'fund_sources.fund_source', \DB::raw('sum(allocated__budgets.allocated_budget) as SumBudget')
                    ]); 

                $mandatory_expeditures = \DB::table("mandatory_expenditures as me")
                    ->select("me.year", "me.fund_source_id", \DB::raw('sum(me.price) as SumMandatory'))
                    ->where("me.department_id", $departmentID)
                    ->where("me.campus", session('campus'))
                    ->groupBy("me.year")
                    ->groupBy("me.fund_source_id")
                    ->get();
                
                $ppmp_expenses = \DB::table('project_titles')
                    ->join('ppmps', 'ppmps.project_code', 'project_titles.id')
                    ->where('project_titles.department_id', session('department_id'))
                    ->where('project_titles.employee_id', session('employee_id'))
                    ->where('project_titles.campus', session('campus'))
                    ->where('ppmps.status', 4)
                    ->whereNull('ppmps.deleted_at')
                    ->whereNull('project_titles.deleted_at')
                    ->get([
                        'ppmps.estimated_price',
                        'project_titles.fund_source as f_source'
                    ]);

                    return view('pages.users-dashboard', compact('mandatory_expeditures',  'response', 'ppmp_expenses'));
        } 
        
        return view('pages.users-dashboard');
    }
    // analystic
    public function dashboardAnalytics(){
        return view('pages.dashboard-analytics');
    }
}
