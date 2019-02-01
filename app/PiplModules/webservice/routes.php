<?php
Route::group(array('module'=>'webservice','namespace' => 'App\PiplModules\webservice\Controllers'), function() {
    //Your routes belong to this module.

    //    routs for web services
    Route::post("ws-registration","WebServiceController@registrationData");
    Route::post("ws-social-login","WebServiceController@socialLogin");
    Route::post("ws-login","WebServiceController@login");
    Route::post("ws-set-date-of-birth","WebServiceController@setDateOfBirth");
    Route::post("ws-forgot-pass","WebServiceController@forgotPassword");
    Route::post("ws-change-password","WebServiceController@changePassword");
    Route::post("ws-change-profile","WebServiceController@changeProfile");
    Route::post("ws-get-dispensaries-byrange","WebServiceController@dispenceryByRange");
    Route::post("ws-get-product-bydispenary","WebServiceController@getDispenceryProduct");
    Route::post("ws-dispencery-feedback","WebServiceController@dispenceryFeedback");

    Route::post("ws-get-coupon","WebServiceController@dispensaryAlgo");
    Route::post("ws-get-patient-coupon","WebServiceController@getPatientCoupon");
    Route::post("ws-use-coupon","WebServiceController@useCoupon");
    Route::post("ws-get-privacy-policy","WebServiceController@getPrivacyPolicy");
    Route::post("ws-get-about-us","WebServiceController@getAboutUs");
    Route::post("ws-get-coupon-details","WebServiceController@getCouponDetail");

//        Route::post("ws-advertisement-offer","WebServiceController@getAdvertisementOffer");
    Route::post("ws-redeem-advertisement-offer","WebServiceController@offerGetUsed");
    Route::post("ws-get-all-advertisement-offer","WebServiceController@getPatientAdvertisementOffer");
    Route::post("ws-delete-coupon","WebServiceController@deleteCoupon");
    Route::post("ws-share-offer","WebServiceController@shareOffer");
    Route::post("ws-get-offer","WebServiceController@getOffer");
    Route::post("ws-set-patient-data","WebServiceController@setPatientData");


    Route::get("ws-set-notification","WebServiceController@iosNotificaton");

//        set cron job
    Route::get("check-coupon-expiry","WebServiceController@checkCoupon");

    //customer logout
    Route::post("customer-logout","WebServiceController@customerLogout");
    //view and update offer/msg count
    Route::post("view-offer-msg-count","WebServiceController@viewOfferMsgCount");

});
