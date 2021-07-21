<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\BackIndexController;
use App\Http\Controllers\BackstageController;


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

Route::get('/', [LoginController::class, 'show_page']);
Route::post('/login_check', [LoginController::class, 'login_check']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::post('/send_contect', [IndexWebController::class, 'send_contect']);

Route::get('/error_return', function() {
	$json['error_return'] = true;
	return $json;
});

Route::group(['middleware' => ['user_authentication']], function () {
	// 會員資料
	Route::get('/index', [BackIndexController::class, 'index']);
	Route::post('/index/json', [BackIndexController::class, 'index_json']);
	// ./會員資料

	// 會計系統
	Route::get('/Accounting/info', [AccountingController::class, 'info']);
	Route::post('/Accounting/info/json', [AccountingController::class, 'info_json']);

	Route::get('/Accounting/edit_member', [AccountingController::class, 'edit_member']);
	Route::post('/Accounting/edit_member/json', [AccountingController::class, 'edit_member_json']);
	
	Route::get('/Accounting/edit_mou', [AccountingController::class, 'edit_mou']);
	Route::post('/Accounting/edit_mou/json', [AccountingController::class, 'edit_mou_json']);
	
	Route::get('/Accounting/search_member_fee', [AccountingController::class, 'search_member_fee']);
	Route::post('/Accounting/search_member_fee/json', [AccountingController::class, 'search_member_fee_json']);
	
	Route::get('/Accounting/search_labor_fee', [AccountingController::class, 'search_labor_fee']);
	Route::post('/Accounting/search_labor_fee/json', [AccountingController::class, 'search_labor_fee_json']);
	// ./會計系統

	
	Route::get('/backstage_management/admin_management', [BackstageController::class, 'backstage_management']);
	Route::get('/backstage_management/auth_management', [BackstageController::class, 'auth_management']);
	Route::get('/backstage_management/admin_authority', [BackstageController::class, 'admin_authority']);
	// Route::get('/backstage_management/reset_pwd_index', [BackstageController::class, 'reset_pwd_index']);
	Route::post('/backstage_management/json', [BackstageController::class, 'backstage_management_json']);
});
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
