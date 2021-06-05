<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostImagers;
use Config;
use Validator;

class ImagesController extends Controller
{
    public function store(Request $request)
    {
        if(!$request->hasFile('imageFiles')) {
            return response()->json(['uploaded_file_not_found'], 400);
        }

        $allowedfileExtension=config('FindyoConfig.allowedImageExtensions');
        $imageStorePath=config('FindyoConfig.postImageSavePath');
  
        foreach($request->imageFiles as $mediaFiles) {
            $extension = $mediaFiles->getClientOriginalExtension();
            $check = in_array(strtolower($extension),$allowedfileExtension);
            
            if($check) {
                $path = $mediaFiles->store($imageStorePath);
                $name = $mediaFiles->getClientOriginalName();
         
                $save = new PostImagers();
                $save->name = $name;
                $save->post_id = $request['post_id'];;
                $save->save();
            } else {
                    return response()->json(['invalid_file_format'], 422);
                }
        }
          
    
         return response()->json(['file_uploaded'], 200);

    }

      /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PostImagers  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (PostImagers::where('id', $id)->exists()) {
            $postImagers = PostImagers::where('id', $id);
            $postImagers->delete();
        }else {
            return response()->json([
                "message" => "Image not found"
            ], 404);
        }

        return response()->json('Image Deleted', 200);
    }
}
