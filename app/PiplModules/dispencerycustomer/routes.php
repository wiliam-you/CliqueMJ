<?php
Route::group(array('module'=>'dispencerycustomer','namespace' => 'App\PiplModules\dispencerycustomer\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/dispencery/customer/list","DispenceryCustomerController@index")->middleware('auth');
	Route::get("/dispencery/customer/data","DispenceryCustomerController@getCustomerData")->middleware('auth');
////	Route::get("/admin/product-data/{id}","DispenceryProductController@getProductData")->middleware('auth');
//
//	Route::get("/dispencery/product/create","DispenceryProductController@createProduct")->middleware('auth');
//	Route::post("/dispencery/product/create","DispenceryProductController@createProduct")->middleware('auth');
//
//	Route::get("/dispencery/product/update/{id}","DispenceryProductController@showUpdateProductPageForm")->middleware('auth');
//	Route::post("/dispencery/product/update/{id}","DispenceryProductController@showUpdateProductPageForm")->middleware('auth');
//
//
//	Route::delete("/dispencery/product/delete/{page_id}","DispenceryProductController@deleteProduct")->middleware('auth');
//	Route::delete("/dispencery/product/delete-selected/{page_id}","DispenceryProductController@deleteSelectedProduct")->middleware('auth');
//
//    Route::get("/dispencery/product/update/quantity/{id}","DispenceryProductController@productQuantity")->middleware('auth');
//    Route::post("/dispencery/product/update/quantity/{id}","DispenceryProductController@productQuantity")->middleware('auth');
	
});
