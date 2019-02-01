<?php
Route::group(array('module'=>'FrontGetstart','namespace' => 'App\PiplModules\getstartedsection\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/get-started-section/list","GetStartedController@index")->middleware('permission:view.getstart');
	Route::get("/admin/get-started-section-data","GetStartedController@getGetStartedData")->middleware('permission:view.getstart');
	
//	Route::get("/admin/property/create","PropertyController@createProperty")->middleware('permission:create.property');
//	Route::post("/admin/property/create","PropertyController@createProperty")->middleware('permission:create.property');
	
	Route::get("/admin/get-started-section/update/{page_id}","GetStartedController@showUpdateGetStartedPageForm")->middleware('permission:update.getstart');
	Route::post("/admin/get-started-section/update/{page_id}","GetStartedController@showUpdateGetStartedPageForm")->middleware('permission:update.getstart');
	
	
//	Route::delete("/admin/property/delete/{page_id}","PropertyController@deleteProperty")->middleware('permission:delete.property');
//	Route::delete("/admin/property/delete-selected/{page_id}","PropertyController@deleteSelectedProperty")->middleware('permission:delete.property');
	
});
