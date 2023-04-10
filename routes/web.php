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
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\SurveyController;

Auth::routes();

Route::group(['middleware' => ['auth', 'profile']], function () {


    // Admin Panel
    Route::group(['middleware' => ['admin']], function () {

        // AdminController
        Route::get('admin/panel/users', [AdminController::class, 'users'])->name('admin.panel.users');
        Route::get('admin/panel/activities', [AdminController::class, 'activities'])->name('admin.panel.activities');
        Route::get('admin/panel/incomes', [AdminController::class, 'incomes'])->name('admin.panel.incomes');

        // UserController
        Route::get('user/add', [UserController::class, 'add_user'])->name('user.add');
        Route::post('user/store', [UserController::class, 'store_user'])->name('user.store');

        //        Activities
        Route::get('cs/activities/{id}', [ActivityController::class, 'cs'])->name('cs.activities');

    });


    Route::group(['middleware' => ['cs']], function () {

        //    CS Panel
        Route::get('admin/panel', [AdminController::class, 'index'])->name('admin.panel');
        Route::get('admin/panel/orders', [AdminController::class, 'orders'])->name('admin.panel.orders');
        Route::get('admin/panel/pending/orders', [AdminController::class, 'pending_orders'])->name('admin.panel.pending.orders');
        Route::get('admin/panel/awaits/orders', [AdminController::class, 'awaits_orders'])->name('admin.panel.awaits.orders');
        Route::get('admin/panel/confirmed/orders', [AdminController::class, 'confirmed_orders'])->name('admin.panel.confirmed.orders');
        Route::get('admin/panel/finished/orders', [AdminController::class, 'finished_orders'])->name('admin.panel.finished.orders');
        Route::get('admin/panel/go_back/orders', [AdminController::class, 'go_back_orders'])->name('admin.panel.go_back.orders');
        Route::get('admin/panel/drivers', [AdminController::class, 'drivers'])->name('admin.panel.drivers');
        Route::get('admin/panel/cars', [AdminController::class, 'cars'])->name('admin.panel.cars');
        Route::get('admin/panel/orders_car', [AdminController::class, 'orders_car'])->name('admin.panel.orders_car');
        Route::get('admin/panel/clients', [AdminController::class, 'clients'])->name('admin.panel.clients');
        Route::get('cs/analytics/{id}', [AdminController::class, 'analytics'])->name('cs.analytics');


//        Activities
        Route::get('user/activities/{id}', [ActivityController::class, 'user'])->name('user.activities');
        Route::get('driver/activities/{id}', [ActivityController::class, 'driver'])->name('driver.activities');
        Route::get('cs/activities/{id}', [ActivityController::class, 'cs'])->name('cs.activities');
        Route::get('seen/all', [ActivityController::class, 'seen'])->name('seen.all');


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


        // DriverController
        Route::get('admin/panel/driver/{id}', [DriverController::class, 'info_driver'])->name('admin.panel.driver');
        Route::put('admin/panel/driver/update/{id}', [DriverController::class, 'update_driver'])->name('admin.panel.driver.update');

        // UserController
        Route::get('admin/panel/user/{user}', [UserController::class, 'info_user'])->name('admin.panel.user');
        Route::put('admin/panel/user/update/{user}', [UserController::class, 'update_user'])->name('admin.panel.user.update');
        Route::get('client/create', [UserController::class, 'add_client'])->name('client.create');
        Route::post('client/store', [UserController::class, 'store_client'])->name('client.store');
        Route::get('user/delete/{id}', [UserController::class, 'delete_user'])->name('user.delete');
        Route::get('user/block/{id}', [UserController::class, 'block_user'])->name('user.block');
        Route::get('user/remove_block/{id}', [UserController::class, 'remove_block_user'])->name('user.remove_block');
        Route::get('user/vip/{id}', [UserController::class, 'vip_user'])->name('user.vip');
        Route::get('user/remove_vip/{id}', [UserController::class, 'remove_vip_user'])->name('user.remove_vip');

    });

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
        Route::get('driver/panel/orders', [DriverController::class, 'orders'])->name('driver.panel.orders');
        Route::get('driver/panel/pending/orders', [DriverController::class, 'pending_orders'])->name('driver.panel.pending.orders');
        Route::get('driver/panel/finished/orders', [DriverController::class, 'done_orders'])->name('driver.panel.finished.orders');
        Route::get('driver/analytics/{id}', [DriverController::class, 'analytics'])->name('driver.analytics');
    });


    // User Info
    Route::get('profile', [UserController::class, 'index'])->name('profile')->middleware('block');
    Route::put('user/update', [UserController::class, 'update'])->name('user.update')->middleware('block');
    Route::get('user/get_otp', [UserController::class, 'get_otp'])->name('user.get_otp');
    Route::post('user/active/otp', [UserController::class, 'otp'])->name('user.active.otp');


    // Driver
    Route::get('driver/profile/{id}', [DriverController::class, 'show'])->name('driver.profile');


    // Quiz
    Route::get('quizzes', [SurveyController::class, 'index'])->name('quizzes')->middleware('block');
    Route::get('quiz/create', [SurveyController::class, 'create'])->name('quiz.create')->middleware('block');
    Route::post('quiz/store', [SurveyController::class, 'store'])->name('quiz.store')->middleware('block');
    Route::put('quiz/update/{slug}', [SurveyController::class, 'update'])->name('quiz.update')->middleware('block');
    Route::get('quiz/show/{slug}', [SurveyController::class, 'show'])->name('quiz.show')->middleware('block');
    Route::get('quiz/edit/{slug}', [SurveyController::class, 'edit'])->name('quiz.edit')->middleware('block');
    Route::get('quiz/cancel/{id}', [SurveyController::class, 'cancel'])->name('quiz.cancel');
    Route::get('quiz/success/{id}', [SurveyController::class, 'succeed'])->name('quiz.success');


    //        Activities
    Route::get('seen/all', [ActivityController::class, 'seen'])->name('seen.all');
});


//        Google Authentication
Route::get('auth_redirect', [SocialAuthController::class, 'redirect'])->name('auth_redirect');
Route::get('auth_callback', [SocialAuthController::class, 'callback'])->name('auth_callback');


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
