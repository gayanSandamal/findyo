<?php

namespace App\Http\Controllers;

use App\Models\Interests;
use Illuminate\Http\Request;
use Validator;

class InterestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interests = Interests::paginate(15)->appends(['sort' => 'name']);
        return response()->json($interests, 200);
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
            'name' => 'bail|required|max:50|unique:App\Models\Interests,name|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $interest = new Interests;
        $interest->name = $request['name'];
        $interest->is_active = true;
        $interest->save();

        return response()->json($interest, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Intrests  $intrests
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\Interests,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $interest  = Interests::find($request['id']);
        $interest->name = $request['name'];
        $interest->is_active = $request['is_active'];
        $interest->save();

        return response()->json($interest, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Intrests  $intrests
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\Interests,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $interest = Interests::find($request['id']);
        $interest->delete();

        return response()->json('Interests Deleted', 200);
    }
}
