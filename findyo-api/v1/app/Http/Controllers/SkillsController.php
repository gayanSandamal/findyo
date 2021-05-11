<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Validator;

class SkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $skills = Skill::paginate(15)->appends(['sort' => 'name']);
        return response()->json($skills, 200);
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
            'name' => 'bail|required|max:50|unique:App\Models\Skill,name|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $skillData = new Skill;
        $skillData->name = $request['name'];
        $skillData->active = $request['active'];
        $skillData->save();

        return response()->json($skillData, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\Skill,id',
            'name' => 'bail|required|max:50|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $skill = Skill::find($request['id']);
        $skill->name = $request['name'];
        $skill->active = $request['active'];
        $skill->save();

        return response()->json($skill, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\Skill,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $jobTit = Skill::find($request['id']);
        $jobTit->delete();

        return response()->json('Skill Deleted', 200);
    }
}
