@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Manage Dispensary</title>

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
					<a href="{{url('admin/cluster/list/'.$cluster->zone->id)}}">Manage Cluster</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="javascript:void(0)">Manage Dispensary</a>
					
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
								<i class="fa fa-list"></i>Manage Dispensary
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
										@if(count($cluster->clusterDispencery)<$cluster->limit)
										<div class="btn-group">
											<a href="{{url('/admin/dispencery/add/'.$id)}}" id="sample_editable_1_new" class="btn green">
											Add New Dispensary <i class="fa fa-plus"></i>
											</a>
										</div>
											@else
											<div class="alert alert-info">
												If you want to update dispensary list delete cluster and recreate it.
											</div>
										@endif

									</div>
								</div>
							</div>
							<table class="table table-striped table-bordered table-hover" id="list_testimonial">
							<thead>
							<tr>
                                                                <th>Weeks</th>
                                                                <th>Dispensary Name</th>
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
        ajax: {"url":'{{url("/admin/dispencery-data/".$id)}}',"complete":afterRequestComplete},
        columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
        columns: [
           { data: 'sr', name: 'sr'},
           { data: 'name', name: 'name'},
        ]
    });
});
function confirmDelete(id)
{
    if(!$('#delete'+id).prop('checked')){
        alert('Please select a record');
        return false
    }
    if(confirm("Do you really want to delete this dispensary? Cluster current week will be set to 0"))
    {
        $("#testimonial_delete_"+id).submit();
    }
    return false;
    }
</script>
@endsection
