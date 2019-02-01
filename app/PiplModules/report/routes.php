<?php
Route::group(array('module'=>'Report','namespace' => 'App\PiplModules\report\Controllers','middleware'=>'web'), function() {
    //Your routes belong to this module.
	

    Route::get("/admin/sale-report/list","ReportController@saleReport")->middleware('permission:view.sale-report');
    Route::get("/admin/sale-report-data","ReportController@saleReportData")->middleware('permission:view.sale-report');
	
    
    Route::get("/admin/patient-report/list","ReportController@patientReport")->middleware('permission:view.patient-report');
    Route::get("/admin/patient-report-data","ReportController@patientReportData")->middleware('permission:view.patient-report');

    Route::get("/admin/coupon-report/list","ReportController@couponReport")->middleware('permission:view.coupon-report');
    Route::get("/admin/coupon-report-data","ReportController@couponReportData")->middleware('permission:view.coupon-report');

    Route::get("/admin/share-report/list","ReportController@shareReport")->middleware('permission:view.share-report');
    Route::get("/admin/share-report-data","ReportController@shareReportData")->middleware('permission:view.share-report');

    Route::get("/admin/advertisement-report/list","ReportController@advertisementReport")->middleware('permission:view.advertisement-report');
    Route::get("/admin/advertisement-report-data","ReportController@advertisementReportData")->middleware('permission:view.advertisement-report');

    Route::get("/admin/coupon-offer-view","ReportController@couponOfferViewReport")->middleware('permission:view.coupon-offer-view-report');
    Route::get("/admin/coupon-offer-view-data","ReportController@couponOfferViewReportData")->middleware('permission:view.coupon-offer-view-report');

    Route::get("/admin/global-offer-report","ReportController@globalOfferViewReport")->middleware('permission:view.global-offer-view-report');
    Route::get("/admin/global-offer-report-data","ReportController@globalOfferViewReportData")->middleware('permission:view.global-offer-view-report');
});
