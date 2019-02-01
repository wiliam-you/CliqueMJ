<?php
Route::group(array('module'=>'FrontDispencary','namespace' => 'App\PiplModules\dispencarysection\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/dispencary-section/list","DispencaryController@index")->middleware('permission:view.dispencary');
	Route::get("/admin/dispencary-section-data","DispencaryController@getDispencaryData")->middleware('permission:view.dispencary');
	
//	Route::get("/admin/property/create","PropertyController@createProperty")->middleware('permission:create.property');
//	Route::post("/admin/property/create","PropertyController@createProperty")->middleware('permission:create.property');
	
	Route::get("/admin/dispencary-section/update/{page_id}","DispencaryController@showUpdateDispencaryPageForm")->middleware('permission:update.dispencary');
	Route::post("/admin/dispencary-section/update/{page_id}","DispencaryController@showUpdateDispencaryPageForm")->middleware('permission:update.dispencary');
	
	
//	Route::delete("/admin/property/delete/{page_id}","PropertyController@deleteProperty")->middleware('permission:delete.property');
//	Route::delete("/admin/property/delete-selected/{page_id}","PropertyController@deleteSelectedProperty")->middleware('permission:delete.property');
	
});
