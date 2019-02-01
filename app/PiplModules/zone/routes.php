<?php
Route::group(array('module'=>'zone','namespace' => 'App\PiplModules\zone\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.

    Route::get("/admin/zone/list","ZoneController@zoneIndex")->middleware('permission:view.zone');
    Route::get("/admin/list-zone","ZoneController@getZoneData")->middleware('permission:view.zone');

    Route::get("/admin/zone/create/","ZoneController@createZone")->middleware('permission:create.zone');
    Route::post("/admin/zone/create/","ZoneController@createZone")->middleware('permission:create.zone');

    Route::get("/admin/zone/update/{id}","ZoneController@updateZone")->middleware('permission:update.zone');
    Route::post("/admin/zone/update/{id}","ZoneController@updateZone")->middleware('permission:update.zone');

    Route::delete("/admin/delete-zone/{page_id}","ZoneController@deleteZone")->middleware('permission:delete.zone');
    Route::delete("/admin/zone/delete-selected/{page_id}","ZoneController@deleteSelectedZone")->middleware('permission:delete.zone');





    Route::get("/admin/cluster/list/{id}","ZoneController@index")->middleware('permission:view.cluster');
    Route::get("/admin/list-cluster/{id}","ZoneController@getClusterData")->middleware('permission:view.cluster');

    Route::get("/admin/cluster/create/{id}","ZoneController@createCluster")->middleware('permission:create.cluster');
    Route::post("/admin/cluster/create/{id}","ZoneController@createCluster")->middleware('permission:create.cluster');

    Route::get("/admin/cluster/update/{id}","ZoneController@updateCluster")->middleware('permission:update.cluster');
    Route::post("/admin/cluster/update/{id}","ZoneController@updateCluster")->middleware('permission:update.cluster');

    Route::delete("/admin/delete-cluster/{page_id}","ZoneController@deleteCluster")->middleware('permission:delete.cluster');
    Route::delete("/admin/cluster/delete-selected/{page_id}","ZoneController@deleteSelectedCluster")->middleware('permission:delete.cluster');




	Route::get("/admin/dispencery/create/{id}","ZoneController@getDispecery")->middleware('permission:view.zone.dispencery');
	Route::get("/admin/dispencery-data/{id}","ZoneController@getDispenceryData")->middleware('permission:view.zone.dispencery');
	
	Route::get("/admin/dispencery/add/{id}","ZoneController@addDispencery")->middleware('permission:create.zone.dispencery');
	Route::post("/admin/dispencery/add/{id}","ZoneController@addDispencery")->middleware('permission:create.zone.dispencery');
	
	Route::get("/admin/dispencery/update/{id}","ZoneController@showUpdateClusterPageForm")->middleware('permission:update.zone.dispencery');
	Route::post("/admin/dispencery/update/{id}","ZoneController@showUpdateClusterPageForm")->middleware('permission:update.zone.dispencery');
	
	
	Route::delete("/admin/dispencery/delete/{page_id}","ZoneController@deleteClusterDispencery")->middleware('permission:delete.zone.dispencery');
	Route::delete("/admin/dispencery/delete-selected/{page_id}","ZoneController@deleteSelectedClusterDispencery")->middleware('permission:delete.zone.dispencery');
	
});
