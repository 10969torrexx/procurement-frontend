<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
class HistoryLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $response = \DB::table('history_log')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($department_id, $employee_id, $campus_id, $data_id, $activity, $action, $ip_address )
    {
        try {
            
            $response = \DB::table('history_log')
            ->insert([
                'department_id' => $department_id,
                'employee_id'   =>  $employee_id,
                'campus'    => $campus_id,
                'data_id'   => $data_id,
                'activity'  => $activity,
                'action'    => $action,
                'ip_address'    => $ip_address,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        } catch (\Throwable $th) {
            throw $th;
            // return  view('pages.error-500');
        }
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
