<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware(['jwt.auth'])->group(function () {
    Route::get('students', [StudentController::class, 'index']);
// Route::get('students', [StudentController::class, 'index']);
Route::post('students/save', [StudentController::class, 'store']);
Route::get('students/{id}', [StudentController::class, 'show']);
Route::get('students/edit/{id}', [StudentController::class, 'edit']);
Route::put('students/update/{id}', [StudentController::class, 'update']);
Route::delete('students/delete/{id}', [StudentController::class, 'destroy']);

// });


Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
Route::post('register', [AuthController::class,'register']);   
Route::post('login', [AuthController::class,'login']);
Route::post('logout', [AuthController::class,'logout']);
Route::post('refresh', [AuthController::class,'refresh']);
Route::post('me', [AuthController::class,'me']);

});

// Route::get('students', function() {
//     return 'this is students api';
// });