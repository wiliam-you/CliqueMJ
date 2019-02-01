<?php
Route::group(array('module'=>'Product','namespace' => 'App\PiplModules\product\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/product/list","ProductController@index")->middleware('permission:view.product');
	Route::get("/admin/list-dispencary-data","ProductController@listDispencary")->middleware('permission:view.product');
	
	Route::get("/admin/product/all/{id}","ProductController@getProduct")->middleware('permission:view.product');
	Route::get("/admin/product-data/{id}","ProductController@getProductData")->middleware('permission:view.product');
	
	Route::get("/admin/product/create/{id}","ProductController@createProduct")->middleware('permission:create.product');
	Route::post("/admin/product/create/{id}","ProductController@createProduct")->middleware('permission:create.product');
	
	Route::get("/admin/product/update/{id}","ProductController@showUpdateProductPageForm")->middleware('permission:update.product');
	Route::post("/admin/product/update/{id}","ProductController@showUpdateProductPageForm")->middleware('permission:update.product');
	
	
	Route::delete("/admin/product/delete/{page_id}","ProductController@deleteProduct")->middleware('permission:delete.product');
	Route::delete("/admin/product/delete-selected/{page_id}","ProductController@deleteSelectedProduct")->middleware('permission:delete.product');

    Route::get("/admin/delete/product-size/{id}","ProductController@deleteProductSize")->middleware('permission:create.product');

    Route::get("/admin/product/update/quantity/{id}","ProductController@productWeights")->middleware('permission:update.product-quantity');
    Route::get("/admin/product-size-data/{id}","ProductController@productWeightData");
    Route::get("/product-quantity","ProductController@productQuantity");
	
});
