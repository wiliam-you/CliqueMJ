<?php
Route::group(array('module'=>'Feedback','namespace' => 'App\PiplModules\feedback\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	
	Route::get("/admin/feedback/list","FeedbackController@index")->middleware('permission:view.feedback');
	Route::get("/admin/feedback/all/{id}","FeedbackController@getFeedback")->middleware('permission:view.feedback');
	Route::get("/admin/feedback-data/{id}","FeedbackController@getFeedbackData")->middleware('permission:view.feedback');
	Route::get("/admin/feedback-dispensary-list","FeedbackController@listDispencary")->middleware('permission:view.feedback');

//	Route::get("/admin/feedback/create/{id}","FeedbackController@createFeedback")->middleware('permission:create.feedback');
//	Route::post("/admin/feedback/create/{id}","FeedbackController@createFeedback")->middleware('permission:create.feedback');
	
	Route::get("/admin/feedback/update/{id}","FeedbackController@showUpdateFeedbackPageForm")->middleware('permission:update.feedback');
	Route::post("/admin/feedback/update/{id}","FeedbackController@showUpdateFeedbackPageForm")->middleware('permission:update.feedback');
	
	
	Route::delete("/admin/feedback/delete/{page_id}","FeedbackController@deleteFeedback")->middleware('permission:delete.feedback');
	Route::delete("/admin/feedback/delete-selected/{page_id}","FeedbackController@deleteSelectedFeedback")->middleware('permission:delete.feedback');
	
});
