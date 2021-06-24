<?php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use Illuminate\Http\Request;
use Validator;

class JobTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobtitiles = JobTitle::paginate(15)->appends(['sort' => 'name']);
        return response()->json($jobtitiles, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Regx : /^[a-zA-Z0-9_\\s]*$/u allow a-z A-Z 0-9 _ space
        $validation = Validator::make($request->all(), [
            'name' => 'bail|required|max:50|unique:App\Models\JobTitle,name|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $userData = new JobTitle;
        $userData->name = $request['name'];
        $userData->save();

        return response()->json($userData, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobTitle  $jobTitle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\JobTitle,id',
            'name' => 'bail|required|max:50|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $jobTit = JobTitle::find($request['id']);
        $jobTit->name = $request['name'];
        $jobTit->save();

        return response()->json($jobTit, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobTitle  $jobTitle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\JobTitle,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $jobTit = JobTitle::find($request['id']);
        $jobTit->delete();

        return response()->json('Job Title Deleted', 200);
    }
}
