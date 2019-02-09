<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect('/home');
});
Route::auth();

Route::get('/home', 'HomeController@index');
Route::get("/run/queue","HomeController@runQueue");
Route::get('/permission/denied', 'HomeController@permissionDenied');
Route::get('/redirect-dashboard', 'HomeController@toDashboard');
Route::get('/user-profile', 'HomeController@toDashboard');
Route::get('/dispensary/dashboard', 'HomeController@despenceryDashboard')->middleware('auth');
Route::get('dispensary/profile','HomeController@dispenceryProfile')->middleware('auth');
Route::post('/dispencery/update-profile-post','HomeController@addNameDispenceryProfile')->middleware('auth');
Route::post('/dispencery/update-vendor-email','HomeController@addEmailDispenceryProfile')->middleware('auth');
Route::post('/dispencery/change-password-post','HomeController@addPasswordDispenceryProfile')->middleware('auth');
Route::get('/dispensary/feedback/list','HomeController@feedbackList')->middleware('auth');
Route::get('/dispensary/feedback-data','HomeController@feedbackData')->middleware('auth');
Route::get('/dispencery/logout',function(){
    Auth::logout();
    return redirect('/login');
});



// For User Profile and account settings...
Route::get('verify-user-email/{token}', ['uses' => 'ProfileController@verifyUserEmail']);
Route::get('chk-email-duplicate', ['uses' => 'ProfileController@chkEmailDuplicate']);
Route::get('chk-current-password', ['uses' => 'ProfileController@chkCurrentPassword']);

Route::get('profile', ['middleware' => 'auth', 'uses' => 'ProfileController@show']);
Route::get('update-profile', ['middleware' => 'auth', 'uses' => 'ProfileController@updateProfile']);
Route::post('update-profile-post', ['middleware' => 'auth', 'uses' => 'ProfileController@updateProfileInfo']);

Route::get('change-email', ['middleware' => 'auth', 'uses' => 'ProfileController@updateEmail']);
Route::post('change-email-post', ['middleware' => 'auth', 'uses' => 'ProfileController@updateEmailInfo']);

Route::get('change-password', ['middleware' => 'auth', 'uses' => 'ProfileController@updatePassword']);
Route::post('change-password-post', ['middleware' => 'auth', 'uses' => 'ProfileController@updatePasswordInfo']);
Route::post('pi/upload-file', "ProfileController@uploadProfileFile");
Route::get('pi/send-mail', "ProfileController@sendMail");
Route::get('pi/crop-example', "ProfileController@cropExample");
Route::post('pi/crop-example', "ProfileController@cropPostImage");
Route::get('pi/image-gallery', "ExampleController@loadGallery");
Route::get('pi/file-explorer', "ExampleController@fileExplorerView");
Route::post('send/enquiry/email', "HomeController@sendEmail");
Route::get('forget_password/reset', "PasswordController@forgetPasswordReset");

