@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

    <title>Manage Generic Messages</title>
    <style>
        .download-offer{
            padding: 2.5%;
        }
    </style>
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
                    <a href="javascript:void(0)">Manage Generic Messages</a>
                </li>
                <li class="pull-right">
                    <a href="{{ url('/') }}/admin/genericmessages/create-generic-msg">+ Create Generic Message</a>
                </li>
            </ul>
            @if (session('status'))
                <div class="alert alert-danger">
                    {{ session('status') }}
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet box grey-cascade">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-list"></i>Manage Generic Messages
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
                                    <th>Message</th>
                                    <th>Created At</th>
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
    </div>
    <script>
        $(function() {
            $('#list_testimonial').DataTable({
                processing: true,
                serverSide: true,
                //bStateSave: true,
                ajax: {"url":'{{url("/admin/genericmessages-data")}}',"complete":afterRequestComplete},
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
                columns: [
                    { data:   "message",name: 'message'},
                    { data:   "created_at",name: 'created_at'},

                ]
            });
        });
        function confirmDelete(id)
        {
            if(!$('#delete'+id).prop('checked')){
                alert('Please select a record');
                return false
            }
            if(confirm("Do you really want to delete this advertisement offer?"))
            {
                $("#testimonial_delete_"+id).submit();
            }
            return false;
        }
    </script>
@endsection
