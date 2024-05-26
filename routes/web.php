<?php

use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



//group route
Route::prefix('account')->group(function () {

    //for not auth

    Route::middleware(['guest'])->group(function () {

        //for login
        Route::get('login', [LoginController::class, 'index'])->name('account.login');
        Route::post('authenticate', [LoginController::class, 'authenticate'])->name('account.authenticate');

        //for register
        Route::get('register', [LoginController::class, 'register'])->name('account.register');
        Route::post('process-register', [LoginController::class, 'processRegister'])->name('account.processRegister');
    });

    //foe auth middlware
    Route::middleware(['auth'])->group(function () {

        //for dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('account.dashboard');


        //for logout
        Route::get('logout', [LoginController::class, 'logout'])->name('account.logout');
    });
});




Route::prefix('admin')->group(function () {

    Route::middleware(['admin.guest'])->group(function () {
        //For Admin
        //for login
        Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::middleware(['admin.auth'])->group(function () {

        //for dashboard
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        //for logout
        Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    });
});
