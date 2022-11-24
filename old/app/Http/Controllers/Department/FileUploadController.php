<?php

namespace App\Http\Controllers\Department;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FileUploadController 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
          ["link" => "/", "name" => "Home"],["name" => "Users"]
        ];
    
        $products =  Http::withToken(session('token'))->get(env('APP_API'). "/api/product/index")->json();
    
      //  $users = Http::get($this->api."/api".$this->subf."/product/index")->json(); 
    
        //dd($products['data']);
        return view('pages.employee-dashboard',['pageConfigs'=>$pageConfigs,'breadcrumbs'=>$breadcrumbs], ['data' => $products['data']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $fileValidate = $request->validate([
            'file' => 'required|file|mimes:pdf',
        ]);
        // if($fileValidate){
        //     return redirect('/department/upload-signed-ppmp')->with('message', 'Invalid File Type!');
        // }
        $path = env('APP_NAME').'/upload';
        if (!Storage::exists($path)) {
        Storage::makeDirectory($path);
        }
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('storage/'.$path, $filename);
        $filepath = $filename;

        $submit = Http::post(env('APP_API')."/api/file/store",[
        'file' => $filepath,
        ])->json();
        if($submit){
           return redirect('/department/upload-signed-ppmp',)->with('message', 'Sucessfully saved!');
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
