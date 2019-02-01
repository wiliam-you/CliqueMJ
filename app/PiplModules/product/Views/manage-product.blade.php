@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Manage Products</title>

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
					<a href="javascript:void(0)">Manage Products</a>
					
				</li>
                        </ul>
    
           @if (session('update-user-status'))
          <div class="alert alert-success">
                {{ session('update-user-status') }}
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
          </div>
         @endif
      
        @if (session('delete-user-status'))
            <div class="alert alert-success">
                  {{ session('delete-user-status') }}
                  <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </div>
       
      @endif    
    
         <div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								<i class="glyphicon glyphicon-globe"></i>Manage Products
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
                                                                 <th>Dispensary Name</th>
                                                                 <th>Manage Product</th>
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
        ajax: {"url":'{{url("/admin/list-dispencary-data")}}',"complete":afterRequestComplete},
        columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
        columns: [
			{ data: 'name', name: 'name',searchable: true},
            {data:   "Update",
              render: function ( data, type, row ) {
               
                    if ( type === 'display' ) {
                        
                        return '<a class="btn btn-sm btn-primary" href="{{url("admin/product/all/")}}/'+row.user_id+'">Manage Product</a>';
                    }
                    return data;
                },
                  "orderable": false,
                  'searchable':false,
                  name: 'Action'
                  
            },
		]
    });
});
function confirmDelete(id)
{
    if(confirm("Do you really want to delete this user?"))
    {
        
        $("#delete_user_"+id).submit();
    }
    return false;
    }
</script>
@endsection
