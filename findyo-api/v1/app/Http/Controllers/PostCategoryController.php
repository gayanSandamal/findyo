<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use Validator;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postCategory = PostCategory::paginate(15)->appends(['sort' => 'name']);
        return response()->json($postCategory, 200);
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
            'name' => 'bail|required|max:50|unique:App\Models\PostCategory,name|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $postCat = new PostCategory;
        $postCat->name = $request['name'];
        $postCat->parent = $request['parent'];
        $postCat->save();

        return response()->json($postCat, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PostCategory  $postCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\PostCategory,id',
            'name' => 'bail|required|max:50|regex:/^[a-zA-Z0-9_\\s]*$/u'
        ]);
        
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $postCat = PostCategory::find($request['id']);
        $postCat->name = $request['name'];
        $postCat->save();

        return response()->json($postCat, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PostCategory  $postCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\PostCategory,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $postCat = PostCategory::find($request['id']);
        $postCat->delete();

        return response()->json('Post Category Deleted', 200);
    }
}
