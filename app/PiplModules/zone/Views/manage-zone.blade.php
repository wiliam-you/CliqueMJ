@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Manage Zone</title>

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
					<a href="javascript:void(0)">Manage Zone</a>
					
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
								<i class="glyphicon glyphicon-globe"></i>Manage Zone
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
											<div class="btn-group">
												<a href="{{url('/admin/zone/create/')}}" id="sample_editable_1_new" class="btn green">
													Add New Zone <i class="fa fa-plus"></i>
												</a>
											</div>
										</div>

									</div>
								</div>
							<table class="table table-striped table-bordered table-hover" id="tbl_regusers">
							<thead>
							<tr>
								 <th>
									<div class="cust-chqs">  <p><input type="checkbox" id="select_all_delete" ><label for="select_all_delete"></label>  </p></div>
                                                                 </th>
                                                                 
                                                                 <th>Zone Title</th>
																 <th>Manage Clusters</th>
																 <th>Update</th>
                                                                 <th>Delete</th>
                                                        </tr>
							</thead>
                                                        </table>
                                                       <input type="button" onclick='javascript:deleteAll("{{url('/admin/zone/delete-selected')}}")' name="delete" id="delete" value="Delete Selected" class="btn btn-danger">
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
        ajax: {"url":'{{url("/admin/list-zone")}}',"complete":afterRequestComplete},
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
            
            { data: 'title', name: 'title',searchable: true},

            // { data: 'created_at', name: 'user.created_at' },
       
            {data:   "Update",
              render: function ( data, type, row ) {
               
                    if ( type === 'display' ) {
                        
                        return '<a class="btn btn-sm btn-info" href="{{url("admin/cluster/list/")}}/'+row.id+'">Manage Cluster</a>';
                    }
                    return data;
                },
                  "orderable": false,
                  'searchable':false,
                  name: 'Action'
                  
            },

            {data:   "Update",
                render: function ( data, type, row ) {

                    if ( type === 'display' ) {

                        return '<a class="btn btn-sm btn-primary" href="{{url("admin/zone/update/")}}/'+row.id+'">Update</a>';
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

            return '<form id="delete_user_'+row.id+'" method="post" action="{{url("/admin/delete-zone/")}}/'+row.id+'">{{ method_field("DELETE") }} {!! csrf_field() !!}<button onclick="confirmDelete('+row.id+')" class="btn btn-sm btn-danger" type="button">Delete</button></form>';
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
    if(!$('#delete'+id).prop('checked')){
        alert('Please select a record');
        return false
    }
    if(confirm("Do you really want to delete this zone?"))
    {
        
        $("#delete_user_"+id).submit();
    }
    return false;
    }
</script>
@endsection
