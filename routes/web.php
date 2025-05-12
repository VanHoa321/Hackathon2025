<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\account\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminSiderbarController;
use App\Http\Controllers\admin\AuthorController;
use App\Http\Controllers\Admin\DocumentCategoryController;
use App\Http\Controllers\admin\DocumentController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\PostController as FrontendPostController;
use App\Http\Controllers\frontend\ProductController as FrontendProductController;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::get('/',[HomeController::class, "index"]);

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

    //Admin User
    Route::prefix('user')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/show/{id}', [UserController::class, 'show'])->name("user.show");
        Route::post('/change/{id}', [UserController::class, 'changeActive']);
    });

    //Document Category
    Route::prefix('document-category')->group(function () {
        Route::get('/index', [DocumentCategoryController::class, 'index'])->name('document-category.index');
        Route::get('/create', [DocumentCategoryController::class, 'create'])->name('document-category.create');
        Route::post('/store', [DocumentCategoryController::class, 'store'])->name('document-category.store'); 
        Route::get('/edit/{id}', [DocumentCategoryController::class, 'edit'])->name('document-category.edit');
        Route::post('/update/{id}', [DocumentCategoryController::class, 'update'])->name('document-category.update');
        Route::post('/change/{id}', [DocumentCategoryController::class, 'changeActive'])->name('document-category.change');
        Route::delete('/destroy/{id}', [DocumentCategoryController::class, 'destroy'])->name('document-category.destroy');
    });

    //Publisher
    Route::prefix('publisher')->group(function () {
        Route::get('/index', [PublisherController::class, 'index'])->name('publisher.index');
        Route::get('/create', [PublisherController::class, 'create'])->name('publisher.create');
        Route::post('/store', [PublisherController::class, 'store'])->name('publisher.store'); 
        Route::get('/edit/{id}', [PublisherController::class, 'edit'])->name('publisher.edit');
        Route::post('/update/{id}', [PublisherController::class, 'update'])->name('publisher.update');
        Route::post('/change/{id}', [PublisherController::class, 'changeActive'])->name('publisher.change');
        Route::delete('/destroy/{id}', [PublisherController::class, 'destroy'])->name('publisher.destroy');
    });

    //Author
    Route::prefix('author')->group(function () {
        Route::get('/index', [AuthorController::class, 'index'])->name('author.index');
        Route::get('/create', [AuthorController::class, 'create'])->name('author.create');
        Route::post('/store', [AuthorController::class, 'store'])->name('author.store'); 
        Route::get('/edit/{id}', [AuthorController::class, 'edit'])->name('author.edit');
        Route::post('/update/{id}', [AuthorController::class, 'update'])->name('author.update');
        Route::post('/change/{id}', [AuthorController::class, 'changeActive'])->name('author.change');
        Route::delete('/destroy/{id}', [AuthorController::class, 'destroy'])->name('author.destroy');
    });

    //Document
    Route::prefix('document')->group(function () {
        Route::get('/index', [DocumentController::class, 'index'])->name('document.index');
        Route::get('/create', [DocumentController::class, 'create'])->name('document.create');
        Route::post('/store', [DocumentController::class, 'store'])->name('document.store'); 
        Route::get('/edit/{id}', [DocumentController::class, 'edit'])->name('document.edit');
        Route::post('/update/{id}', [DocumentController::class, 'update'])->name('document.update');
        Route::post('/change/{id}', [DocumentController::class, 'changeActive'])->name('document.change');
        Route::delete('/destroy/{id}', [DocumentController::class, 'destroy'])->name('document.destroy');
    });

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


//Frontend
Route::get('/home', [HomeController::class, 'index'])->name('frontend.home.index');
Route::get('/about-us', [HomeController::class, 'about'])->name('frontend.home.about-us');

//Frontend Product
Route::get('/product', [FrontendProductController::class, 'index'])->name('frontend.product.index');
Route::get('/product/getData', [FrontendProductController::class, 'getData']);

//Frontend Post
Route::get('/post', [FrontendPostController::class, 'index'])->name('frontend.post.index');
