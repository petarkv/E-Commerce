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

#DELETE PRODUCT FROM CART PAGE
Route::get('/cart/delete-product/{id}','ProductController@deleteCartProduct');

#UPDATE PRODUCT QUANTITY IN CART
Route::get('/cart/update-quantity/{id}/{quantity}','ProductController@updateCartQuantity');


# GET PRODUCT ATTRIBUTE PRICE
Route::get('/get-product-price','ProductController@getProductPrice');
//Route::get('/products/{id}/size/{size}/price','ProductController@getProductPrice');

#APPLY COUPON
Route::post('/cart/apply-coupon','ProductController@applyCoupon');

# USER LOGIN/REGISTER PAGE
#Route::get('/login-register', 'UsersController@userLoginRegister');

# USERS REGISTER FORM SUBMIT
#Route::post('/user-register','UserController@register');

# REGISTER / LOGIN USER
Route::get('/login-register', 'UsersController@getLoginRegister');

# FORGET PASSWORD USER
Route::match(['get','post'],'/forgot-password','UsersController@forgotPassword');

# USER REGISTER
Route::post('/user-register', 'UsersController@postUserRegister');

# CONFIRM USER ACCOUNT
Route::get('/confirm/{code}', 'UsersController@confirmAccount');

# USER LOGIN
Route::post('/user-login', 'UsersController@postUserLogin');

# CHECK IF USER ALREADY EXISTS
Route::match(['get','post'],'/check-email','UsersController@checkEmail');
Route::match(['get','post'],'/check-username','UsersController@checkUsername');

Route::match(['get','post'],'/check-username-update','UsersController@checkUsernameUpdate');

# Check Postal Code
Route::post('/check-postalcode','ProductController@checkPostalCode');

# SEARCH PRODUCTS
Route::post('/search-products', 'ProductController@searchProducts');

# CONTACT PAGE
Route::match(['get','post'],'/page/contact','CmsController@contact');

# POST PAGE (for Vue.js)
Route::match(['get','post'],'/page/post','CmsController@addPost');

# CMS PAGES FRONT END
Route::match(['get','post'],'/page/{url}','CmsController@cmsPage');

#MIDDLEWARE LOGIN USER PROTECTION
Route::group(['middleware'=>['frontlogin']],function(){
    # USERS ACCOUNT PAGE
    Route::match(['get','post'],'/account','UsersController@account');
    # CHECK USER CURRENT PASSWORD
    Route::post('/check-user-pwd', 'UsersController@checkUserPassword');
    # UPDATE USER PASSWORD
    Route::post('/update-user-pwd', 'UsersController@updateUserPassword');
    # CHECKOUT PAGE
    Route::match(['get','post'],'/checkout','ProductController@checkout');
    # ORDER REVIEW PAGE
    Route::match(['get','post'],'/order-review','ProductController@orderReview');
    # PLACE ORDER PAGE
    Route::match(['get','post'],'/place-order','ProductController@placeOrder');
    # THANKS PAGE
    Route::get('/thanks', 'ProductController@thanks');
    # USERS ORDERS PAGE
    Route::get('/orders', 'ProductController@userOrders');
    # USER ORDERED PRODUCTS PAGE
    Route::get('/orders/{id}', 'ProductController@userOrderDetails');
    # PAYPAL PAGE
    Route::get('/paypal', 'ProductController@paypal');
    # PAYPAL THANKS PAGE
    Route::get('/paypal/thanks', 'ProductController@thanksPaypal');
    # PAYPAL CANCEL PAGE
    Route::get('/paypal/cancel', 'ProductController@cancelPaypal');
});


# LOGOUT
Route::get('user-logout','UsersController@logout');


#MIDDLEWARE LOGIN PROTECTION - ADMIN PANEL
Route::group(['middleware' => ['adminlogin']], function(){
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

    #COUPON ROUTES
    Route::match(['get','post'],'/admin/add-coupon','CouponsController@addCoupon');
    Route::get('/admin/view-coupons', 'CouponsController@viewCoupons');

    Route::get('/admin/delete-coupon/{id}', 'CouponsController@deleteCoupon');

    #EDIT COUPONS
    Route::match(['get','post'],'/admin/edit-coupon/{id}','CouponsController@editCoupon');

    #BANNERS ADMIN
    Route::match(['get','post'],'/admin/add-banner','BannersController@addBanner');
    Route::get('/admin/view-banners', 'BannersController@viewBanners');
    Route::match(['get','post'],'/admin/edit-banner/{id}','BannersController@editBanner');
    Route::get('/admin/delete-banner/{id}', 'BannersController@deleteBanner');

    #ADMIN ORDERS
    Route::get('/admin/view-orders', 'ProductController@viewOrders');

    #ADMIN ORDER DETAILS
    Route::get('/admin/view-order/{id}', 'ProductController@viewOrderDetails');

    #ADMIN ORDER INVOICE
    Route::get('/admin/view-order-invoice/{id}', 'ProductController@viewOrderInvoice');

    #UPDATE ORDER STATUS
    Route::post('/admin/update-order-status', 'ProductController@updateOrderStatus');

    #ADMIN USERS ROUTE
    Route::get('/admin/view-users', 'UsersController@viewUsers');

    #ADD CMS PAGE ADMIN PANEL
    Route::match(['get','post'],'/admin/add-cms-page','CmsController@addCmsPage');
    #VIEW CMS PAGES ADMIN PANEL
    Route::get('/admin/view-cms-pages', 'CmsController@viewCmsPages');
    #EDIT CMS PAGE ADMIN PANEL
    Route::match(['get','post'],'/admin/edit-cms-page/{id}','CmsController@editCmsPage');
    #DELETE CMS PAGE ADMIN PANEL
    Route::get('/admin/delete-cms-page/{id}', 'CmsController@deleteCmsPages');

});
