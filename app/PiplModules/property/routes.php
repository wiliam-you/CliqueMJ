<?php
Route::group(array('module'=>'Property','namespace' => 'App\PiplModules\property\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/property/list","PropertyController@index")->middleware('permission:view.property');
	Route::get("/admin/property-data","PropertyController@getPropertyData")->middleware('permission:view.property');
	
	Route::get("/admin/property/create","PropertyController@createProperty")->middleware('permission:create.property');
	Route::post("/admin/property/create","PropertyController@createProperty")->middleware('permission:create.property');
	
	Route::get("/admin/property/update/{page_id}","PropertyController@showUpdatePrpoertyPageForm")->middleware('permission:update.property');
	Route::post("/admin/property/update/{page_id}","PropertyController@showUpdatePrpoertyPageForm")->middleware('permission:update.property');
	
	
	Route::delete("/admin/property/delete/{page_id}","PropertyController@deleteProperty")->middleware('permission:delete.property');
	Route::delete("/admin/property/delete-selected/{page_id}","PropertyController@deleteSelectedProperty")->middleware('permission:delete.property');
	
});
