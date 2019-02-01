<?php
Route::group(array('module'=>'featuresection','namespace' => 'App\PiplModules\featuresection\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/feature/list","FeatureController@index")->middleware('permission:view.feature');
	Route::get("/admin/feature-data","FeatureController@getFeatureData")->middleware('permission:view.feature');
	Route::get("/admin/feature/update/{page_id}","FeatureController@showUpdateFeaturePageForm")->middleware('permission:update.feature');
	Route::post("/admin/feature/update/{page_id}","FeatureController@showUpdateFeaturePageForm")->middleware('permission:update.feature');

});
