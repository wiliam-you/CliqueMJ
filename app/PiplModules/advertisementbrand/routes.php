<?php
Route::group(array('module'=>'AdvertisementBrand','namespace' => 'App\PiplModules\advertisementbrand\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/advertisementbrand/list","AdvertisementbrandController@index")->middleware('permission:view.advertisementbrand');
	Route::get("/admin/advertisementbrand-data","AdvertisementbrandController@getAdvertisementData")->middleware('permission:view.advertisementbrand');
	
	Route::get("/admin/advertisementbrand/create","AdvertisementbrandController@createadvertisementbrand")->middleware('permission:create.advertisementbrand');
	Route::post("/admin/advertisementbrand/create","AdvertisementbrandController@createadvertisementbrand")->middleware('permission:create.advertisementbrand');
	
	Route::get("/admin/advertisementbrand/update/{page_id}","AdvertisementbrandController@showUpdateadvertisementbrandPageForm")->middleware('permission:update.advertisementbrand');
	Route::post("/admin/advertisementbrand/update/{page_id}","AdvertisementbrandController@showUpdateadvertisementbrandPageForm")->middleware('permission:update.advertisementbrand');
	
	
	Route::delete("/admin/advertisementbrand/delete/{page_id}","AdvertisementbrandController@deleteadvertisementbrand")->middleware('permission:delete.advertisementbrand');
	Route::delete("/admin/advertisementbrand/delete-selected/{page_id}","AdvertisementbrandController@deleteSelectedadvertisementbrand")->middleware('permission:delete.advertisementbrand');
	
});
