<?php

namespace App\Http\Controllers\SupplyCustodian;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
use App\Http\Controllers\HistoryLogController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;



class APP_CSE extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $global;
    protected $aes;
    public function __construct(){
        $this->aes = new AESCipher();
        $this->global = new GlobalDeclare();
    }
    
    

  public function app_cse_index(Request $request){
    // dd($request->all());
    try {
            $validator = 0;
            $response = DB::table('app_cse')
                          ->whereNull('deleted_at')
                          ->where('status',0)
                          ->where('campus',session('campus'))
                          ->orderBy('year_created','DESC')
                          ->get();

                        //   dd($response);
      return view('pages.supplycustodian.app_cse.app_cse', compact('response','validator'));
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function submitted_app_cse_index(Request $request){
    try {
        $validator = 1;
      $response = DB::table('app_cse')
                    ->whereNull('deleted_at')
                    ->where('status',1)
                    ->where('campus',session('campus'))
                    ->get();

      return view('pages.supplycustodian.app_cse.app_cse', compact('response','validator'));
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /* Upload app cse
  * - Creating files for app cse 
  * - Downloadable app
  */
  public function upload_app_cse(Request $request) {
    // dd($request->all());
      try {
          $validate = Validator::make($request->all(), [
              'project_category'  => ['required'],
              'year_created'  =>  ['required'],
              'file_name' => ['required'],
              'app_cse'   => ['required', 'mimes:pdf, jpeg, jpg, png', 'max:2048']
          ]);
          if($request->hasFile('app_cse')) {
              $file = $request->file('app_cse');
              $extension = $request->file('app_cse')->getClientOriginalExtension();
            
              $is_valid = false;
              # validate extension
                  $allowed_extensions = ['pdf', 'jpeg', 'jpg', 'png'];
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
              # end
              # moving of the actual file
                  $file_name = /* (new GlobalDeclare)->project_category((new AESCipher)->decrypt($request->project_category)) */ $request->file_name.(new AESCipher)->decrypt($request->year_created).'-'. time();
                  $destination_path = env('APP_NAME').'\\supply_custodian_upload\\app_cse\\';
                  if (!\Storage::exists($destination_path)) {
                      \Storage::makeDirectory($destination_path);
                  }
                  $file->storeAs($destination_path, $file_name.'.'.$extension);
                  $file->move('storage/'. $destination_path, $file_name.'.'.$extension);
              # end
              # storing data to signed_app_cse table
                  \DB::table('app_cse')
                  ->insert([
                      'employee_id'   => session('employee_id'),
                      'department_id'   => session('department_id'),
                      'campus'   => session('campus'),
                      'year_created'   => (new AESCipher)->decrypt($request->year_created),
                      'project_category'  => (new AESCipher)->decrypt($request->project_category),
                      'file_name'   => $request->file_name,
                      'file_directory'    => $destination_path .''. $file_name.'.'.$extension,
                      'app_cse' =>  $file_name.'.'.$extension,
                      'created_at'    => Carbon::now(),
                      'updated_at'    => Carbon::now()
                  ]);
              # this will created history_log
                  (new HistoryLogController)->store(
                      session('department_id'),
                      session('employee_id'),
                      session('campus'),
                      null,
                      'Uploaded app cse',
                      'upload',
                      $request->ip(),
                  );
              # end
              return back()->with([
                  'success' => 'APP uploaded successfully!'
              ]);
          } else {
              return back()->with([
                  'error' => 'Please fill the form accordingly!'
              ]);
          }
      } catch (\Throwable $th) {
          throw $th;
          return view('pages.error-500');
      }
  }

  /* Download Uplooded app
  * - this will enable downlaod uploade app
  * - based on: Employee id, campus, department_id
  * - get file from storage upload id search
  */
  public function download_uploaded_app_cse(Request $request) {
    // dd($request->all());
      try {
          $response = \DB::table('app_cse')
          ->where('employee_id', session('employee_id'))
          ->where('department_id', session('department_id'))
        //   ->where('campus', session('campus'))
          ->where('id', (new AESCipher)->decrypt($request->id))
          ->whereNull('deleted_at')
          ->get([
              'app_cse'
          ]);
          # this will created history_log
              (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  null,
                  'Downloaded app cse',
                  'Download',
                  $request->ip(),
              );
          # end
          return \Storage::download(env('APP_NAME').'\\supply_custodian_upload\\app_cse\\'.$response[0]->app_cse);
      } catch (\Throwable $th) {
          throw $th;
          return view('pages.error-500');
      }
  }

  /* Delete Uploaded app cse
  * - this will delete the uploaded app
  * - based on: Employee id, campus, department_id
  */
  public function delete_uploaded_app_cse(Request $request) {
      try {
          $response = \DB::table('app_cse')
          ->where('employee_id', session('employee_id'))
          ->where('department_id', session('department_id'))
          ->where('campus', session('campus'))
          ->where('id', (new AESCipher)->decrypt($request->id))
          ->whereNull('deleted_at')
          ->update([
              'updated_at'    => Carbon::now(),
              'deleted_at'    => Carbon::now()
          ]);
          # this will created history_log
              (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  (new AESCipher)->decrypt($request->id),
                  'Deleted app cse',
                  'Delete',
                  $request->ip(),
              );
          # end
          return back()->with([
              'success'   => 'Uploaded app successfully deleted!'
          ]);
      } catch (\Throwable $th) {
          return view('pages.error-500');
          // throw $th;
      }
  }

  /* View uploaded app
  * - this will allow preview of the uploaded app
  */
  public function view_uploaded_app_cse(Request $request) {
      try {
          $response = \DB::table('app_cse')
              ->where('employee_id', session('employee_id'))
              ->where('department_id', session('department_id'))
            //   ->where('campus', session('campus'))
              ->where('id', (new AESCipher)->decrypt($request->id))
              ->whereNull('deleted_at')
              ->get([
                  'file_directory',
                  'app_cse'
              ]);
          # this will created history_log
              (new HistoryLogController)->store(
                  session('department_id'),
                  session('employee_id'),
                  session('campus'),
                  (new AESCipher)->decrypt($request->id),
                  'Viewed app cse',
                  'View',
                  $request->ip(),
              );
          # end
          return response ([
              'status'    => 200,
              'data'  => $response
          ]);
      } catch (\Throwable $th) {
          return view('pages.error-500');
          throw $th;
      }
  }

  /* GET Edit Uploaded app
  * - get uploaded app for edit feature
  */
  public function get_edit_app_cse(Request $request) {
      try {
          $response = \DB::table('app_cse')
              ->where('employee_id', session('employee_id'))
              ->where('department_id', session('department_id'))
              ->where('campus', session('campus'))
              ->where('id', (new AESCipher)->decrypt($request->id))
              ->whereNull('deleted_at')
              ->get();

          return ([
              'status'    => 200,
              'data'  => $response,
          ]);
        
      } catch (\Throwable $th) {
          return view('pages.error-500');
          throw $th;
      }
  }

  /* GET Edit Uploaded app
  * - edit / update uploaded app
  * ! wadwadaw
  * ? awdawdaw
  */
  public function edit_uploaded_app_cse(Request $request) {
      try {
        // dd($request->year_created);
          $validate = Validator::make($request->all(), [
              'project_category'  => ['required'],
              'year_created'  =>  ['required'],
              'file_name' => ['required'],
              'app_cse'   => ['required', 'mimes:pdf, jpeg, jpg, png', 'max:2048']
          ]);
          if($request->hasFile('app_cse')) {
              $file = $request->file('app_cse');
              $extension = $request->file('app_cse')->getClientOriginalExtension();
              $is_valid = false;
              # validate extension
                  $allowed_extensions = ['pdf', 'jpeg', 'jpg', 'png'];
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
              # end
              $file_name = (new GlobalDeclare)->project_category((new AESCipher)->decrypt($request->project_category)) .'-'. time();
              $destination_path = env('APP_NAME').'\\supply_custodian_upload\\app_cse\\';
              if (!\Storage::exists($destination_path)) {
                  \Storage::makeDirectory($destination_path);
              }
              $file->storeAs($destination_path, $file_name.'.'.$extension);
              // \Storage::put($destination_path, $file_name.'.'.$extension);
              $file->move('storage/'. $destination_path, $file_name.'.'.$extension);
              # storing data to signed_app_cse table
                  \DB::table('app_cse')
                  ->where('id', $request->id)
                  ->update([
                      'year_created'   => $request->year_created,
                      'project_category'  => (new AESCipher)->decrypt($request->project_category),
                      'file_name'   => $request->file_name,
                      'file_directory'    => $destination_path .''. $file_name.'.'.$extension,
                      'app_cse' =>  $file_name.'.'.$extension,
                      'updated_at'    => Carbon::now()
                  ]);
              # this will created history_log
                  (new HistoryLogController)->store(
                      session('department_id'),
                      session('employee_id'),
                      session('campus'),
                      null,
                      'Viewed app cse',
                      'View',
                      $request->ip(),
                  );
              # end
              return back()->with([
                  'success' => 'APP uploaded successfully!'
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

  # upload app | app cse
  public function get_upload_app_cse(Request $request) {
      try {
        // dd((new AESCipher)->decrypt($request->year_created));
          $this->validate($request, [
              'project_category'  => ['required'],
              'year_created'  =>  ['required'],
              'file_name' => ['required'],
          ]);
          $validator = $request->validator;
          # get uplpaded app
          $response = \DB::table('app_cse')
              ->where('employee_id', session('employee_id'))
              ->where('department_id', session('department_id'))
              ->where('campus', session('campus'))
              ->where('status', $request->validator)
              ->where('project_category', (new AESCipher)->decrypt($request->project_category))
              ->where('year_created', (new AESCipher)->decrypt($request->year_created))
              ->where('file_name', 'like', '%'. $request->file_name .'%')
              ->whereNull('deleted_at')
              ->get();
          # return page
        //   return view('pages.supplycustodian.app_cse.app_cse', compact('response','validator'));
        return ([
            'status'    => 200,
            'data'  => $response,
        ]);
      } catch (\Throwable $th) {
          throw $th;
          return view('pages.error-500');
      }
  }

  
  /* Submit Uploaded app cse
  * - this will delete the uploaded app
  * - based on: Employee id, campus, department_id
  */
    public function submit_uploaded_app_cse(Request $request) {
        try {
            $response = \DB::table('app_cse')
            ->where('employee_id', session('employee_id'))
            ->where('department_id', session('department_id'))
            ->where('campus', session('campus'))
            ->where('id', (new AESCipher)->decrypt($request->id))
            ->whereNull('deleted_at')
            ->update([
                'status'    => 1,
            ]);
            # this will created history_log
                (new HistoryLogController)->store(
                    session('department_id'),
                    session('employee_id'),
                    session('campus'),
                    (new AESCipher)->decrypt($request->id),
                    'Submit app cse to BAC Sec',
                    'Submit',
                    $request->ip(),
                );
            # end
            return back()->with([
                'success'   => 'Submitted APP successfully !'
            ]);
        } catch (\Throwable $th) {
            return view('pages.error-500');
            // throw $th;
        }
    }
    
    public function filter_upload_app_cse(Request $request){
        dd($request->all());
        try {
            
            $validator = $request->validator;
            $response = DB::table('app_cse')
                            ->whereNull('deleted_at')
                            ->where('status',$request->validator)
                            ->where('project_category',$request->project_category)
                            ->get();
                            // dd($response);

            return ([
                'status'    => 200,
                'data'  => $response,
            ]);
        } catch (\Throwable $th) {
        throw $th;
        }
    }
}
