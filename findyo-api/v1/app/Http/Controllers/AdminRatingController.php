<?php

namespace App\Http\Controllers;

use App\Models\AdminRating;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class AdminRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adminRating = AdminRating::paginate(15)->appends(['sort' => 'order']);
        return response()->json($adminRating, 200);
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
            'question' => 'bail|required|max:500|unique:App\Models\AdminRating,question|regex:/^[a-zA-Z0-9_\\s]*$/u',
            'order' => 'numeric|unique:App\Models\AdminRating,order',
            'weight' => 'numeric:App\Models\AdminRating,weight',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $adminRating = new AdminRating;
        $adminRating->question = $request['question'];
        $adminRating->weight = $request['weight'];
        $adminRating->active = $request['active'];
        $adminRating->order = $request['order'];
        $adminRating->save();

        return response()->json($adminRating, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminRating  $adminRating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\AdminRating,id',
            
            'question' => [
                'required',
                Rule::unique('adminrating')->ignore($request['id']),
            ],
            'order' => [
                'required',
                Rule::unique('adminrating')->ignore($request['id']),
            ],
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }
        
        $adminRating  = AdminRating::find($request['id']);
        $adminRating->question = $request['question'];
        $adminRating->weight = $request['weight'];
        $adminRating->active = $request['active'];
        $adminRating->order = $request['order'];
        $adminRating->save();

        return response()->json($adminRating, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminRating  $adminRating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\AdminRating,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $adminRating = AdminRating::find($request['id']);
        $adminRating->delete();

        return response()->json('Rating item has been deleted', 200);
    }
}
