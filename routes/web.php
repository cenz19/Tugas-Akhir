<?php

use App\Http\Controllers\DatasetController;
use App\Http\Controllers\LawController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ScrapeController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Auth
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');


//post, law, & dataset
Route::middleware('auth')->group(function () {
    Route::resource('post', PostController::class);
    Route::post('post/predict', [PostController::class, 'predict'])->name('post.predict');
    Route::post('post/sendPost', [PostController::class,'sendPost'])->name('post.sendPost');

    Route::resource('law', LawController::class);
    Route::resource('dataset', DatasetController::class);
});


//Scrape
Route::middleware(['auth', 'role:pakar'])->group(function () {
    Route::resource('scrape', ScrapeController::class);
    Route::post('/getscrapedata', [ScrapeController::class, 'getscrapedata'])->name('scrape.getscrapedata');
    Route::post('/update-label', [ScrapeController::class, 'updateLabel'])->name('scrape.updateLabel');
    Route::post('/retrain', [ScrapeController::class, 'retrain'])->name('scrape.retrain');
});

