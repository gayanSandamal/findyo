<?php

namespace App\Http\Controllers;

use App\Models\LocationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class LocationLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locationLevel = LocationLevel::paginate(15)->appends(['sort' => 'name']);
        return response()->json($locationLevel, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'bail|required|max:50|unique:App\Models\LocationLevel,name|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $locationLevelData = new LocationLevel;
        $locationLevelData->name = $request['name'];
        $locationLevelData->save();

        return response()->json($locationLevelData, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LocationLevel  $locationLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\LocationLevel,id',
            'name' => 'bail|required|max:50|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $locationLevelData = LocationLevel::find($request['id']);
        $locationLevelData->name = $request['name'];
        $locationLevelData->save();

        return response()->json($locationLevelData, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LocationLevel  $locationLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\LocationLevel,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $locationLevelData = LocationLevel::find($request['id']);
        $locationLevelData->delete();

        return response()->json('Location level has Deleted', 200);
    }
}
