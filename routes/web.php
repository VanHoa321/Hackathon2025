<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Admin\AdminSiderbarController;
use App\Http\Controllers\admin\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('account.login');
});

//Account
Route::get('/login', [AccountController::class, 'login'])->name('login');
Route::post('/login', [AccountController::class, 'postLogin'])->name('postLogin');

Route::prefix('admin')->middleware("admin")->group(function () {
    Route::prefix('tag')->group(function () {
        Route::get('/index', [TagController::class, 'index'])->name('tag.index');
    });

    Route::prefix('admin-sidebar')->group(function () {
        Route::get('/index', [AdminSiderbarController::class, 'index'])->name('admin-sidebar.index');
    });
});