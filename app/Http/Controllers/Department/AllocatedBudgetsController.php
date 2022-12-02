<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Allocated_Budgets;
use App\Models\FundSources;
use Carbon\Carbon;
use App\Http\Controllers\AESCipher;

class AllocatedBudgetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_date = Carbon::now()->addYears(1)->format('Y');
        $response = Allocated_Budgets::join('fund_sources', 'allocated__budgets.fund_source_id', '=', 'fund_sources.id')
            ->where('department_id', session('department_id'))
            ->where('')
            ->whereNull('allocated__budgets.deleted_at')
            ->get(['allocated__budgets.*', 'fund_sources.fund_source']);

        return ([
            'status'    => 200,
            'data'  =>  json_decode($response)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $response = Allocated_Budgets::
        join('fund_sources', 'allocated__budgets.fund_source_id', '=', 'fund_sources.id')
        ->where('allocated__budgets.id', $id)
        ->where('department_id', session('department_id'))
        ->whereNull('allocated__budgets.deleted_at')
        ->get(['allocated__budgets.*', 'fund_sources.fund_source']);
        if(count($response) > 0) {
            return ([
                'status'    => 200,
                'data'  => \json_decode($response)
            ]);
        }
        return ([
            'status'    => 400,
            'message'   => 'Failed to retrieve allocated budgets'
        ]);
    }
    public function find(Request $request)
    {
        //
        $response = Allocated_Budgets::
        join('fund_sources', 'allocated__budgets.fund_source_id', '=', 'fund_sources.id')
        ->where('department_id', session('department_id'))
        ->where('allocated__budgets.year', (new AESCipher)->decrypt($request->project_year))
        ->where('fund_sources.fund_source', (new AESCipher)->decrypt($request->fund_source))
        ->whereNull('allocated__budgets.deleted_at')
        ->get(['allocated__budgets.*', 'fund_sources.fund_source']);
        // dd(\json_decode($response));
        return ([
            'status'    => 200,
            'data'  => \json_decode($response)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
