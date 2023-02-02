<?php

namespace App\Http\Controllers\BOR_Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\HistoryLogController;
use App\Http\Controllers\AESCipher;
use Carbon\Carbon;
class BOR_SecretaryController extends Controller
{
    /**
     * ! Upload BOR resolution
     * ? process bor resolution upload
     */
    
    public function upload_bor_resolution(Request $request) {
        try {
            $this->validate($request, [
                'bor_title'  => ['required'],
                'bor_file'   => ['required', 'max:2048'],
                'department'    => ['required']
            ]);
            
            if($request->hasFile('bor_file')) {
                $file = $request->file('bor_file');
                $extension = $request->file('bor_file')->getClientOriginalExtension();
               
                // ! validate extension
                    $is_valid = false;
                    $allowed_extensions = ['pdf', 'docx', 'pub', 'jpeg', 'jpg', 'png'];
                    for ($i = 0; $i < count($allowed_extensions) ; $i++) { 
                       if($allowed_extensions[$i] == $extension) {
                            $is_valid = true;
                       }
                    }
                    if($is_valid == false) {
                        return back()->with([
                            'error' => 'Invalid file format!'
                        ]);
                    }
                // ! end
                
                $file_name = 'BOR_Resolution'.'-'. Carbon::now()->format('Y') .'-'. time();
                $destination_path = env('APP_NAME').'\\bor_secretary\\bor_resolution\\';
                if (!\Storage::exists($destination_path)) {
                    \Storage::makeDirectory($destination_path);
                }
                $file->storeAs($destination_path, $file_name.'.'.$extension);
                $file->move('storage/'. $destination_path, $file_name.'.'.$extension);
                # storing data to signed_ppmp table
                    \DB::table('bor_resolution')
                    ->insert([
                        'employee_id'   => session('employee_id'),
                        'department_id'   => session('department_id'),
                        'campus'   => session('campus'),
                        'bor_title' =>  $request->bor_title,
                        'year_created'  => Carbon::now()->format('Y'),
                        'bor_file'  =>  $file_name.'.'.$extension,
                        'file_directory'    => $destination_path .''. $file_name.'.'.$extension,
                        'department'    => (new AESCipher)->decrypt($request->department),
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now()
                    ]);
                # this will created history_log
                    (new HistoryLogController)->store(
                        session('department_id'),
                        session('employee_id'),
                        session('campus'),
                        null,
                        'Uploaded BOR Resolution',
                        'upload',
                        $request->ip(),
                    );
                # end
                return back()->with([
                    'success' => 'BOR Resolution uploaded successfully!'
                ]);
            } else {
                return back()->with([
                    'error' => 'Please fill the form accordingly!'
                ]);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return view('pages.error-500');
        }
    }

    /**
     * ! Download uploaded BOR resolution
     * ? TODO enable bor resolution download based given id
     */
    public function download_bor_resolution(Request $request) {
        try {
            $response = \DB::table('bor_resolution')
                ->where('campus', session('campus'))
                // ->where('employee_id', session('employee_id'))
                // ->where('department_id', session('department_id'))
                ->where('id', (new AESCipher)->decrypt($request->id))
                ->whereNull('deleted_at')
                ->get();
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    null,
                    'Downloaded BOR Resolution',
                    'Download',
                    $request->ip(),
                );
            # end
            return \Storage::download(env('APP_NAME').$response[0]->file_directory);
        } catch (\Throwable $th) {
            throw $th;
            return view('pages.error-500');
        }
    }

    /**
     * ! Delete uploaded BOR resolution
     * ? TODO enable bor resolution deletion based given id
     */
    public function delete_bor_resolution(Request $request) {
        try {
            $response = \DB::table('bor_resolution')
                ->where('campus', session('campus'))
                ->where('employee_id', session('employee_id'))
                ->where('department_id', session('department_id'))
                ->where('id', (new AESCipher)->decrypt($request->id))
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => Carbon::now()
                ]);
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    (new AESCipher)->decrypt($request->id),
                    'Deleted BOR Resolution',
                    'Deleted',
                    $request->ip(),
                );
            # end
            return back()->with([
                'success'   => 'Uploaded PPMP successfully deleted!'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return view('pages.error-500');
        }
    }
    /**
     * ! View uploaded BOR resolution
     * ? TODO enable bor resolution deletion based given id
     */
    public function view_bor_resolution(Request $request) {
        
        try {
            $response = \DB::table('bor_resolution')
                ->where('campus', session('campus'))
                // ->where('employee_id', session('employee_id'))
                // ->where('department_id', session('department_id'))
                ->where('id', (new AESCipher)->decrypt($request->id))
                ->whereNull('deleted_at')
                ->get([
                    'bor_title',
                    'file_directory',
                    'bor_file'
                ]);
                // dd($response);
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    (new AESCipher)->decrypt($request->id),
                    'Viewed BOR Resolution',
                    'View',
                    $request->ip(),
                );
            # end
            return response ([
                'status'    => 200,
                'data'  => $response
            ]);
           
        } catch (\Throwable $th) {
            throw $th;
            return view('pages.error-500');
        }
    }
    /**
     * ! Search uploaded BOR resolution
     * ? TODO enable bor resolution deletion based given id
     */
    public function search_bor_resolution(Request $request) {
        try {
            $this->validate($request, [
                'bor_title' => ['required']
            ]);
            $departments = \DB::Table('departments')
                ->where('campus', session('campus'))
                ->whereNull('deleted_at')
                ->get();
            $response = \DB::table('bor_resolution')
                ->join('departments', 'departments.id', '=','bor_resolution.department')
                ->where('bor_resolution.bor_title', 'like', '%'. $request->bor_title . '%')
                ->where('bor_resolution.campus', session('campus'))
                ->whereNull('bor_resolution.deleted_at')
                ->whereNull('departments.deleted_at')
                ->get([
                    'bor_resolution.*',
                    'departments.department_name as department_name',
                    'departments.description as description',
                ]);
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    null,
                    'Searched BOR Resolution',
                    'Search',
                    $request->ip(),
                );
            # end
            return view('pages.bor_secretary.bor-resolution', compact('response', 'departments'));
           
        } catch (\Throwable $th) {
            throw $th;
            return view('pages.error-500');
        }
    }

    /**
     * ! Get specified BOR resolution
     * ? TODO get specified BOR resolution based on the given id
     */
    public function get_bor_resolution(Request $request) {
        try {
            $response = \DB::table('bor_resolution')
                ->join('departments', 'departments.id', '=','bor_resolution.department')
                ->where('bor_resolution.id', (new AESCipher)->decrypt($request->id))
                ->where('bor_resolution.campus', session('campus'))
                ->whereNull('bor_resolution.deleted_at')
                ->whereNull('departments.deleted_at')
                ->get([
                    'bor_resolution.*',
                    'departments.department_name as department_name',
                    'departments.description as description',
                ]);
            
            return ([
                'status'    => 200,
                'data'  => $response
            ]);
           
        } catch (\Throwable $th) {
            // throw $th;
            return view('pages.error-500');
        }
    }

    /**
     * ! Update / edit uploded BOR Resolution
     * ? TODO enable update or editing of uploaded BOR resolution details included
     */
    public function edit_bor_resolution(Request $request) {
        try {
            $this->validate($request, [
                'bor_title' => ['required'],
                'department'    => ['required'],
                'bor_file'  =>  ['required']
            ]);
            if($request->hasFile('bor_file')) {
                $file = $request->file('bor_file');
                $extension = $request->file('bor_file')->getClientOriginalExtension();
                // ! validate extension
                    $is_valid = false;
                    $allowed_extensions = ['pdf', 'docx', 'pub', 'jpeg', 'jpg', 'png'];
                    for ($i = 0; $i < count($allowed_extensions) ; $i++) { 
                       if($allowed_extensions[$i] == $extension) {
                            $is_valid = true;
                       }
                    }
                    if($is_valid == false) {
                        return back()->with([
                            'error' => 'Invalid file format!'
                        ]);
                    }
                // ! end
                
                $file_name = 'BOR_Resolution'.'-'. Carbon::now()->format('Y') .'-'. time();
                $destination_path = env('APP_NAME').'\\bor_secretary\\bor_resolution\\';
                if (!\Storage::exists($destination_path)) {
                    \Storage::makeDirectory($destination_path);
                }
                $file->storeAs($destination_path, $file_name.'.'.$extension);
                $file->move('storage/'. $destination_path, $file_name.'.'.$extension);
                # storing data to signed_ppmp table
                    \DB::table('bor_resolution')
                    ->where('campus', session('campus'))
                    ->where('id', $request->id)
                    ->update([
                        'bor_title' =>  $request->bor_title,
                        'year_created'  => Carbon::now()->format('Y'),
                        'bor_file'  =>  $file_name.'.'.$extension,
                        'file_directory'    => $destination_path .''. $file_name.'.'.$extension,
                        'department'    => (new AESCipher)->decrypt($request->department),
                        'updated_at'    => Carbon::now()
                    ]);
                # this will created history_log
                    (new HistoryLogController)->store(
                        session('department_id'),
                        session('employee_id'),
                        session('campus'),
                        null,
                        'Updated BOR Resolution',
                        'Update',
                        $request->ip(),
                    );
                # end
                return back()->with([
                    'success' => 'BOR Resolution uploaded successfully!'
                ]);
            } else {
                return back()->with([
                    'error' => 'Please fill the form accordingly!'
                ]);
            }
            return back()->with([
                'success'   => 'BOR Resolution update successfully!'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return view('pages.error-500');
        }
    }
}
