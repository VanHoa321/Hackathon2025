<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\account\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminSiderbarController;
use App\Http\Controllers\admin\AuthorController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\Admin\DocumentCategoryController;
use App\Http\Controllers\admin\DocumentController;
use App\Http\Controllers\admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\admin\TagController;
use App\Http\Controllers\admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FrontEnd\AccountController as FrontEndAccountController;
use App\Http\Controllers\frontend\AuthorController as FrontendAuthorController;
use App\Http\Controllers\FrontEnd\DocumentController as FrontEndDocumentController;
use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\PostController as FrontendPostController;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::get('/',[HomeController::class, "index"]);

Route::group(['prefix' => 'files-manager'], function () {
    Lfm::routes();
});

//Account
Route::get('/login', [AccountController::class, 'login'])->name('login');
Route::post('/login', [AccountController::class, 'postLogin'])->name('postLogin');
Route::get('/register', [AccountController::class, 'register'])->name('register');
Route::post('/register', [AccountController::class, 'postRegister'])->name('postRegister');
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
    });

    //Admin Customer
    Route::prefix('customer')->group(function () {
        Route::get('/index', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
        Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::post('/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
        Route::post('/change/{id}', [CustomerController::class, 'changeStatus']);
        Route::delete('/destroy/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
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
        Route::get('/list/approve', [DocumentController::class, 'list_approve'])->name('document.approve');
        Route::post('/approve/{id}', [DocumentController::class, 'approve']);
        Route::post('/refuse/{id}', [DocumentController::class, 'refuse']);
        Route::get('/approve/show/{id}', [DocumentController::class, 'showApprove'])->name('document.show-approve');
        Route::get('/show/{id}', [DocumentController::class, 'show'])->name('document.show');
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

    //Slide
    Route::prefix('slide')->group(function () {
        Route::get('/index', [SlideController::class, 'index'])->name('slide.index');
        Route::get('/create', [SlideController::class, 'create'])->name('slide.create');
        Route::post('/store', [SlideController::class, 'store'])->name('slide.store');
        Route::get('/edit/{id}', [SlideController::class, 'edit'])->name('slide.edit');
        Route::post('/update/{id}', [SlideController::class, 'update'])->name('slide.update');
        Route::delete('/destroy/{id}', [SlideController::class, 'destroy']);
        Route::post('/change/{id}', [SlideController::class, 'changeActive']);
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

    //Admin Statistic
    Route::prefix('statistic')->group(function () {
        Route::get('/rating', [StatisticController::class, 'ratingStatistic'])->name('statistic.rating');
        Route::get('/ratings/list/{documentId}', [StatisticController::class, 'ratingList'])->name('admin.ratings.list');
        Route::get('/favourite', [StatisticController::class, 'favouriteStatistic'])->name('statistic.favourite');
        Route::get('/download', [StatisticController::class, 'downloadStatistic'])->name('statistic.download');
        Route::get('/comment', [StatisticController::class, 'commentStatistic'])->name('statistic.comment');
        Route::get('/comments/list/{documentId}', [StatisticController::class, 'commentList'])->name('admin.comments.list');
        Route::get('/user', [StatisticController::class, 'userStatistic'])->name('statistic.user');
        Route::get('/favourites/list/{documentId}', [StatisticController::class, 'favouriteList'])->name('admin.favourites.list');
        Route::get('/user-transaction-overview', [StatisticController::class, 'userTransactionOverview'])->name('statistic.user-transaction.overview');
        Route::get('/user-transaction-statistic/{userId}', [StatisticController::class, 'userTransactionDetail'])->name('statistic.user-transaction.detail');
        Route::get('/document-transaction-overview', [StatisticController::class, 'documentTransactionOverview'])->name('statistic.document-transaction-overview');
        Route::get('/document-transaction-detail/{documentId}', [StatisticController::class, 'documentTransactionDetail'])->name('statistic.document-transaction-detail');
        
    });

    //Admin Contact
    Route::prefix('admin')->group(function () {
        Route::get('contact', [ContactController::class, 'index'])->name('admin.contact.index');
        Route::get('contact/{id}', [ContactController::class, 'show'])->name('admin.contact.show');
        Route::delete('contact/{id}', [ContactController::class, 'destroy'])->name('admin.contact.destroy');
        Route::post('contact/mark-read/{id}', [ContactController::class, 'markAsRead'])->name('admin.contact.mark-read');
        Route::post('contact/mark-unread/{id}', [ContactController::class, 'markAsUnread'])->name('admin.contact.mark-unread');
    });

    Route::prefix('transaction')->group(function () {
        Route::get('/index', [TransactionController::class, 'index'])->name('admin.tran.index');
    });

    Route::prefix('setting')->group(function () {
        Route::get('/index', [SettingController::class, 'index'])->name('setting.index');
        Route::post('/update', [SettingController::class, 'update'])->name('setting.update');
    });

    Route::prefix('home')->group(function () {
        Route::get('/index', [AdminHomeController::class, 'index'])->name('home.index');
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
});

//Frontend
Route::get('/home', [HomeController::class, 'index'])->name('frontend.home.index');
Route::get('/about-us', [HomeController::class, 'about'])->name('frontend.home.about-us');
Route::get('/contact-us', [HomeController::class, 'contact'])->name(name: 'frontend.home.contact-us');
Route::post('/contact-us/send', [HomeController::class, 'sendContact'])->middleware('auth')->name('frontend.contact-us.send');
Route::post('admin/contact/{id}/reply', [ContactController::class, 'reply'])->name('admin.contact.reply');

//Frontend Document
Route::get('/document', [FrontEndDocumentController::class, 'index'])->name('frontend.document.index');
Route::get('/document/getData', [FrontEndDocumentController::class, 'getData']);
Route::get('/document-details/{id}', [FrontEndDocumentController::class, 'details'])->name('frontend.document.details');
Route::post('/document/comment', [FrontEndDocumentController::class, 'comment'])->name('frontend.document.comment');
Route::get('/document/download/{id}', [FrontEndDocumentController::class, 'download'])->name('frontend.document.download');
Route::get('/document/download-pdf/{id}', [FrontEndDocumentController::class, 'downloadPDF'])->name('frontend.document.downloadPDF');
Route::post('/document/{id}/rate', [FrontEndDocumentController::class, 'rate'])->name('frontend.document.rate');
Route::delete('/document/{id}/unrate', [FrontEndDocumentController::class, 'unrate'])->name('frontend.document.unrate');

//Frontend Post
Route::get('/post', [FrontendPostController::class, 'index'])->name('frontend.post.index');
Route::get('/posts/{id}', [FrontendPostController::class, 'show'])->name('frontend.posts.show');

//Frontend Author
Route::get('/author', [FrontendAuthorController::class, 'index'])->name('frontend.author.index');
Route::get('/author/{id}', [FrontEndAuthorController::class, 'show'])->name('frontend.author.details');


Route::prefix('account')->middleware("auth")->group(function () {
    Route::get('/profile', [FrontEndAccountController::class, 'profile'])->name('frontend.profile');
    Route::get('/edit-profile', [FrontEndAccountController::class, 'editProfile'])->name('frontend.edit-profile');
    Route::post('/update-profile', [FrontEndAccountController::class, 'updateProfile'])->name('frontend.update-profile');
    Route::get('/change-password', [FrontEndAccountController::class, 'editPassword'])->name('frontend.edit-password');
    Route::post('/update-password', [FrontEndAccountController::class, 'updatePassword'])->name('frontend.update-password');
    Route::get('/my-favourite', [FrontEndAccountController::class, 'myFavourite'])->name('frontend.my-favourite');
    Route::post('/favourite/{id}', [FrontEndAccountController::class, 'addFavourite'])->name('frontend.add-favourite');
    Route::delete('/favourite/{id}', [FrontEndAccountController::class, 'removeFavourite'])->name('frontend.remove-favourite');
    Route::get('/my-document', [FrontEndAccountController::class, 'myDocument'])->name('frontend.mydocument');
    Route::get('/uploads', [FrontEndAccountController::class, 'uploads'])->name('frontend.uploads');
    Route::post('/post-upload', [FrontEndAccountController::class, 'postUpload'])->name('frontend.post-upload');
    Route::delete('/destroy/{id}', [FrontEndAccountController::class, 'destroy'])->name('frontend.document.destroy');
    Route::get('/point', [FrontEndAccountController::class, 'indexPoint'])->name('frontend.point');
    Route::post('/deposit', [FrontendAccountController::class, 'vnpay_payment'])->name('frontend.deposit');
    Route::get('/vnpay-return', [FrontendAccountController::class, 'vnpayReturn'])->name('vnpay.return');
    Route::get('/transaction-sucess', [FrontEndAccountController::class, 'vnpSuccess'])->name('frontend.success');
    Route::get('/document/purchase/{id}', [FrontEndDocumentController::class, 'purchase'])->name('frontend.purchase');
    Route::get('/transaction-history', [FrontEndAccountController::class, 'tranHistory'])->name('frontend.tran-history');
});