@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Manage Email Templates</title>

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
					<a href="javascript:void(0)">Manage Email Templates</a>
					
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
								<i class="glyphicon glyphicon-globe"></i>Manage Email Templates
                                                                
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
							<table class="table table-striped table-bordered table-hover" id="cms_table">
							<thead>
							<tr>
                                                                 <th>Id</th>
                                                                 <th>Title</th>
                                                                 <th>Subject</th>
                                                                 <th>Action</th>
                                                        </tr>
							</thead>
                                                        </table>

						</div>
					</div>
	
		</div>
	</div>
<script>
$(function() {
    $('#cms_table').DataTable({
        processing: true,
        serverSide: true,
         //bStateSave: true,
        ajax: '{{url("admin/email-templates-data")}}',
        columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
        columns: [          
            { data: 'id', name: 'id'},
            { data: 'template_key', name: 'title'},
             { data: 'subject', name: 'subject'},
           
           {data:   "Update",
              render: function ( data, type, row ) {
               
                    if ( type === 'display' ) {
                        
                        return '<a class="btn btn-sm btn-primary" href="{{url("/admin/email-templates/update/")}}/'+row.id+'">Update</a>';
                    }
                    return data;
                },
                  "orderable": false,
                  name: 'Action'
                  
            }
             
               
        ]
    });
});
</script>
@endsection
