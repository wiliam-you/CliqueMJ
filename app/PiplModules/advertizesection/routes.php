<?php
Route::group(array('module'=>'FrontAdvertize','namespace' => 'App\PiplModules\advertizesection\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/advertise-section/list","AdvertizeController@index")->middleware('permission:view.advertize');
	Route::get("/admin/advertize-section-data","AdvertizeController@getAdvertizeData")->middleware('permission:view.advertize');
	
//	Route::get("/admin/property/create","PropertyController@createProperty")->middleware('permission:create.property');
//	Route::post("/admin/property/create","PropertyController@createProperty")->middleware('permission:create.property');
	
	Route::get("/admin/advertise-section/update/{page_id}","AdvertizeController@showUpdateAdvertizePageForm")->middleware('permission:update.advertize');
	Route::post("/admin/advertise-section/update/{page_id}","AdvertizeController@showUpdateAdvertizePageForm")->middleware('permission:update.advertize');
	
	
//	Route::delete("/admin/property/delete/{page_id}","PropertyController@deleteProperty")->middleware('permission:delete.property');
//	Route::delete("/admin/property/delete-selected/{page_id}","PropertyController@deleteSelectedProperty")->middleware('permission:delete.property');
	
});
