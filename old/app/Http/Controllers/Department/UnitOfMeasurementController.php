<?php

namespace App\Http\Controllers\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// this will import AESCipeher COntroller
use App\Http\Controllers\AESCipher;
use Illuminate\Support\Facades\Http;

class UnitOfMeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show(Request $request)
    {
        $aes = (new AESCipher)->decrypt($request->id);
        // get data from database 
        $response =  Http::withToken(session('token'))->post(env('APP_API'). "/api/UnitOfMeasurement", [
            'id' => $aes
        ])->json();

        if(count($response) > 0) {
            // this will return response
            return response()->json([
                'status'    => 200,
                'data'      =>  $response['data']
            ]);
        }
         // this will return response
         return response()->json([
            'status'    => 400,
            'message'      =>  'Failed!'
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
