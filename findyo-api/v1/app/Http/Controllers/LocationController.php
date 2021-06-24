<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Validator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::paginate(15)->appends(['sort' => 'name']);
        return response()->json($locations, 200);
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
            'name' => 'bail|required|max:50|unique:App\Models\Location,name|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $locationData = new Location;
        $locationData->name = $request['name'];
        $locationData->parent = $request['parent'];
        $locationData->save();

        return response()->json($locationData, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\Location,id',
            'name' => 'bail|required|max:50|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $locationData = Location::find($request['id']);
        $locationData->name = $request['name'];
        $locationData->parent = $request['parent'];
        $locationData->location_level_id = $request['location_level_id'];
        $locationData->save();

        return response()->json($locationData, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\Location,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $locationData = Location::find($request['id']);
        $locationData->delete();

        return response()->json('Location hasbeen Deleted', 200);
    }

    /**
     * This will be returned the location with it's location level.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function GetLocationByLocationId($id)
    {
        if (Location::where('id', $id)->exists()) {
            $location = Location::where('id', $id)->with('LocationLevel')->get();
            return response($location, 200);
        } else {
            return response()->json([
                "message" => "Location not found"
            ], 404);
        }
    }
}
