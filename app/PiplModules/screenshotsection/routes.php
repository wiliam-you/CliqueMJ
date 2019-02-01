<?php
Route::group(array('module'=>'FrontScreenShot','namespace' => 'App\PiplModules\screenshotsection\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/screen-shot-section/list","ScreenShotController@addScreenShot")->middleware('permission:view.screenshot');
	Route::post("/admin/screen-shot-section/list","ScreenShotController@addScreenShot")->middleware('permission:update.screenshot');

    Route::get("/admin/remove/screen-shot/{id}","ScreenShotController@removeScreenShot")->middleware('permission:delete.screenshot');
	
	
//	Route::delete("/admin/property/delete/{page_id}","PropertyController@deleteProperty")->middleware('permission:delete.property');
//	Route::delete("/admin/property/delete-selected/{page_id}","PropertyController@deleteSelectedProperty")->middleware('permission:delete.property');
	
});
