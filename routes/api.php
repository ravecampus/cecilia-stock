<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeLeaveController;


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

Route::post('signin',[AuthController::class, 'signin']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('list-department', [DepartmentController::class, 'listDepartment']);
    Route::resource('administrator', AdministratorController::class);
    Route::resource('department', DepartmentController::class);
    Route::resource('position', PositionController::class);
    Route::get('leave-type-list', [LeaveTypeController::class, 'leaveTypeList']);
    Route::resource('leave-type', LeaveTypeController::class);
    Route::post('change-password', [UserController::class, 'changePassword']);
    Route::resource('user', UserController::class);
    Route::get('consume-leave', [EmployeeLeaveController::class, 'authConsumeLeave']);
    Route::resource('leave', EmployeeLeaveController::class);
    
});