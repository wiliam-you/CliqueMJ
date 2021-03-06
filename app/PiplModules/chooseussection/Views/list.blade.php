@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Manage Why Choose Us Section</title>

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
					<a href="javascript:void(0)">Manage Why Choose Us Section</a>
					
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
								<i class="fa fa-list"></i>Manage Why Choose Us Section
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
							{{--<div class="table-toolbar">--}}
								{{--<div class="row">--}}
									{{--<div class="col-md-6">--}}
										{{--<div class="btn-group">--}}
											{{--<a href="{{url('/admin/property/create')}}" id="sample_editable_1_new" class="btn green">--}}
											{{--Add New <i class="fa fa-plus"></i>--}}
											{{--</a>--}}
										{{--</div>--}}
									{{--</div>--}}
									{{----}}
								{{--</div>--}}
							{{--</div>--}}
							<table class="table table-striped table-bordered table-hover" id="list_testimonial">
							<thead>
							<tr>
								{{--<th>--}}
									{{--<div class="cust-chqs">  <p><input type="checkbox" id="select_all_delete" ><label for="select_all_delete"></label>  </p></div>--}}
                                                                {{--</th>--}}
                                                                <th>Id</th>
                                                                <th>Title</th>
                                                                <th>Description</th>
                                                                <th>Update</th>
                                                                {{--<th>Delete</th>--}}
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
        ajax: {"url":'{{url("/admin/chooseus-data")}}',"complete":afterRequestComplete},
        columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
        columns: [

           { data: 'id', name: 'id'},
           { data: 'title', name: 'title'},
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
                        
                        return '<a  class="btn btn-sm btn-primary" href="{{url("/admin/chooseus/update")}}/'+row.id+'">Update</a>';
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
    if(!$('#delete'+id).prop('checked')){
        alert('Please select a record');
        return false
    }
    if(confirm("Do you really want to delete this testimonial?"))
    {
        $("#testimonial_delete_"+id).submit();
    }
    return false;
    }
</script>
@endsection
