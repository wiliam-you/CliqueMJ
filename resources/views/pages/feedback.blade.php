@extends('layouts.vendor')

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

				</li>
                <li>
					<a href="javascript:void(0)">Manage Feedback</a>
					
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
					<div class="portlet grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								<h2>Manage Feedback</h2>
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
                                                                <th>Patient Name</th>
                                                                <th>Feedback</th>
                                                                <th>Rating</th>
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
        ajax: {"url":'{{url("/dispensary/feedback-data")}}',"complete":afterRequestComplete},
        columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
        columns: [
           { data: 'name', name: 'name'},
           { data: 'feedback', name: 'feedback'},
            { data: 'rating', name: 'rating'},
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
