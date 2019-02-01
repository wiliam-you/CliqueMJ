@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

    <title>Manage Global Offers</title>
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
                    <a href="javascript:void(0)">Manage Global Offers</a>

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
                                <i class="fa fa-list"></i>Manage Global Offers
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
                                            <a href="{{url('/admin/advertisement/create-global-offer')}}" id="sample_editable_1_new" class="btn green">
                                                Add New <i class="fa fa-plus"></i>
                                            </a>

                                        </div>
                                        {{--<div class="btn-group">--}}
                                        {{--<a href="{{url('/admin/advertisement/download/all')}}" id="sample_editable_1_new" class="btn btn-warning">--}}
                                        {{--Download All Clique Offers <i class="fa fa-arrow-circle-down"></i>--}}
                                        {{--</a>--}}

                                        {{--</div>--}}
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="list_testimonial">
                                <thead>
                                <tr>
                                    <th>Offer</th>
                                    <th>Qr</th>
                                    <th>MJ Offer</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
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
                ajax: {"url":'{{url("/admin/advertisement/list-global-offer-data")}}',"complete":afterRequestComplete},
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
                columns: [
                    { data: 'offer', name: 'offer'},
                    { data: "qr_code",name: 'qr_code'},
                    { data: 'is_mj_offer', name: 'is_mj_offer'},
                    { data: 'start_date', name: 'start_date'},
                    { data: 'end_date', name: 'end_date'},
                    { data: 'created_at', name: 'created_at' }
                ]
            });
        });
        function confirmDelete(id)
        {
            if(confirm("Do you really want to delete this advertisement offer?"))
            {
                $("#testimonial_delete_"+id).submit();
            }
            return false;
        }
    </script>
@endsection
