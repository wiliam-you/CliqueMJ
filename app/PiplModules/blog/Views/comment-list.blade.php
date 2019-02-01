@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Manage Blog Posts Comments</title>

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
					<a href="javascript:void(0)">Manage Blog Posts Comments</a>
					
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
								<i class="fa fa-list"></i>Manage Blog Posts Comments
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
							<table class="table table-striped table-bordered table-hover" id="list_blogs_comment">
							<thead>
							<tr>
								<th class="table-checkbox">
									<input type="checkbox" class="group-checkable" id="select_all_delete" data-set="#list_blogs .checkboxes"/>
								</th>
                                                                <th>Id</th>
                                                                <th>Comment</th>
                                                                
                                                        </tr>
							</thead>
                                                        </table>
                                                      <input type="button" onclick='javascript:deleteAll("{{url('/admin/blog-post/delete-selected')}}")' name="delete" id="delete" value="Delete Selected" class="btn btn-danger">
						</div>
					</div>
	
				
			
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<script>
$(function() {
    $('#list_blogs_comment').DataTable({
        processing: true,
        serverSide: true,
         bStateSave: true,
        ajax: {"url":'{{url("/admin/blog-post-comments-data")}}/{{$post_id}}',"complete":afterRequestComplete},
        columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
        columns: [
            {data:   "id",
              render: function ( data, type, row ) {
                
                    if ( type === 'display' ) {
                        
                        return '<div class="checker"> <span><input class="checkboxes" type="checkbox"  name="delete'+row.id+'" value="'+row.id+'"></span></div>';
                    }
                    return data;
                },
                  "orderable": false,
                  
               },
           { data: 'id', name: 'id'},
           { data: 'comment', name: 'comment'}
           
            
            
               
        ]
    });
});
function confirmDelete(id)
{
    if(confirm("Do you really want to delete this comment?"))
    {
        $("#blog_delete_"+id).submit();
    }
    return false;
}
</script>
@endsection
