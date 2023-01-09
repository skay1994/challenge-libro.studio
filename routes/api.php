<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{CourseController, UserController, RegisterController};

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

Route::resource('users', UserController::class);
Route::resource('courses', CourseController::class);
Route::prefix('registries')->name('registries.')->group(function () {
    Route::post('add', [RegisterController::class, 'add'])->name('add');
    Route::post('remove', [RegisterController::class, 'remove'])->name('remove');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
