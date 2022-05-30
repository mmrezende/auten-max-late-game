<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\PaymentPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EULAController;
use App\Http\Controllers\InsightsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\PaymentPlanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationIntervalController;
use App\Http\Controllers\TournamentTypeController;
use App\Http\Controllers\TournamentPlatformController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $builder = PaymentPlan::query()->orderBy('price');
    return view('welcome', ['payment_plans' => $builder->get()]);
});

Route::get('/mercadopago', function () {
    return view('mercadopago');
});

Auth::routes(['verify' => true, 'register' => false]);

// Registration Routes...
Route::get('register/{step?}', [RegisterController::class,'showRegistrationForm'])->name('register');
Route::post('register/{step?}', [RegisterController::class,'register']);

Route::middleware('auth')->group(function () {
    Route::get('/plataforma/{any?}', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->where('any','.*');

    Route::group(['prefix' => 'api'], function () {
        Route::apiResource('user',UserController::class);
        Route::apiResource('tournament',TournamentController::class);
        Route::post('tournament/{tournament}/notification',[TournamentController::class, 'enableNotification']);
        Route::delete('tournament/{tournament}/notification', [TournamentController::class, 'disableNotification']);
        Route::apiResource('payment_plan', PaymentPlanController::class);
        Route::apiResource('tournament_platform',TournamentPlatformController::class);
        Route::apiResource('tournament_type',TournamentTypeController::class);
        Route::apiResource('notification', NotificationController::class);
        Route::post('eula',[EULAController::class,'update']);
        Route::apiResource('payment',PaymentController::class,['only' => ['index', 'show']]);
        Route::apiResource('notification_interval',NotificationIntervalController::class,['only' => ['index', 'store', 'show', 'destroy']]);
        Route::apiResource('ad',AdController::class,['only' => ['index', 'store', 'update', 'destroy']]);
        Route::get('insights',[InsightsController::class,'index']);
    });
});