<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\LocationLevelController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\LocationController;


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

Route::get('/locations', [LocationController::class, 'index']);
Route::get('/postcategory', [PostCategoryController::class, 'index']);

//////////////////////////////////////Auth Routes Start Here///////////////////////////////////////////
Route::middleware(['auth:api'])->get('/jobtitle', [JobTitleController::class, 'index']);

//////////////////////////////////////Admin Routes Start Here///////////////////////////////////////////
Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'scope:user-role-admin']], function () {

    //Job Title CRUD
    Route::prefix('Jobtitle')->group(function () {
        Route::post('/', [JobTitleController::class, 'store']);
        Route::put('/', [JobTitleController::class, 'update']);
        Route::delete('/', [JobTitleController::class, 'destroy']);
    });
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'scope:user-role-admin']], function () {

    //Location Level CRUD
    Route::prefix('Locationlevel')->group(function () {
        Route::get('/', [LocationLevelController::class, 'index']);
        Route::post('/', [LocationLevelController::class, 'store']);
        Route::put('/', [LocationLevelController::class, 'update'])->middleware('verifyid');;
        Route::delete('/', [LocationLevelController::class, 'destroy'])->middleware('verifyid');;
    });
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'scope:user-role-admin']], function () {

    //Post Category CRUD
    Route::prefix('PostCategory')->group(function () {
        Route::post('/', [PostCategoryController::class, 'store']);
        Route::put('/', [PostCategoryController::class, 'update'])->middleware('verifyid');
        Route::delete('/', [PostCategoryController::class, 'destroy'])->middleware('verifyid');
    });
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'scope:user-role-admin']], function () {

    //Location  CRUD
    Route::prefix('location')->group(function () {
        Route::get('GetLocation/{id}', [LocationController::class, 'GetLocationByLocationId']);
        Route::post('/', [LocationController::class, 'store']);
        Route::put('/', [LocationController::class, 'update'])->middleware('verifyid');
        Route::delete('/', [LocationController::class, 'destroy'])->middleware('verifyid');
    });
});
