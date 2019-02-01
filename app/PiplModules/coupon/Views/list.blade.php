@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

	<title>Manage Coupon</title>
	<style>
		.top-filter{
			padding:15px 0px;
			background: #fff;
			border:1px solid #ccc;
			border-radius:10px;
			height: 80px;
			margin-top:20px;
		}
		.status{
			padding:20px 0px;
		}
		.top-filter input{
			padding: 2px 10px;
		}
		.status lable{
			margin-right: 20px;
		}
		.updt-btn{
			padding:15px 0px;
		}
		.updt-btn button{
			background: #5cb85c;
			border: 1px solid #ccc;
			border: none;
			padding: 4px 15px;
			border-radius:5px;
			color:#fff;
		}
	</style>
@endsection

@section('content')
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="{{url('admin/dashboard')}}">Dashboard</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
{{--					<a href="javascript:void(0)">Manage {{$cluster->title}}'s MJ Offer</a>--}}
					<a href="javascript:void(0)">Manage MJ Offer</a>

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
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
{{--								<i class="fa fa-list"></i>Manage {{$cluster->title}}'s MJ Offer--}}
								<i class="fa fa-list"></i>Manage MJ Offer
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
								<div class="row">
									<div class="col-md-6">
										@if($cluster->coupons->count()>0)
											<div class="btn-group">
												<a href="{{url('/admin/coupon/pdf/'.$cluster->id)}}" 												id="sample_editable_1_new" class="btn btn-warning">
													Download MJ Offers <i class="fa fa-arrow-down"></i>
												</a>
											</div>
										@endif
									</div>

								</div>
								<hr>
								<div class="">
									<form action="{{url('/admin/coupon/create/'.$cluster->id)}}" method="post">
										{!! csrf_field() !!}
										<div class="col-md-3">
											<lable>MJ Offer Title</lable>
											<input type ="text" name="code" placeholder="Coupon Title" value="{{old('code',$cluster->coupons->count()>0?$cluster->coupons[0]->code:'')}}">
											<div>
												@if ($errors->has('code'))
													<strong class="text-danger">{{ $errors->first('code') }}</strong>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="status">
												<lable> Status </lable>
												<input type="radio" @if($cluster->coupons->count()>0) {{$cluster->coupons[0]->is_expire==0?'checked':'checked'}} @else checked @endif name="status" value="0"> Active
												<input type="radio" @if($cluster->coupons->count()>0) {{$cluster->coupons[0]->is_expire==1?'checked':''}} @endif name="status" value="1"> Inactive
											</div>
										</div>
										<div class="col-md-2">
											<div class="updt-btn">
												<button type="submit">{{$cluster->coupons->count()>0?'Update':'Create'}}</button>
											</div>
										</div>
									</form>
								</div>

							</div>
							<hr>
							<table class="table table-striped table-bordered table-hover" id="list_testimonial">
								<thead>
								<tr>
									{{--<th>Week</th>--}}
									<th>Dispensary Name</th>
									<th>QR Code</th>
								</tr>
								</thead>
							</table>
							{{--<input type="button" onclick='javascript:deleteAll("{{url('/admin/property/delete-selected')}}")' name="delete" id="delete" value="Delete Selected" class="btn btn-danger">--}}
						</div>
					</div>



					<!-- END PAGE CONTENT INNER -->
				</div>
			</div>
			<!-- END CONTENT -->
		</div>
		<script>
            $(function() {
                $('#list_testimonial').DataTable({
                    processing: true,
                    serverSide: true,
                    //bStateSave: true,
                    ajax: {"url":'{{url("admin/coupon-data/".$cluster->id)}}',"complete":afterRequestComplete},
                    columnDefs: [{
                        "defaultContent": "-",
                        "targets": "_all"
                    }],
                    columns: [
                        { data: 'name', name: 'name' },
                        {data:   "qr_code",
                            render: function ( data, type, row ) {

                                if ( type === 'display' ) {

                                    return '<img width="80px" src="{{url("/storage/app/public/qr-codes/")}}/'+row.qr_code+'">';
                                }
                                return data;
                            },
                            "orderable": false,
                            name: 'Update'

                        },
                    ]
                });
            });
            function confirmDelete(id)
            {
                if(confirm("Do you really want to delete this coupon?"))
                {
                    $("#testimonial_delete_"+id).submit();
                }
                return false;
            }
		</script>
@endsection
