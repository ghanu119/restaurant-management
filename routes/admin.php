<?php

use Illuminate\Support\Facades\Route;

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
Route::post('select2/meta-keywords', '\App\Http\Controllers\HelperController@select2MetaKeywords')->name('select2.meta_keywords');
Route::post('select2/category', '\App\Http\Controllers\HelperController@select2Category')->name('select2.category');

Route::post('upload-media', 'UploadController@uploadMedia')->name('admin.upload_media');

Route::group(['middleware' => 'unauth'], function () {
    Route::get('login', 'LoginController@login')->name('admin.login');
    Route::post('login', 'LoginController@doLogin')->name('admin.post_login');
    Route::get('forgot-password', 'LoginController@forgotPassword')->name('admin.forgot_password');
    Route::post('forgot-password', 'LoginController@sendResetPasswordLink')->name('admin.post_forgot_password');
    Route::get('reset-password/{token}', 'LoginController@showResetPassword')->name('admin.show_reset_password');
    Route::post('reset-password', 'LoginController@resetPassword')->name('admin.post_reset_passeword');
});

Route::group(['middleware' => 'auth:admin'], function () {

    Route::redirect('/', 'admin/dashboard', 301)->name('admin.index');

    Route::get('logout', 'LoginController@logout')->name('admin.logout');
    Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');

    //Admin Profile
    Route::prefix('profile')->group( function(){

        $profileController = 'ProfileController@';

        Route::get('/', $profileController.'index')->name('admin.profile');
        Route::post('store', $profileController.'store')->name('admin.profile.post_data');
        Route::get('change-password', $profileController.'changePassword')->name('admin.profile.change_password');
        Route::post('change-password/update', $profileController.'updatePassword')->name('admin.profile.change_password.post_data');

    });

    //Category
    Route::prefix('category')->group( function(){

        $controller = 'CategoryController@';

        Route::get('/', $controller.'index')->name('admin.category');
        Route::post('ajax-data', $controller.'ajaxTableData')->name('admin.category.ajax_data');
        Route::get('create', $controller.'create')->name('admin.category.create');
        Route::get('{category}/edit', $controller.'edit')->name('admin.category.edit');
        Route::post('store', $controller.'store')->name('admin.category.post_data');
        Route::post('{category}/change-status', $controller.'changeStatus')->name('admin.category.change_status');
        Route::post('{category}/delete', $controller.'delete')->name('admin.category.delete');
    });

    //Product Route
    Route::prefix('products')->group( function(){

        $controller = 'ProductController@';

        Route::get('/', $controller.'index')->name('admin.product');
        Route::post('ajax-data', $controller.'ajaxTableData')->name('admin.product.ajax_data');
        Route::get('create', $controller.'create')->name('admin.product.create');
        Route::get('{product}/edit', $controller.'edit')->name('admin.product.edit');
        Route::post('store', $controller.'store')->name('admin.product.post_data');
        Route::post('{product}/change-status', $controller.'changeStatus')->name('admin.product.change_status');
        Route::post('{product}/delete', $controller.'delete')->name('admin.product.delete');
    });

    //Extra Charges Route
    Route::prefix('extra-charges')->group( function(){

        $controller = 'ExtraChargeController@';

        Route::get('/', $controller.'index')->name('admin.extra_charge');
        Route::post('ajax-data', $controller.'ajaxTableData')->name('admin.extra_charge.ajax_data');
        Route::get('create', $controller.'create')->name('admin.extra_charge.create');
        Route::get('{extraCharge}/edit', $controller.'edit')->name('admin.extra_charge.edit');
        Route::post('store', $controller.'store')->name('admin.extra_charge.post_data');
        Route::post('{extraCharge}/change-status', $controller.'changeStatus')->name('admin.extra_charge.change_status');
        Route::post('{extraCharge}/delete', $controller.'delete')->name('admin.extra_charge.delete');
    });

    //Tables Route
    Route::prefix('tables')->group( function(){

        $controller = 'TablesController@';

        Route::get('/', $controller.'index')->name('admin.table');
        Route::post('ajax-data', $controller.'ajaxTableData')->name('admin.table.ajax_data');
        Route::get('create', $controller.'create')->name('admin.table.create');
        Route::get('{table}/edit', $controller.'edit')->name('admin.table.edit');
        Route::post('store', $controller.'store')->name('admin.table.post_data');
        Route::post('{table}/change-status', $controller.'changeStatus')->name('admin.table.change_status');
        Route::post('{table}/delete', $controller.'delete')->name('admin.table.delete');
    });

    //Order list
    Route::prefix('orders')->group( function(){

        $orderController = 'OrderController@';

        Route::get('/', $orderController.'index')->name('admin.orders');
        Route::post('ajax-data', $orderController.'ajaxTableData')->name('admin.orders.ajax_data');
        Route::get('{order}/view', $orderController.'view')->name('admin.orders.view');
        Route::post('{order}/change-status', $orderController.'changeStatus')->name('admin.orders.change_status');
        Route::post('{order}/delete', $orderController.'delete')->name('admin.orders.delete');
    });
    Route::get('/manage-table', 'OrderController@viewManageOrder')->name('admin.manage_table');

});
