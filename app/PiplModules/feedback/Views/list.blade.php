@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Manage Feedback</title>

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
					<a href="{{url('admin/feedback/list')}}">Manage Feedback</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="javascript:void(0)">Manage Feedback of {{$user->dispensary_name}}</a>
					
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
								<i class="fa fa-list"></i>Manage Feedback
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
								<th>
									<div class="cust-chqs">  <p><input type="checkbox" id="select_all_delete" ><label for="select_all_delete"></label>  </p></div>
                                                                </th>
                                                                <th>Patient Name</th>
                                                                <th>Feedback</th>
                                                                <th>Rating</th>
                                                                <th>Update</th>
                                                                <th>Delete</th>
                                                        </tr>
							</thead>
                                                        </table>
                                                        <input type="button" onclick='javascript:deleteAll("{{url('/admin/feedback/delete-selected')}}")' name="delete" id="delete" value="Delete Selected" class="btn btn-danger">
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
        ajax: {"url":'{{url("/admin/feedback-data/".$user->user_id)}}',"complete":afterRequestComplete},
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
           { data: 'name', name: 'name'},
           { data: 'feedback', name: 'feedback'},
            { data: 'rating', name: 'rating'},
            {data:   "Update",
              render: function ( data, type, row ) {
               
                    if ( type === 'display' ) {
                        
                        return '<a  class="btn btn-sm btn-primary" href="{{url("/admin/feedback/update")}}/'+row.id+'">Update</a>';
                    }
                    return data;
                },
                  "orderable": false,
                  name: 'Update'
                  
            },
            
             {data:   "Delete",
              render: function ( data, type, row ) {
               
                    if ( type === 'display' ) {
                        
                        return '<form id="testimonial_delete_'+row.id+'"  method="post" action="{{url("/admin/feedback/delete")}}/'+row.id+'">{{ method_field("DELETE") }} {!! csrf_field() !!}<button onclick="confirmDelete('+row.id+');" class="btn btn-sm btn-danger" type="button">Delete</button></form>';
                    }
                    return data;
                },
                  "orderable": false,
                  name: 'Delete'
                  
            }
               
        ]
    });
});
function confirmDelete(id)
{
    if(confirm("Do you really want to delete this feedback?"))
    {
        $("#testimonial_delete_"+id).submit();
    }
    return false;
    }
</script>
@endsection
