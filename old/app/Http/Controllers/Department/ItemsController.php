<?php

namespace App\Http\Controllers\department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Items;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $response = Items::whereNull('deleted_at')->get();
            if(count($response) > 0) {
                return ([
                    'status'    => 200,
                    'data'  => \json_decode($response)
                ]);
            }
            return ([
                'status'    => 400,
                'message'   => 'Faield to retrieve Items'
            ]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($item_name)
    {
       try {
         // 
         $response = Items::where('item_name', $item_name)->get();
         if($response) {
             return ([
                 'status'    => 200,
                 'data'   =>  $response
             ]);
         }
         return ([
             'status'    => 400,
             'message'   =>  'Failed to retrieve data from items'
         ]);
       } catch (\Throwable $th) {
            throw $th;
       }
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
