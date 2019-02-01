@extends('layouts.vendor')

@section("meta")

<title>Update {{$product->name}}'s Quantity</title>
	<style>
	 input.inpt{
		 border-radius: 3px;
		 padding: 4px;
		 border: 1px solid #ccc;
		 text-align: center;
		 width: 60px;
	 }
		.inpt_btn{
			border: 1px solid #d43f3a;
			background: #d43f3a;
			padding:4px 10px;
			color: #fff;
			margin-left:3px;
			transition: all 0.3s linear;
			border-radius:3px;
		}
	 .inpt_btn:focus{
		 box-shadow:none;
	 }
		.inpt_btn .fa {
			font-size: 12px;
			line-height: 10px;
			padding: 4px;
		}
	 .inpt_btn:hover{
		 background: #d9534f;
	 }


	 #list_testimonial_wrapper .row.dishid {
		 display: none !important;
	 }
	 .left_col.scroll-view {
		 width: 100%;
	 }
	 #list_testimonial  .btn.btn-danger {
		 margin-left: 10px;
	 }
	 #list_testimonial .form-control {
		 height:35px;
	 }
	 #list_testimonial  .btn{
		 margin-bottom: 0px;
	 }
	</style>

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
					<a href="{{url('dispencery/product/list')}}">Manage Product</a>
				</li>
				<li>
					<a href="javascript:void(0)">Update {{$product->name}}'s Quantity</a>
					
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
					<div class="">
						<div class="portlet-title">
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
							<table class="table table-striped table-bordered table-hover" id="list_testimonial">
							<thead>
							<tr>
                                                                <th>Weight</th>
                                                                <th>Quantity</th>
                                                                <th>Deduct Product Quantity</th>
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
<script>
$(function() {
    $('#list_testimonial').DataTable({
        processing: true,
        serverSide: true,
         //bStateSave: true,
        ajax: {"url":'{{url("/admin/product-size-data/".$product->id)}}',"complete":afterRequestComplete},
        columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
        columns: [
           { data: 'size', name: 'size'},
            {data:   "quantity",
                render: function ( data, type, row ) {

                    if ( type === 'display' ) {

                        return '<label>'+row.quantity+'</label>';
                    }
                    return data;
                },
                "orderable": false,
                name: 'Update'

            },
            { data: 'deduct', name: 'deduct' },
               
        ]
    });
});
function confirmDelete(id)
{
    if(confirm("Do you really want to delete this product?"))
    {
        $("#testimonial_delete_"+id).submit();
    }
    return false;
    }

    function productQuantity(id) {
		if($('#quantity_'+id).val()>0)
		{
			$.ajax({
				url: '{{url("/product-quantity")}}',
				type: "get",
				dataType: "json",
				data: {
					size_id: id,
				},
				success: function (result) {
				    $('#quantity_'+id).val(result);
                }
			});
		}
    }

	function productQuantity(id) {
		if($('#quantity_'+id).val()>0)
		{
			$.ajax({
				url: '{{url("/product-quantity")}}',
				type: "get",
				dataType: "json",
				data: {
					size_id: id,
				},
				success: function (result) {
				    $('#quantity_'+id).val(result);
                }
			});
		}
    }
</script>
@endsection
