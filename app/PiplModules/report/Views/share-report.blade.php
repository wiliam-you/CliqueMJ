@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Advertise Offer Share Reports</title>

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
					<a href="javascript:void(0)">Advertise Offer Share Reports</a>
					
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
								<i class="fa fa-list"></i>Advertise Offer Share Reports
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

                                                                <th>Sender Patient Name</th>
                                                                <th>Receiver Patient Name</th>
																<th>Advertise Offer</th>
                                                        </tr>
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
        ajax: {"url":'{{url("/admin/share-report-data")}}',"complete":afterRequestComplete, data: function(d){
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
            { data: 'sender', name: 'sender',searchable: true},
           { data: 'receiver', name: 'receiver'},
            { data: 'offer', name: 'offer'},
        ],


    });






});


</script>
@endsection
