<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImagers;
use Illuminate\Http\Request;
use Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::paginate(15)->appends(['sort' => 'id']);
        return response()->json($post, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $post = new Post;
        $post->like_count = 0;
        $post->user_id =  $request['user_id'];
        $post->verified = $request['verified'];
        $post->save();
        
        return response()->json($post, 200);
        
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\Post,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $post = Post::find($request['id']);
        $post->verified = $request['verified'];
        $post->like_count = $request['like_count'];
        $post->likes = $request['likes'];
        $post->share_count = $request['share_count'];
        $post->shares = $request['shares'];
        $post->save();

        return response()->json($post, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:App\Models\Post,id'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        if (PostImagers::where('post_id', $request['id'])->exists()) {
            $postImagers = PostImagers::where('post_id', $request['id']);
            $postImagers->delete();
        }

        $postCat = Post::find($request['id']);
        $postCat->delete();

        return response()->json('Post Deleted', 200);
    }

     /**
     * This will be returned the post with it's images.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function GetPostByPostId($id)
    {
        if (Post::where('id', $id)->exists()) {
            $post = Post::where('id', $id)->with('PostImages')->get();
            return response($post, 200);
        } else {
            return response()->json([
                "message" => "Post not found"
            ], 404);
        }
    }
}
