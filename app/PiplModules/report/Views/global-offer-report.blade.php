@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

	<title>Global Offer Reports</title>

@endsection

@section('content')
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="{{url('admin/dashboard')}}">Dashboard</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="javascript:void(0)">Global Offer Reports</a>

				</li>
			</ul>
			@if (session('status'))
				<div class="alert alert-success">
					{{ session('status') }}
					<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
				</div>
			@endif
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-list"></i>Global Offer Reports
							</div>
							<div class="tools">
								<a class="collapse" href="javascript:;" data-original-title="" title="">
								</a>
								<a class="config" data-toggle="modal" href="#portlet-config" data-original-title="" title="">
								</a>
								<a class="reload" href="javascript:;" data-original-title="" title="">
								</a>
								<a class="remove" href="javascript:;" data-original-title="" title="">
								</a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-toolbar">

							</div>
							<table class="table table-striped table-bordered table-hover" id="list_testimonial">
								<thead>
								<tr>

									{{--<th>Patient Name</th>--}}
									<th>Clique Offer Name</th>
									<th>Clique Offer View Count</th>
									<th>Clique Offer Redeem Count</th>
									{{--<th>Coupon Name</th>--}}
									{{--<th>MJ Offer View Count</th>--}}
								</tr>
								{{--<tr>
									<th>Offer</th>
									<th>Scan By Patient Name</th>
									<th>Redeem By Patient Name</th>
								</tr>--}}
								</thead>
							</table>

						</div>
					</div>



					<!-- END PAGE CONTENT INNER -->
				</div>
			</div>
			<!-- END CONTENT -->
		</div>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script>
            var tbl_sale='';
            $(function() {
                currentFilters = [];
                tbl_sale = $('#list_testimonial').DataTable({
                    processing: true,
                    serverSide: true,
                    //bStateSave: true,
                    ajax: {"url":'{{url("/admin/global-offer-report-data")}}',"complete":afterRequestComplete, data: function(d){
                        d.filters = currentFilters;
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.product = $('#product').val();
                        d.patient = $('#patient').val();
                    }},

                    columnDefs: [{
                        "defaultContent": "-",
                        "targets": "_all"
                    }],
                    columns: [
//                        { data: 'patient_name', name: 'patient_name',searchable: true},
                        { data: 'offerName', name: 'offerName',searchable: true},
                        { data: 'advertisement_view', name: 'advertisement_view',searchable: true},
                        { data: 'redeem_count', name: 'redeem_count'}
//            { data: 'coupon_name', name: 'coupon_name',searchable: true},
//                        { data: 'coupon_view', name: 'coupon_view',searchable: true},

                        /*{ data: 'offer', name: 'offer'},
                        { data: 'scan', name: 'scan',searchable: true},
                        { data: 'redeem', name: 'redeem'},*/
                    ],
                });
            });


		</script>
@endsection
