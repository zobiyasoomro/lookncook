<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\PageController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\front\ContactController;
use App\Http\Controllers\Admin\BannerController;

use App\Http\Controllers\front\ProfileController;


// Public Layout Frontend View Channels
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/services', 'services')->name('services');
    Route::get('/cart', 'cart')->name('cart');
    Route::view('/profile', 'pages.profile')->name('profile');
});

Route::post('/contact/store', [ContactController::class, 'store'])->name('contacts.store');
Route::post('/contact-submit', [ContactController::class, 'store'])->name('contact.submit');


Route::post('/contact-submit', [ContactController::class, 'store'])
    ->name('contacts.store');


Route::put('/profile/update', [ProfileController::class, 'update'])
    ->name('profile.update');

//-------------------------------------------------------------- auth routes --------------------------------

Route::get('/login', [AuthController::class, 'showAuthForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register/send-otp', [AuthController::class, 'registerOtp'])->name('register.otp');
Route::post('/register/verify', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/forgot-password/send', [AuthController::class, 'sendResetOtp'])->name('password.forgot.send');
Route::post('/forgot-password/verify', [AuthController::class, 'updatePassword'])->name('password.forgot.submit');

/*
|--------------------------------------------------------------------------
| Protected Master Administrative Console (Secured via Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Core Administrative Entry Point Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    
    // Exact requested matching URI paths: /admin/banner/index, create, edit
    Route::get('/banner/index', [BannerController::class, 'index'])->name('banners.index');
    Route::get('/banner/create', [BannerController::class, 'create'])->name('banners.create');
    Route::post('/banner/store', [BannerController::class, 'store'])->name('banners.store');
    Route::get('/banner/edit/{id}', [BannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banner/update/{id}', [BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banner/destroy/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');


    Route::get('/payment', function () {
        return view('pages.payment_page');
    })->name('payment');

    /* Admin Panel */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors(['email' => 'You do not have administrative privileges to access this area.']);
            }
            // FIXED EXACT PATH
            return view('admin.dashboard');
        })->name('dashboard');
    });


    // this is user profile pagees routes 
    Route::view('/profile', 'pages.profile')->name('profile');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])
        ->name('profile.delete');

});