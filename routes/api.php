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
#routing for login 
Route::post('login', 'API\UserController@login');
# add and register new customer
Route::post('AddnRegisternewCustomer', 'API\UserController@AddnRegisternewCustomer');
#routing for product
Route::post('products', 'API\ProductController@createProduct');
#routing for customer
Route::post('addcustomer', 'API\UserController@AddCustomer');
# view customer
Route::get('viewcustomer', 'API\UserController@ViewCustomer');
#routing for createrequest
Route::post('createrequest', 'API\UserController@CreateRequest');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    // return $request->user();
    Route::get('details', 'API\UserController@details');
});

Route::get('getusers','UserController@getusers');
# otp varification
// Route::post('OtpVerificationforLogin', 'API\UserController@OtpVerificationforLogin');
# otp verify  done
Route::post('otpverificationForLogin', 'API\UserController@otpverificationForLogin');
#add Customers
Route::post('AddCustomer', 'API\UserController@AddCustomer');
# admin Register 
Route::post('AdminRegister', 'API\UserController@AdminRegister');
# get details of aproved customers
Route::get('whiteList', 'API\UserController@whiteList');
# total customer
Route::get('countTotalcustomres', 'API\UserController@countTotalcustomres');
# use Resend OTP
Route::post('resendOtp', 'API\UserController@resendOtp');
# call logs 
Route::get('callLog', 'API\UserController@callLog');
# upcoming birthday 
Route::any('upcomingDateofBirth', 'API\UserController@upcomingDateofBirth');
# add category 
Route::post('addCategory', 'API\UserController@addCategory');
# Category list 
Route::get('categoryList', 'API\UserController@categoryList');
# Get Details of User or Customer
Route::any('UserOrCustomerDetails', 'API\UserController@UserOrCustomerDetails');
# Create Announcement and store 
Route::post('CreateAnnouncement', 'API\UserController@CreateAnnouncement');
# Get Announcement Details 
Route::get('AnnouncementDetails', 'API\UserController@AnnouncementDetails');
# total Announcement
Route::get('totalannouncemet', 'API\UserController@totalannouncemet');