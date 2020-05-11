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

/*Route::get('/', function () {
    return view('welcome');
});*/

#HOME PAGE
Route::get('/','IndexController@index');


#LOGIN
Route::get('/admin', 'AdminController@getLogin');
Route::post('/admin', 'AdminController@postLogin');

#LOGOUT
Route::get('/logout', 'AdminController@logout');

#DASHBOARD
#Route::get('/admin/dashboard', 'AdminController@getDashboard');

#AUTH
Auth::routes();

#HOME
Route::get('/home', 'HomeController@index')->name('home');

# CATEGORY LISTING PAGE
Route::get('/products/{url}','ProductController@products');

# PRODUCT DETAIL PAGE
Route::get('/product/{id}','ProductController@getProductDetails'); 

# ADD TO CART ROUTE
Route::match(['get','post'],'/cart','ProductController@cart');

# CART PAGE
Route::match(['get','post'],'/add-cart','ProductController@addtocart');

# GET PRODUCT ATTRIBUTE PRICE
Route::get('/get-product-price','ProductController@getProductPrice');
//Route::get('/products/{id}/size/{size}/price','ProductController@getProductPrice');

# USER LOGIN/REGISTER PAGE
#Route::get('/login-register', 'UsersController@userLoginRegister');

# USERS REGISTER FORM SUBMIT
#Route::post('/user-register','UserController@register');

# REGISTER / LOGIN USER
Route::get('/login-register', 'UsersController@getLoginRegister');

# USER REGISTER
Route::post('/user-register', 'UsersController@postUserRegister');

# USER LOGIN
Route::post('/user-login', 'UsersController@postUserLogin');

# CHECK IF USER ALREADY EXISTS
Route::match(['get','post'],'/check-email','UsersController@checkEmail');
Route::match(['get','post'],'/check-username','UsersController@checkUsername');

# LOGOUT
Route::get('user-logout','UsersController@logout');


#MIDDLEWARE LOGIN PROTECTION
Route::group(['middleware' => ['auth']], function(){
    Route::get('/admin/dashboard', 'AdminController@getDashboard');
    #SETTINGS PAGE
    Route::get('/admin/settings', 'AdminController@getSettings');
    #CHECKING CURRENT PASSWORD
    Route::get('/admin/check-pwd', 'AdminController@checkPassword');
    #UPDATE PASSWORD
    Route::get('/admin/update-pwd', 'AdminController@updatePassword');
    Route::post('/admin/update-pwd', 'AdminController@updatePassword');

    #CATEGORIES ROUTES - ADMIN
    Route::get('/admin/add-category', 'CategoryController@getAddCategory');
    Route::post('/admin/add-category', 'CategoryController@postAddCategory');

    Route::get('/admin/view-categories', 'CategoryController@getViewCategories');

    Route::get('/admin/edit-category/{id}', 'CategoryController@getEditCategory');
    Route::post('/admin/edit-category/{id}', 'CategoryController@postEditCategory');
    #Route::match(['get','post'],'/admin/edit-category/{id}', 'CategoryController@getEditCategory');
    
    Route::match(['get','post'],'/admin/delete-category/{id}', 'CategoryController@deleteCategory');
    #Route::get('/admin/delete-category/{id}', 'CategoryController@getDeleteCategory');
    #Route::post('/admin/delete-category/{id}', 'CategoryController@postDeleteCategory');

    #PRODUCTS ROUTES - ADMIN
    Route::get('/admin/add-product', 'ProductController@getAddProduct');
    Route::post('/admin/add-product', 'ProductController@postAddProduct');

    Route::get('/admin/view-products', 'ProductController@getViewProducts');

    Route::get('/admin/edit-product/{id}', 'ProductController@getEditProduct');
    Route::post('/admin/edit-product/{id}', 'ProductController@postEditProduct');

    Route::get('/admin/delete-product/{id}', 'ProductController@deleteProduct');
        
    #DELETE IMAGE - ADMIN
    Route::get('/admin/delete-product-image/{id}', 'ProductController@deleteProductImage');

    Route::get('/admin/delete-alt-image/{id}', 'ProductController@deleteAltImage');

    #PRODUCT ATTRIBUTES - ADMIN
    Route::match(['get','post'],'admin/add-attributes/{id}','ProductController@addAttributes');
    Route::match(['get','post'],'admin/edit-attributes/{id}','ProductController@editAttributes');
    Route::match(['get','post'],'admin/add-images/{id}','ProductController@addImages');
    Route::get('/admin/delete-attribute/{id}', 'ProductController@deleteAttribute');
});
