<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\ResponsibilityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth Route
Route::name('auth.')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('fetch', [AuthController::class, 'fetch'])->name('fetch');
    });
});

// Company API
Route::prefix('company')->middleware('auth:sanctum')->name('company.')->group(function () {
    Route::get('', [CompanyController::class, 'fetch'])->name('fetch');
    Route::post('', [CompanyController::class, 'create'])->name('create');
    Route::post('update/{id}', [CompanyController::class, 'update'])->name('update');
});

// Team API
Route::prefix('team')->middleware('auth:sanctum')->name('team.')->group(function () {
    Route::post('', [TeamController::class, 'create'])->name('create');
    Route::post('update/{id}', [TeamController::class, 'update'])->name('update');
    Route::get('', [TeamController::class, 'fetch'])->name('fetch');
    Route::delete('{id}', [TeamController::class, 'destroy'])->name('destroy');
});

// Role API
Route::prefix('role')->middleware('auth:sanctum')->name('role.')->group(function () {
    Route::post('', [RoleController::class, 'create'])->name('create');
    Route::post('update/{id}', [RoleController::class, 'update'])->name('update');
    Route::get('', [RoleController::class, 'fetch'])->name('fetch');
    Route::delete('{id}', [RoleController::class, 'destroy'])->name('destroy');
});

// Responsibility API
Route::prefix('responsibility')->middleware('auth:sanctum')->name('responsibility.')->group(function () {
    Route::post('', [ResponsibilityController::class, 'create'])->name('create');
    Route::post('update/{id}', [ResponsibilityController::class, 'update'])->name('update');
    Route::get('', [ResponsibilityController::class, 'fetch'])->name('fetch');
    Route::delete('{id}', [ResponsibilityController::class, 'destroy'])->name('destroy');
});

// Employees API
Route::prefix('employee')->middleware('auth:sanctum')->name('employee.')->group(function () {
    Route::post('', [EmployeeController::class, 'create'])->name('create');
    Route::post('update/{id}', [EmployeeController::class, 'update'])->name('update');
    Route::get('', [EmployeeController::class, 'fetch'])->name('fetch');
    Route::delete('{id}', [EmployeeController::class, 'destroy'])->name('destroy');
});
