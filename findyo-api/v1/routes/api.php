<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\LocationLevelController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\InterestsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ImagesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//*****************************************Start Here******************************************************//

//////////////////////////////////////Annonymouse Routes Start Here///////////////////////////////////////////
Route::post('/emailregister', [UserController::class, 'emailregister']);
Route::post('/emaillogin', [UserController::class, 'emaillogin']);
Route::put('/updateuser', [UserController::class, 'updateuser']);
Route::get('/GetUser', [UserController::class, 'GetUserByUserId']);

Route::get('/locations', [LocationController::class, 'index']);
Route::get('/postcategory', [PostCategoryController::class, 'index']);
Route::get('/interests', [InterestsController::class, 'index']);

Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts', [PostController::class, 'index']);
Route::put('/posts', [PostController::class, 'update']);
Route::delete('/posts', [PostController::class, 'destroy']);
Route::get('/GetPost/{id}', [PostController::class, 'GetPostByPostId']);

Route::delete('images/{id}', [ImagesController::class, 'destroy']);
Route::post('/images', [ImagesController::class, 'store']);


//////////////////////////////////////Auth Routes Start Here///////////////////////////////////////////
Route::middleware(['auth:api'])->get('/jobtitle', [JobTitleController::class, 'index']);
Route::middleware(['auth:api'])->get('/skills', [SkillsController::class, 'index']);
Route::middleware(['auth:api'])->post('/skills', [SkillsController::class, 'store']);

//////////////////////////////////////Admin Routes Start Here///////////////////////////////////////////
Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'scope:user-role-admin']], function () {

    //Job Title CRUD
    Route::prefix('Jobtitle')->group(function () {
        Route::post('/', [JobTitleController::class, 'store']);
        Route::put('/', [JobTitleController::class, 'update']);
        Route::delete('/', [JobTitleController::class, 'destroy']);
    });

    //Location Level CRUD
    Route::prefix('Locationlevel')->group(function () {
        Route::get('/', [LocationLevelController::class, 'index']);
        Route::post('/', [LocationLevelController::class, 'store']);
        Route::put('/', [LocationLevelController::class, 'update'])->middleware('verifyid');;
        Route::delete('/', [LocationLevelController::class, 'destroy'])->middleware('verifyid');;
    });

    //Post Category CRUD
    Route::prefix('PostCategory')->group(function () {
        Route::post('/', [PostCategoryController::class, 'store']);
        Route::put('/', [PostCategoryController::class, 'update'])->middleware('verifyid');
        Route::delete('/', [PostCategoryController::class, 'destroy'])->middleware('verifyid');
    });

    //Location  CRUD
    Route::prefix('location')->group(function () {
        Route::get('GetLocation/{id}', [LocationController::class, 'GetLocationByLocationId']);
        Route::post('/', [LocationController::class, 'store']);
        Route::put('/', [LocationController::class, 'update'])->middleware('verifyid');
        Route::delete('/', [LocationController::class, 'destroy'])->middleware('verifyid');
    });

    //Skills
    Route::prefix('skills')->group(function () {
        Route::put('/', [SkillsController::class, 'update'])->middleware('verifyid');
        Route::delete('/', [SkillsController::class, 'destroy'])->middleware('verifyid');
    });
  //Interests
    Route::prefix('interests')->group(function () {
        Route::post('/', [InterestsController::class, 'store']);
        Route::put('/', [InterestsController::class, 'update'])->middleware('verifyid');
        Route::delete('/', [InterestsController::class, 'destroy'])->middleware('verifyid');
    });
});
