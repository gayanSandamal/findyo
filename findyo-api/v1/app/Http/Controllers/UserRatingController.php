<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\UserRating;
use Illuminate\Http\Request;
use App\Models\AdminRating;
use App\Models\UserPostRating;
use App\Models\Post;
use App\Models\User;
use Validator;

class UserRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = "successfully saved";
        $response_code = 200;
        $total_post_rating = 0;
        $total_user_rating = 0;
        $loggedin_user_rating = 0;
        $maximumRatingMark = (float)(env('MAXIMUM_RATING_MARK'));

        $post = Post::where('id', $request['post_id'])->first();
        $postowner = User::where('id', $post['user_id'])->first();
       
        if(!empty($request['ratingItems']) && $postowner != null && $post != null) {

         try{
                DB::beginTransaction();

              $userpost_rating = new UserPostRating;
              $userpost_rating->rated_by = $request['rated_by'];
              $userpost_rating->post_id = $request['post_id'];
              $userpost_rating->save();

                foreach($request->ratingItems as $ratingItems) {

                    $adminrating = AdminRating::where('id', $ratingItems['question_id'])->first();
                    
                    if($adminrating === null || !is_numeric($ratingItems['rating'])){
                        DB::rollback();
                        $message = "Invalid question id exists in request";
                        $response_code = 404;
                        break;
                    }
                    else{
                        $ratingItems['rating'] = ($ratingItems['rating'] > 0 ? round($ratingItems['rating'], 3) : 0);
                       
                        $user_rating = new UserRating;
                        $user_rating->question_id = $ratingItems['question_id'];
                        $user_rating->rating = $ratingItems['rating'];
                        $user_rating->userpost_rating_id = $userpost_rating['id'];
                        $user_rating->save();

                        $loggedin_user_rating = $loggedin_user_rating + ($ratingItems['rating'] * ($adminrating['weight']/$maximumRatingMark));

                    }
            
                }
                if($response_code == 200){

                    $updateuserpost_rating = UserPostRating::where('id', $userpost_rating['id'])->first();
                    $updateuserpost_rating->rating = $loggedin_user_rating;
                    $updateuserpost_rating->save();

                    $total_post_rating = (($post['post_rating'] * $post['rated_count']) + $loggedin_user_rating)/($post['rated_count'] + 1);

                    $total_user_rating = (($postowner['user_rating'] * $postowner['rated_count']) + $loggedin_user_rating)/($postowner['rated_count'] + 1);
                
                    $post->post_rating = round($total_post_rating, 3);
                    $post->rated_count = $post['rated_count'] + 1;
                    $post->save();

                    $postowner->user_rating = round($total_user_rating, 3);
                    $postowner->rated_count = $postowner['rated_count'] + 1;
                    $postowner->save();

                    DB::commit();
                }
            }catch(\Exception $e)
            {
                $message = 'Something went wrong';
                $response_code = 500;
                DB::rollback();
                return response()->json($message, $response_code);
            }
            
        }else{
            $message = 'No Items';
            $response_code = 200;
        }

        $ratingObject = new \stdClass();
        $ratingObject->overallUserRating= round($total_user_rating, 3);
        $ratingObject->overallPostRating  = round($total_post_rating, 3);
        $ratingObject->message = $message;
        $ratingObject->overallpostRatingByUser =  round($loggedin_user_rating,3);
        $ratingObject->user_post_rating =  $request['ratingItems'];

        return response()->json((array)$ratingObject);
    }

    /** 
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserRating  $userRating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $message = "successfully saved";
        $response_code = 200;
        $total_post_rating = 0;
        $total_user_rating = 0;
        $loggedin_user_rating = 0;
        $prev_userpost_rating = 0;
        $maximumRatingMark = (float)(env('MAXIMUM_RATING_MARK'));

        $post = Post::where('id', $request['post_id'])->first();
        $postowner = User::where('id', $post['user_id'])->first();

        if(!empty($request['ratingItems']) && $postowner != null && $post != null) {

         try{
                DB::beginTransaction();
                $matchQuerforUserRating = ['post_id' => $request['post_id'], 'rated_by' => $request['rated_by']];
                if (UserPostRating::where($matchQuerforUserRating)->exists()) {

                    $prevUserPostRatings = UserPostRating::where($matchQuerforUserRating)->first();

                    $prev_userpost_rating = $prevUserPostRatings['rating'];

                    $prevUserRatings = UserRating::where('userpost_rating_id', $prevUserPostRatings['id']);
                    $prevUserRatings->delete();
                
                    foreach($request -> ratingItems as $ratingItems) {
                        
                        $adminrating = AdminRating::where('id', $ratingItems['question_id'])->first();

                        if($adminrating === null || !is_numeric($ratingItems['rating'])){
                            DB::rollback();
                            $message = "Invalid data found in request";
                            $response_code = 404;
                            break;
                        }
                        else{

                            $ratingItems['rating'] = ($ratingItems['rating'] > 0 ? round($ratingItems['rating'], 3) : 0);
                            
                            $user_rating = new UserRating;
                            $user_rating->question_id = $ratingItems['question_id'];
                            $user_rating->rating = $ratingItems['rating'];
                            $user_rating->userpost_rating_id = $prevUserPostRatings['id'];
                            $user_rating->save();

                            $loggedin_user_rating = $loggedin_user_rating + $ratingItems['rating'] * ($adminrating['weight']/$maximumRatingMark);

                        }
                
                    }
                    if($response_code == 200){

                        $prevUserPostRatings->rating = $loggedin_user_rating;
                        $prevUserPostRatings->save();
                    
                        $total_post_rating = (($post['post_rating'] * $post['rated_count']) - $prev_userpost_rating + $loggedin_user_rating) / ($post['rated_count']);

                        $total_user_rating = (($postowner['user_rating'] * $postowner['rated_count']) - $prev_userpost_rating + $loggedin_user_rating) / ($postowner['rated_count']);
                    
                        $post->post_rating = round($total_post_rating, 3);
                        $post->save();

                        $postowner->user_rating = round($total_user_rating, 3);
                        $postowner->save();

                        DB::commit();

                    }
                }
                else{
                    $message = 'No Items';
                    $response_code = 200;
                }
            }catch(\Exception $e)
            {
                $message = 'Something went wrong';
                $response_code = 500;
                DB::rollback();
                return response()->json($message, $response_code);
                //throw $e;
            } 
            
        }else{
            $message = 'No Items';
            $response_code = 200;
        }
        $ratingObject = new \stdClass();
        $ratingObject->overallUserRating = round($total_user_rating, 3);
        $ratingObject->overallPostRating  = round($total_post_rating, 3);
        $ratingObject->message = $message;
        $ratingObject->overallpostRatingByUser =  round($loggedin_user_rating,3);
        $ratingObject->user_post_rating =  $request['ratingItems'];

        return response()->json((array)$ratingObject);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserRating  $userRating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $message = "successfully deleted";
        $response_code = 200;
        $total_post_rating = 0;
        $total_user_rating = 0;
        $prev_userpost_rating = 0;

        
        $post = Post::where('id', $request['post_id'])->first();
        $postowner = User::where('id', $post['user_id'])->first();
       
        try{
            DB::beginTransaction();

            $matchQuerforUserRating = ['post_id' => $request['post_id'], 'rated_by' => $request['rated_by']];
            if (UserPostRating::where($matchQuerforUserRating)->exists()) {

                $prevUserPostRatings = UserPostRating::where($matchQuerforUserRating)->first();

                 $prev_userpost_rating = $prevUserPostRatings['rating'];

                 $prevUserRatings = UserRating::where('userpost_rating_id', $prevUserPostRatings['id']);
                 $prevUserRatings->delete();

                 $total_post_rating = (($post['rated_count'] -1) > 0 ? (($post['post_rating'] * $post['rated_count']) - $prev_userpost_rating) / ($post['rated_count'] -1) : 0 );

                 $total_user_rating = (($postowner['rated_count'] -1) > 0 ? (($postowner['user_rating'] * $postowner['rated_count']) - $prev_userpost_rating) / ($postowner['rated_count'] -1) : 0);
             
                 $post->post_rating = round($total_post_rating, 3);
                 $post->rated_count = $post['rated_count'] - 1;
                 $post->save();

                 $postowner->user_rating = round($total_user_rating, 3);
                 $postowner->rated_count = $postowner['rated_count'] - 1;
                 $postowner->save();

                 $prevUserPostRatings->delete();

            }
            else{
                $message = 'No Item';
                $response_code = 200;
            }

            DB::commit();

        }
        catch(\Exception $e){
            $message = 'Something went wrong';
            $response_code = 500;
            DB::rollback();
            return response()->json($message, $response_code);
            //throw $e;
        } 

        $ratingObject = new \stdClass();
        $ratingObject->overallUserRating = round($total_user_rating, 3);
        $ratingObject->overallPostRating = round($total_post_rating, 3);
        $ratingObject->message = $message;
        $ratingObject->overallpostRatingByUser = 0;

        return response()->json((array)$ratingObject);
    }

    public function GetUserRatingsByRatedUserId($rated_by , $post_id)
    {
        $matchQuerforUserRating = ['post_id' => $post_id, 'rated_by' => $rated_by];
            if (UserPostRating::where($matchQuerforUserRating)->exists()) {
          
                    $overallratingforPostByUser = UserPostRating::where($matchQuerforUserRating)
                                                        ->first();         
                    $currentratingbreakdown = UserRating::where('userpost_rating_id', $overallratingforPostByUser['id'])
                                                         ->join('adminrating','adminrating.id','=','user_rating.question_id')
                                                         ->select('user_rating.*','adminrating.question','adminrating.order')
                                                         ->get();

            $ratingObject = new \stdClass();
            $ratingObject->overallRatingforPostByUser =$overallratingforPostByUser ;
            $ratingObject->currentRatingBreakdown = $currentratingbreakdown ;
            return response()->json( $ratingObject);
        }
        else{
            return response()->json("No Items");
        }
    }
}
