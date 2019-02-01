<?php
Route::group(array('module'=>'Advertisement','namespace' => 'App\PiplModules\advertisement\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/advertisement/list","AdvertisementController@index")->middleware('permission:view.advertisement');
	Route::get("/admin/advertisement-data","AdvertisementController@getAdvertisementData")->middleware('permission:view.advertisement');
	
	Route::get("/admin/advertisement/create","AdvertisementController@createAdvertisement")->middleware('permission:create.advertisement');
	Route::post("/admin/advertisement/create","AdvertisementController@createAdvertisement")->middleware('permission:create.advertisement');
	
	Route::get("/admin/advertisement/update/{page_id}","AdvertisementController@showUpdateAdvertisementPageForm")->middleware('permission:update.advertisement');
	Route::post("/admin/advertisement/update/{page_id}","AdvertisementController@showUpdateAdvertisementPageForm")->middleware('permission:update.advertisement');
	
	
	Route::delete("/admin/advertisement/delete/{page_id}","AdvertisementController@deleteAdvertisement")->middleware('permission:delete.advertisement');
	Route::get("/admin/advertisement/delete-selected","AdvertisementController@deleteSelectedAdvertisement")->middleware('permission:delete.advertisement');

    Route::get("/admin/advertisement/download/{id}","AdvertisementController@downloadtAdvertisement")->middleware('permission:download.advertisement');
    Route::get("/admin/advertisement/download-bmp/{id}","AdvertisementController@downloadtAdvertisementBmp")->middleware('permission:download.advertisement');

    Route::get("/admin/advertisement/create-global-offer","AdvertisementController@createGlobalOffer")->middleware('permission:create.advertisement.global');
    Route::post("/admin/advertisement/create-global-offer","AdvertisementController@createGlobalOffer")->middleware('permission:create.advertisement.global');

    Route::get("/admin/advertisement/list-global-offer","AdvertisementController@listGlobalOffer")->middleware('permission:list.advertisement.global');
    Route::get("/admin/advertisement/list-global-offer-data","AdvertisementController@listGlobalOfferData")->middleware('permission:list.advertisement.global');

    Route::get("/admin/check/queue","AdvertisementController@checkQueue")->middleware('permission:create.advertisement');

    Route::get("/admin/advertisement/deleteqr","AdvertisementController@deleteQr")->middleware('permission:create.advertisement');

    Route::get("/admin/download/pdf","AdvertisementController@downloadPdf")->middleware('permission:create.advertisement');
    Route::get("/admin/new-pdf/download/{id}","AdvertisementController@downloadNewPdf")->middleware('permission:create.advertisement');
    Route::get("/admin/download/new-zip","AdvertisementController@downloadZip")->middleware('permission:create.advertisement');

});

/*define('DB_USER', 'root');
define('DB_PASSWORD', 'ondemandtec@123$');
define('DB_HOST', 'localhost');*/