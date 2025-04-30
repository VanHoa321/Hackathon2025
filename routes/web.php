<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\account\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminSiderbarController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::get('/',[AccountController::class, "login"]);

Route::group(['prefix' => 'files-manager'], function () {
    Lfm::routes();
});

//Account
Route::get('/login', [AccountController::class, 'login'])->name('login');
Route::post('/login', [AccountController::class, 'postLogin'])->name('postLogin');
Route::get('/logout', [AccountController::class, 'logout'])->name('logout');

//Reset Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/new-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

Route::prefix('admin')->middleware("admin")->group(function () {

    //Profile
    Route::get('/profile', [AccountController::class, 'profile']) ->name('profile');
    Route::get('/edit-profile', [AccountController::class, 'editProfile']) ->name('edit-profile');
    Route::post('/profile/update', [AccountController::class, 'updateProfile']) -> name('updateProfile');
    Route::get('/change-password', [AccountController::class, 'editPassword']) ->name('editPassword');
    Route::post('/update-password', [AccountController::class, 'updatePassword']) -> name('updatePassword');

    //Tag
    Route::prefix('tag')->group(function () {
        Route::get('/index', [TagController::class, 'index'])->name('tag.index');
    });

    //Admin Sidebar
    Route::prefix('admin-sidebar')->group(function () {
        Route::get('/index', [AdminSiderbarController::class, 'index'])->name('admin-sidebar.index');
        Route::get('/create', [AdminSiderbarController::class, 'create'])->name('admin-sidebar.create');
        Route::post('/store', [AdminSiderbarController::class, 'store'])->name('admin-sidebar.store');
        Route::get('/edit/{id}', [AdminSiderbarController::class, 'edit'])->name('admin-sidebar.edit');
        Route::post('/update/{id}', [AdminSiderbarController::class, 'update'])->name('admin-sidebar.update');
        Route::delete('/destroy/{id}', [AdminSiderbarController::class, 'destroy']);
        Route::post('/change/{id}', [AdminSiderbarController::class, 'changeActive']);
    });

    //Admin User
    Route::prefix('user')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/show/{id}', [UserController::class, 'show'])->name("user.show");
        Route::post('/change/{id}', [UserController::class, 'changeActive']);
    });

    //Tag
    Route::prefix('tag')->group(function () {
        Route::get('/index', [TagController::class, 'index'])->name('tag.index');
        Route::get('/create', [TagController::class, 'create'])->name('tag.create');
        Route::post('/blog/store', [TagController::class, 'store'])->name('tag.store'); 
        Route::get('/edit/{id}', [TagController::class, 'edit'])->name('tag.edit');
        Route::post('/update/{id}', [TagController::class, 'update'])->name('tag.update');
        Route::post('/change/{id}', [TagController::class, 'changeActive'])->name('tag.change');
        Route::delete('/destroy/{id}', [TagController::class, 'destroy'])->name('tag.destroy');
    });

    //Admin Post
    Route::prefix('post')->group(function () {
        Route::get('/index', [PostController::class, 'index'])->name('admin-post.index');
        Route::get('/create', [PostController::class, 'create'])->name('admin-post.create');
        Route::post('/blog/store', [PostController::class, 'store'])->name('admin-post.store'); 
        Route::get('/edit/{id}', [PostController::class, 'edit'])->name('admin-post.edit');
        Route::put('/update/{id}', [PostController::class, 'update'])->name('admin-post.update');
        Route::post('/change/{id}', [PostController::class, 'changeStatus'])->name('admin-post.change');
        Route::delete('/destroy/{id}', [PostController::class, 'destroy'])->name('admin-post.destroy');
    });

    //Product Category
    Route::prefix('category')->group(function () {
        Route::get('/index', [ProductCategoryController::class, 'index'])->name('category.index');
        Route::get('/create', [ProductCategoryController::class, 'create'])->name('category.create');
        Route::post('/store', [ProductCategoryController::class, 'store'])->name('category.store'); 
        Route::get('/edit/{id}', [ProductCategoryController::class, 'edit'])->name('category.edit');
        Route::post('/update/{id}', [ProductCategoryController::class, 'update'])->name('category.update');
        Route::post('/change/{id}', [ProductCategoryController::class, 'changeActive'])->name('category.change');
        Route::delete('/destroy/{id}', [ProductCategoryController::class, 'destroy'])->name('category.destroy');
    });

    //Product
    Route::prefix('product')->group(function () {
        Route::get('/index', [ProductController::class, 'index'])->name('product.index');
        Route::get('/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/store', [ProductController::class, 'store'])->name('product.store'); 
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::post('/change/{id}', [ProductController::class, 'changeActive'])->name('product.change');
        Route::delete('/destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    });
});