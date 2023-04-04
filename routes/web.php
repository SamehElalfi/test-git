<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\SurveyController;

Auth::routes();

Route::group(['middleware' => ['auth', 'profile']], function () {


    // Admin Panel
    Route::group(['middleware' => ['admin']], function () {

        // AdminController
        Route::get('admin/panel/users', [AdminController::class, 'users'])->name('admin.panel.users');

        // UserController
        Route::get('admin/panel/user/{user}', [UserController::class, 'info_user'])->name('admin.panel.user');
        Route::put('admin/panel/user/update/{user}', [UserController::class, 'update_user'])->name('admin.panel.user.update');

        // DriverController
        Route::get('admin/panel/driver/{id}', [DriverController::class, 'info_driver'])->name('admin.panel.driver');
        Route::put('admin/panel/driver/update/{id}', [DriverController::class, 'update_driver'])->name('admin.panel.driver.update');
    });


    Route::group(['middleware' => ['cs']], function () {

        //    CS Panel
        Route::get('admin/panel', [AdminController::class, 'index'])->name('admin.panel');
        Route::get('admin/panel/orders', [AdminController::class, 'orders'])->name('admin.panel.orders');
        Route::get('admin/panel/pending/orders', [AdminController::class, 'pending_orders'])->name('admin.panel.pending.orders');
        Route::get('admin/panel/awaits/orders', [AdminController::class, 'awaits_orders'])->name('admin.panel.awaits.orders');
        Route::get('admin/panel/confirmed/orders', [AdminController::class, 'confirmed_orders'])->name('admin.panel.confirmed.orders');
        Route::get('admin/panel/finished/orders', [AdminController::class, 'finished_orders'])->name('admin.panel.finished.orders');
        Route::get('admin/panel/drivers', [AdminController::class, 'drivers'])->name('admin.panel.drivers');
        Route::get('admin/panel/cars', [AdminController::class, 'cars'])->name('admin.panel.cars');
        Route::get('admin/panel/clients', [AdminController::class, 'clients'])->name('admin.panel.clients');
        Route::get('cs/analytics/{id}', [AdminController::class, 'analytics'])->name('cs.analytics');


//        Activities
        Route::get('user/activities/{id}', [ActivityController::class, 'user'])->name('user.activities');
        Route::get('driver/activities/{id}', [ActivityController::class, 'driver'])->name('driver.activities');
        Route::get('cs/activities/{id}', [ActivityController::class, 'cs'])->name('cs.activities');


        // Quiz
        Route::get('quiz/process/{id}', [SurveyController::class, 'process'])->name('quiz.process');
        Route::post('quiz/process/change/{id}', [SurveyController::class, 'change_status'])->name('quiz.process.change')->middleware("order_status");
        Route::put('quiz/process/confirm/{id}', [SurveyController::class, 'confirm'])->name('quiz.process.confirm');
        Route::get('quiz/add/solid', [SurveyController::class, 'add_solid'])->name('quiz.add.solid');
        Route::post('quiz/store/solid', [SurveyController::class, 'store_solid'])->name('quiz.store.solid');
        Route::get('quiz/add/multiple', [SurveyController::class, 'add_multiple'])->name('quiz.add.multiple');
        Route::post('quiz/store/multiple', [SurveyController::class, 'store_multiple'])->name('quiz.store.multiple');


        // Car
        Route::get('admin/panel/car/create', [CarController::class, 'create'])->name('admin.panel.car.create');
        Route::post('admin/panel/car/store', [CarController::class, 'store'])->name('admin.panel.car.store');
        Route::get('admin/panel/car/delete/{id}', [CarController::class, 'destroy'])->name('admin.panel.car.delete');
        Route::get('admin/panel/car/edit/{id}', [CarController::class, 'edit'])->name('admin.panel.car.edit');
        Route::put('admin/panel/car/update/{id}', [CarController::class, 'update'])->name('admin.panel.car.update');
    });


    // User Info
    Route::get('profile', [UserController::class, 'index'])->name('profile');
    Route::put('user/update', [UserController::class, 'update'])->name('user.update');


    Route::group(['middleware' => ['analytics']], function () {

        // User Info
        Route::get('user/analytics/{id}', [UserController::class, 'analytics'])->name('user.analytics');
    });


    Route::group(['middleware' => ['driver']], function () {

        //    Driver
        Route::get('driver/profile/edit', [DriverController::class, 'index'])->name('driver.profile.edit');
        Route::put('driver/profile/update', [DriverController::class, 'update'])->name('driver.profile.update');
        Route::get('driver/panel', [DriverController::class, 'panel'])->name('driver.panel');
        Route::get('driver/panel/users', [DriverController::class, 'users'])->name('driver.panel.users');
        Route::get('driver/panel/orders', [DriverController::class, 'surveys'])->name('driver.panel.orders');
        Route::get('driver/analytics/{id}', [DriverController::class, 'analytics'])->name('driver.analytics');
    });


    // Driver
    Route::get('driver/profile/{id}', [DriverController::class, 'show'])->name('driver.profile');


    // Quiz
    Route::get('quizzes', [SurveyController::class, 'index'])->name('quizzes');
    Route::get('quiz/create', [SurveyController::class, 'create'])->name('quiz.create');
    Route::post('quiz/store', [SurveyController::class, 'store'])->name('quiz.store');
    Route::put('quiz/update/{slug}', [SurveyController::class, 'update'])->name('quiz.update');
    Route::get('quiz/show/{slug}', [SurveyController::class, 'show'])->name('quiz.show');
    Route::get('quiz/edit/{slug}', [SurveyController::class, 'edit'])->name('quiz.edit');
    Route::get('quiz/cancel/{id}', [SurveyController::class, 'cancel'])->name('quiz.cancel');
    Route::get('quiz/success/{id}', [SurveyController::class, 'succeed'])->name('quiz.success');
});


// Static Pages
Route::get('/', [HomeController::class, 'index'])->name('');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('terms', [StaticController::class, 'terms'])->name('terms');
Route::get('privacy', [StaticController::class, 'privacy'])->name('privacy');
Route::get('about_us', [StaticController::class, 'about_us'])->name('about_us');
Route::get('contact_us', [StaticController::class, 'contact_us'])->name('contact_us');
Route::post('contact_store', [StaticController::class, 'contact_store'])->name('contact_store');




// Test pages
Route::get('test1', [HomeController::class, 'test'])->name('test1');
