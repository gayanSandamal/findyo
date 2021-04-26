<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\CommonErrorsConroller;

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

Route::post('/emailregister', [UserController::class, 'emailregister']);
Route::post('/emaillogin', [UserController::class, 'emaillogin']);

//Example protect route with authentication
Route::middleware('auth:api')->get('/test', [UserController::class, 'emailtest']);

//Example protect route with authorization
Route::middleware(['auth:api', 'scope:user-role-admin'])->get('/admin', [UserController::class, 'emailadmin']);

// Route::prefix('admin')->group(function () {
//     Route::get('/users', function () {
//         // Matches The "/admin/users" URL
//     });
// });

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'scope:user-role-admin']], function () {

    //Job Title CRUD
    Route::prefix('Jobtitle')->group(function () {
        Route::get('/', [JobTitleController::class, 'index']);
        Route::post('/', [JobTitleController::class, 'store']);
        Route::put('/{id}', [JobTitleController::class, 'update']);
        Route::delete('/{id}', [JobTitleController::class, 'destroy']);
    });
});
