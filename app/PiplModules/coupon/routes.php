<?php

Route::group(array('module'=>'Coupon','namespace' => 'App\PiplModules\coupon\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.

    Route::get("/admin/coupon/cluster/list","CouponController@clusterList")->middleware('permission:view.coupon');
    Route::get("/admin/coupon/list-cluster-data","CouponController@clusterData")->middleware('permission:view.coupon');

    Route::get("/admin/coupon/list/{id}","CouponController@index")->middleware('permission:view.coupon');
	Route::get("/admin/coupon-data/{id}","CouponController@getCouponData")->middleware('permission:view.coupon');

	Route::get("/admin/coupon/create/{id}","CouponController@createCoupon")->middleware('permission:create.coupon');
	Route::post("/admin/coupon/create/{id}","CouponController@createCoupon")->middleware('permission:create.coupon');
	
	Route::get("/admin/coupon/update/{page_id}","CouponController@showUpdateCouponPageForm")->middleware('permission:update.coupon');
	Route::post("/admin/coupon/update/{page_id}","CouponController@showUpdateCouponPageForm")->middleware('permission:update.coupon');
	
	
	Route::get("/admin/coupon/delete/{page_id}","CouponController@deleteCoupon")->middleware('permission:delete.coupon');
	Route::delete("/admin/coupon/delete-selected/{page_id}","CouponController@deleteSelectedCoupon")->middleware('permission:delete.coupon');

    Route::get("/admin/coupon/get-qr_code/{id}","CouponController@getQrCode")->middleware('permission:qr-code');

    Route::get("/admin/coupon/pdf/{id}","CouponController@getPdf")->middleware('permission:download.qr-code');
	
});
