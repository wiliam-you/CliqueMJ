<?php

Route::group(array('module'=>'Genericmessages','namespace' => 'App\PiplModules\genericmessages\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
    Route::get("/admin/genericmessages/list","GenericmessagesController@listGenericMsg")->middleware('permission:view.genericmessages');
    Route::get("/admin/genericmessages-data","GenericmessagesController@getGenericData")->middleware('permission:view.genericmessages');

    Route::get("/admin/genericmessages/create-generic-msg","GenericmessagesController@createGenericMsg")->middleware('permission:create.genericmessages');
    Route::post("/admin/genericmessages/create-generic-msg","GenericmessagesController@createGenericMsg")->middleware('permission:create.genericmessages');

     Route::get("/admin/ios","GenericmessagesController@androidNotification");

});
