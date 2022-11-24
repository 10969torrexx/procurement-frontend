<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Project_Timeline;
use App\Models\Project_Titles;
use App\Http\Controllers\AESCipher;
class ProjectTimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $response = Project_timeline::where('project_id', (new AESCipher)->decrypt($id))
            ->orderBy('created_at')
            ->get();
        return ([
            'status'    => 200,
            'data'  => \json_decode($response)
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request, 
        $status, 
        $remarks
    )
    {
       try {
            $response = Project_Timeline::create([
                'employee_id'   => session('employee_id'),
                'department_id' => session('department_id'),
                'role'  =>  \session('role'),
                'campus'  => \session('campus'),
                'project_id'    =>  (new AESCipher)->decrypt($request->current_project_code),
                'status'    => intval($status),
                'remarks'   =>   $remarks
            ]);

            if($response) {
                return response()->json([
                    'status'    => 200,
                    'message'   => 'Project timeline added'
                ]);
            } 
            return response()->json([
                'status'    => 400,
                'message'   => 'Project timeline Failed'
            ]);
       } catch (\Throwable $th) {
            throw $th;
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
