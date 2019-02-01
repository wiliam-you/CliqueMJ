<?php
Route::group(array('module'=>'chooseussection','namespace' => 'App\PiplModules\chooseussection\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/chooseus/list","ChooseUsController@index")->middleware('permission:view.chooseus');
	Route::get("/admin/chooseus-data","ChooseUsController@getChooseUsData")->middleware('permission:view.chooseus');
	Route::get("/admin/chooseus/update/{page_id}","ChooseUsController@showUpdateChooseUsPageForm")->middleware('permission:update.chooseus');
	Route::post("/admin/chooseus/update/{page_id}","ChooseUsController@showUpdateChooseUsPageForm")->middleware('permission:update.chooseus');
});
