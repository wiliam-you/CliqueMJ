@extends('layouts.vendor')

@section("meta")

	<title>My Customers</title>

@endsection


@section('content')
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="{{url('dispensary/dashboard')}}">Dashboard</a>
				</li>
				<li>
					<a href="javascript:void(0)">My Customers</a>

				</li>
			</ul>

			@if (session('status'))
				<div class="alert alert-success">
					{{ session('status') }}
					<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
				</div>
			@endif
			@if (session('error'))
				<div class="alert alert-danger">
					{{ session('error') }}
					<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
				</div>
			@endif

			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="grey-cascade">
						<div class="portlet-title">
							<div class="x_title"><h2>My Customers</h2>
								<div class="clearfix"></div>
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
							<table class="table table-striped table-bordered table-hover" id="tbl_regusers">
								<thead>
								<tr>
									<th>Patient Name</th>
									<th>Product Name</th>
									<th>Property</th>
									<th>Size</th>
									<th>Quantity</th>
									<th>Price</th>
									<th>Total Price</th>
								</tr>
								</thead>
							</table>
						</div>
					</div>

				</div>
			</div>
			<script>
                $(function() {
                    $('#tbl_regusers').DataTable({
                        processing: true,
                        serverSide: true,
                        //bStateSave: true,
                        ajax: {"url":'{{url("/dispencery/customer/data")}}',"complete":afterRequestComplete},
                        columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
                        columns: [
                            { data: 'patient_name', name: 'patient_name',searchable: true},
                            { data: 'product_name', name: 'product_name',searchable: true},
                            { data: 'property', name: 'property',searchable: true},
                            { data: 'size', name: 'size',searchable: true},
                            { data: 'quantity', name: 'quantity',searchable: true},
                            { data: 'price', name: 'price',searchable: true},
                            { data: 'total', name: 'total',searchable: true},

                        ]
                    });
                });
                function confirmDelete(id)
                {
                    if(confirm("Do you really want to delete this product?"))
                    {

                        $("#delete_user_"+id).submit();
                    }
                    return false;
                }
			</script>
@endsection
