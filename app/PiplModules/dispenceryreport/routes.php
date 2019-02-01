<?php
Route::group(array('module'=>'dispenceryreport','namespace' => 'App\PiplModules\dispenceryreport\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/dispensary/report/list","DispenceryReportController@index")->middleware('auth');
	Route::get("/dispencery/report/data","DispenceryReportController@getCustomerData")->middleware('auth');
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
