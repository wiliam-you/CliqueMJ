@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Manage Advertise Section</title>

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
					<a href="javascript:void(0)">Manage Advertise Section</a>
					
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
								<i class="fa fa-list"></i>Manage Advertise Section
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
                                                                <th>Id</th>
                                                                <th>Title</th>
                                                                <th>Image</th>
                                                                <th>Description</th>
                                                                <th>Update</th>
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
        ajax: {"url":'{{url("/admin/advertize-section-data")}}',"complete":afterRequestComplete},
        columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
        columns: [

           { data: 'id', name: 'id'},
           { data: 'title', name: 'title'},
           {data:   "image",
              render: function ( data, type, row ) {
               
                    if ( type === 'display' ) {
                        
                        return '<img width="50px" src="{{url("/storage/app/public/advertize-section/")}}/'+row.image+'">';
                    }
                    return data;
                },
                  "orderable": false,
                  name: 'Update'
                  
            },
            
			
//           { data: 'description', name: 'description' },
			{data:   "description",
              render: function ( data, type, row ) {

                    if ( type === 'display' ) {

                        return '<p>'+row.description+'</p>';
                    }
                    return data;
                },
                  "orderable": false,
                  name: 'Update'

            },

		{data:   "Update",
              render: function ( data, type, row ) {
               
                    if ( type === 'display' ) {
                        
                        return '<a  class="btn btn-sm btn-primary" href="{{url("/admin/advertise-section/update")}}/'+row.id+'">Update</a>';
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
    if(confirm("Do you really want to delete this testimonial?"))
    {
        $("#testimonial_delete_"+id).submit();
    }
    return false;
    }
</script>
@endsection
