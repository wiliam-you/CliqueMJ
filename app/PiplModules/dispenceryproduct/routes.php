<?php
Route::group(array('module'=>'dispenceryproduct','namespace' => 'App\PiplModules\dispenceryproduct\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/dispencery/product/list","DispenceryProductController@index")->middleware('auth');
	Route::get("/dispencery/product/all","DispenceryProductController@getProductData")->middleware('auth');
//	Route::get("/admin/product-data/{id}","DispenceryProductController@getProductData")->middleware('auth');
	
	Route::get("/dispencery/product/create","DispenceryProductController@createProduct")->middleware('auth');
	Route::post("/dispencery/product/create","DispenceryProductController@createProduct")->middleware('auth');
	
	Route::get("/dispencery/product/update/{id}","DispenceryProductController@showUpdateProductPageForm")->middleware('auth');
	Route::post("/dispencery/product/update/{id}","DispenceryProductController@showUpdateProductPageForm")->middleware('auth');
	
	
	Route::delete("/dispencery/product/delete/{page_id}","DispenceryProductController@deleteProduct")->middleware('auth');
	Route::delete("/dispencery/product/delete-selected/{page_id}","DispenceryProductController@deleteSelectedProduct")->middleware('auth');

    Route::get("/dispencery/product/update/quantity/{id}","DispenceryProductController@productQuantity")->middleware('auth');
    Route::post("/dispencery/product/update/quantity/{id}","DispenceryProductController@productQuantity")->middleware('auth');
	
});
