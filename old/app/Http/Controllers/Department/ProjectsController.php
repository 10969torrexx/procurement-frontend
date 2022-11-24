<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project_Titles;
use App\Http\Controllers\AESCipher;
use Carbon\Carbon;
use \App\Http\Controllers\ProjectTimelineController;
class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $response = Project_Titles::
        where('department_id', session('department_id'))
        ->where('employee_id', session('employee_id'))
        ->where('campus', session('campus'))
        ->where('status', '!=', 0)
        ->whereNull('deleted_at')
        ->get();
        return ([
            'status'   => 200,
            'data'  => $response
        ]);
    }
     # this will get all project title that are currently on draft
    public function showAllDraft()
    {
        //
        $response = Project_Titles::
        where('department_id', session('department_id'))
        ->where('employee_id', session('employee_id'))
        ->where('campus', session('campus'))
        ->where('status', 0)
        ->whereNull('deleted_at')
        ->get();
        return ([
            'status'   => 200,
            'data'  => $response
        ]);
    }
    # this will get all the project title by year
    public function show_by_year_created($year_created) {
        $response = Project_Titles::
        where('year_created', (new AESCipher)->decrypt($year_created))
        ->where('status', '!=', 0)
        ->where('department_id', session('department_id'))
        ->where('employee_id', session('employee_id'))
        ->where('campus', session('campus'))
        ->whereNull('deleted_at')
        ->get();
        if($response) {
            return ([
                'status'  => 200,
                'data'  =>  $response
            ]);
        }
    }
    # this will get all project title that are dissproved
    public function show_disapproved() {
        try {
            $response = Project_Titles::join('fund_sources', 'project_titles.fund_source', '=', 'fund_sources.id')
            ->where('project_titles.department_id', session('department_id'))
            ->where('project_titles.employee_id', session('employee_id'))
            ->where('project_titles.campus', session('campus'))
            ->where('project_titles.project_category', 1)
            ->whereNull('project_titles.deleted_at')
            ->where('project_titles.status', 3)
            ->orWhere('project_titles.status', 5)
            ->get(['project_titles.*', 'fund_sources.fund_source']);
            if($response) {
                return ([
                    'status'    => 200,
                    'data'  =>  \json_decode($response)
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $project_count = Project_Titles:: where('department_id', intval(session('department_id')))
                ->where('employee_id' , intval(session('employee_id')))
                ->where('campus', intval(session('campus')))
                ->where('year_created' , Carbon::now()->format('Y'))
                ->get();
            $response = Project_Titles::create([
                'project_code'  => Carbon::now()->format('Y') . '-' . (count($project_count) + 1),
                'employee_id'   => session('employee_id'),
                'department_id' => session('department_id'),
                'campus'   =>   session('campus'),
                'project_title' => $request->project_title,
                'fund_source'   =>  (new AESCipher)->decrypt($request->fund_source),
                'immediate_supervisor'  =>  $request->immediate_supervisor,
                'project_type'  =>  $request->project_type,
                'project_category'  => (new AESCipher)->decrypt($request->project_category),
                'allocated_budget' => (new AESCipher)->decrypt($request->allocated_budget),
                'project_year'  => (new AESCipher)->decrypt($request->project_year),
                'year_created' => Carbon::now()->format('Y')
            ]);
            if($response) {
                // this create project status to the project timeline table
                    # grab the latest project title id
                    $latest = Project_Titles::all()->sortByDesc('created_at')->toArray();
                    $request->merge([
                        'current_project_code' => (new AESCipher)->encrypt($latest[count($latest) - 1]['id'])
                    ]);
                    # this will create a draft status to current project 
                    (new ProjectTimelineController)->store($request, 0, 'This Project was created as draft.');
                // this will return response message
                return ([
                    'status' => 200,
                    'message'   => 'Project title succesfully created!'
                ]);
            }
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
    public function show(Request $request)
    {
        $response = Project_Titles::
        where('id', intval((new AESCipher)->decrypt($request->id)))
        ->where('status', '!=', 0)
        ->whereNull('deleted_at')
        ->get();
        return ([
            'status'   => 200,
            'data'  => $response
        ]);
    }
    public function showDraft(Request $request)
    {
        $response = Project_Titles::
        where('id', intval((new AESCipher)->decrypt($request->id)))
        ->whereNull('deleted_at')
        ->get();
        return ([
            'status'   => 200,
            'data'  => $response
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
       try {
            //
            $response = Project_Titles::where('id', $id)->update([
                'project_title' =>  $request->project_title,
                'project_year'  => (new AESCipher)->decrypt($request->project_year),
                'project_type'  => $request->project_type,
                'fund_source'   => (new AESCipher)->decrypt($request->fund_source),
                'allocated_budget'  => (new AESCipher)->decrypt($request->allocated_budget)
            ]);

            if($response == 1) {
                return ([
                    'status'    => 200,
                    'message'   => 'Project title successfully updated!'
                ]);
            }
            return ([
                'status'    => 400,
                'message'   => 'Failed to update project title'
            ]);
       } catch (\Throwable $th) {
        throw $th;
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      try {
          $response = Project_Titles::where('id', $id)->delete();
          if($response == 1) {
            return ([
                'status'    => 200,
                'message'   => 'Project Title deleted successfully!'
            ]);
          }
          return ([
            'status'    => 400,
            'message'   => 'Failed to delete project title'
        ]);
      } catch (\Throwable $th) {
        throw $th;
      }
    }
}
