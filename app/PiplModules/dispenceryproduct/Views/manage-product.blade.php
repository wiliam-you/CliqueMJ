@extends('layouts.vendor')

@section("meta")

<title>Manage Products</title>

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
					<a href="javascript:void(0)">Manage Products</a>
					
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
							<div class="x_title"><h2>Manage Product</h2>
								<a href="{{url('dispencery/product/create')}}" title="Create new Coupon" class="btn btn-success pull-right"><i
											class="fa fa-plus"></i> Add New</a>
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
								 <th>
									<div class="cust-chqs">  <p><input type="checkbox" id="select_all_delete" ><label for="select_all_delete"></label>  </p></div>
                                                                 </th>
                                                                 
                                                                 <th>Product Name</th>
																 <th>Property</th>
                                                                 <th>Image</th>
																 <th>Quantity</th>
																 <th>Status</th>
																 <th>Update Product Quantity</th>
                                                                 <th>Update</th>
                                                                  <th>Delete</th>
                                                        </tr>
							</thead>
                                                        </table>
                                                       <input type="button" onclick='javascript:deleteAll("{{url('/dispencery/product/delete-selected')}}")' name="delete" id="delete" value="Delete Selected" class="btn btn-danger">
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
        ajax: {"url":'{{url("/dispencery/product/all")}}',"complete":afterRequestComplete},
        columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
        columns: [
           
             {data:   "id",
               render: function ( data, type, row ) {
                
                       if ( type === 'display' ) {
                        
                          return '<div class="cust-chqs">  <p> <input class="checkboxes" type="checkbox"  id="delete'+row.id+'" name="delete'+row.id+'" value="'+row.id+'"><label for="delete'+row.id+'"></label> </p></div>';
                     }
                     return data;
                 },
                   "orderable": false,
                  
                },
            
            { data: 'name', name: 'name',searchable: true},
            { data: 'property', name: 'property',searchable: true},
            {data:   "Image",
                render: function ( data, type, row ) {

                    if ( type === 'display' ) {

                        return '<img width="80px" src="{{url("storage/app/public/product")}}/'+row.image+'">';
                    }
                    return data;
                },
                "orderable": false,
                'searchable':false,
                name: 'Action'

            },
            { data: 'quantity', name: 'quantity','searchable':false},
             { data: 'status', name: 'status','searchable':false},
            // { data: 'created_at', name: 'user.created_at' },
            {data:   "quantity",
                render: function ( data, type, row ) {

                    if ( type === 'display' ) {

                        return '<a  class="btn btn-sm btn-info" href="{{url("/dispencery/product/update/quantity")}}/'+row.id+'">Update Product Quantity</a>';
                    }
                    return data;
                },
                "orderable": false,
                name: 'Update'

            },
            {data:   "Update",
              render: function ( data, type, row ) {
               
                    if ( type === 'display' ) {
                        
                        return '<a class="btn btn-sm btn-primary" href="{{url("dispencery/product/update/")}}/'+row.id+'">Update</a>';
                    }
                    return data;
                },
                  "orderable": false,
                  'searchable':false,
                  name: 'Action'
                  
            },
           
             
            {data:   "Delete",
            render: function ( data, type, row ) {

            if ( type === 'display' ) {

            return '<form id="delete_user_'+row.id+'" method="post" action="{{url("/dispencery/product/delete")}}/'+row.id+'">{{ method_field("DELETE") }} {!! csrf_field() !!}<button onclick="confirmDelete('+row.id+')" class="btn btn-sm btn-danger" type="button">Delete</button></form>';
            }
            return data;
            },
            "orderable": false,
            'searchable':false,
            name: 'Action'

            }
               
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
