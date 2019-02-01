@extends(config("piplmodules.back-view-layout-location"))

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
					<a href="{{url('admin/product/list')}}">Manage Product</a>
					<i class="fa fa-circle"></i>
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
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-list"></i>Update {{$product->name}}'s Quantity
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
							<table class="table table-striped table-bordered table-hover" id="list_testimonial">
							<thead>
							<tr>
                                                                <th>Weight</th>
                                                                <th>Quantity</th>
                                                        </tr>
							</thead>
                                                        </table>
                                                        <input type="button" onclick='javascript:deleteAll("{{url('/admin/product/delete-selected')}}")' name="delete" id="delete" value="Delete Selected" class="btn btn-danger">
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

                        return '<input class="inpt" type="text" readonly id="quantity_'+row.id+'" value="'+row.quantity+'"><button class="inpt_btn" onclick="productQuantity('+row.id+')"> <i class="fa fa-minus"></i></button>';
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
</script>
@endsection
