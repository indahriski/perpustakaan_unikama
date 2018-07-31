<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::resource('books','BookController');
Route::resource('transactions','TransactionController');
Route::resource('users','UserController');
Route::resource('members','MemberController');
Route::resource('petugas','petugasController');
Route::get('/kirim-email/{id}','petugasController@kirim_email');

Route::post('petugas-update/{id}','petugasController@update');
// Route::get('getList-books','BookController@getList');
Route::get('/getlist-books','Pages\BookController@getList')->name('api.getlist-books');
Route::get('/getlist-users','Pages\UserController@getList')->name('api.getlist-users');
Route::get('/getlist-petugas','Pages\petugasController@getList')->name('api.getlist-petugas');
Route::post('post-login', 'Auth\LoginController@postLogin')->name('api.login');
Route::get('logout', 'Auth\LoginController@getLogout')->name('api.logout');
// Route::get('getList-users','UserController@getList');
Route::group(['namespace' => 'cetak'], function () {
    Route::get('/cetak_petugas', 'cetakpetugas@index');

        Route::get('/cetak_petugas1/{id}', 'cetakpetugas1@index');
});

Route::get('/kirim-sms/{to}', function(\Nexmo\Client $nexmo, $to){
    $petugas = \DB::table('petugas')->where('id',$to)->first();
    try{
    $message = $nexmo->message()->send([
        'to' => $petugas->tlp_petugas,
        'from' => '@leggetter',
        'text' => 'SELAMAT BERGABUNG'
    ]);
    return response()->json(['deleted' => true], 200);

    // Log::info('sent message: ' . $message1['message-id']);
    }
    catch (\Exception $e) {
        // store errors to log
        \Log::error('class :  method : kirim_sms | ' . $e);
        return $e;
    }
});

 