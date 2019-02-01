@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

    <title>Manage Advertisement Offers</title>
    <style>
        .download-offer{
            padding: 2.5%;
        }
        .mp-progress{
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        position: absolute;
        z-index: 999;

    }
    .mp-inner{
        width: 100%;
        left: 50%;
        top: 45%;
       /* margin-left: -50px;*/
        position: fixed;
    }
    .mp-inner img{
        width: 100px;
    }
        .progress-bar{
            width: 0% !important;
        }
        .modal-dialog {
            width: 600px;
            margin: 80px auto !important;
        }
    </style>
@endsection

@section('content')
    <div class="page-content-wrapper">
    <div class="mp-progress hide">
        <div class="mp-inner">
            <img src="{{url('public/media/backend/images/wait.gif')}}">
        </div>
    </div>
        <div class="page-content">
            <!-- BEGIN PAGE BREADCRUMB -->
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <a href="{{url('admin/dashboard')}}">Dashboard</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="javascript:void(0)">Manage Clique Offers</a>

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
            <div class="alert alert-success" id="queue" style="display: none;">
                Qr codes are generating please wait sometime.
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet box grey-cascade">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-list"></i>Manage Clique Offers
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
                                            <a href="{{url('/admin/advertisement/create')}}" id="sample_editable_1_new" class="btn green">
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
                                <br>
                                <div class="row queue">
                                    <form action="{{url('/admin/advertisement/download/multiple')}}">
                                        <div class="col-md-4">
                                            <label class="form-label">Select File Type</label>
                                            <select class="form-control" name="file_type" id="file_type">
                                                <option value="pdf">PDF Format</option>
                                                <option value="bmp">BMP Format</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Select Brand Name</label>
                                            <select class="form-control" name="brand" id="brand">
                                                {{--<option value="all">All</option>--}}
                                                @if(count($brands) > 0)
                                                    @foreach($brands as $brand)
                                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4 download-offer">
                                            {{--<button class="btn btn-warning" type="submit">--}}
                                            <button class="btn btn-warning" type="button" onclick="downloadFile()">
                                                Download All Clique Offers <i class="fa fa-arrow-circle-down"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                            <table class="table table-striped table-bordered table-hover" id="list_testimonial">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="cust-chqs">  <p><input type="checkbox" id="select_all_delete" ><label for="select_all_delete"></label>  </p><br><input type="button" onclick='javascript:deleteAllAdvertisement("{{url('/admin/advertisement/delete-selected')}}")' name="delete" id="delete" value="Delete Selected" class="btn btn-xs btn-danger queue"></div>
                                    </th>
                                    <th>Brand Image</th>
                                    <th>Qr</th>
                                    <th>Brand Name</th>
                                    <th>Offer</th>
                                    <th>MJ Offer</th>
                                    <th>Expiry Date</th>
                                    <th>Expiry Status</th>
                                    <th>Publish Status</th>
                                    <th>Download Clique Offer</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                            </table>
                            <input type="button" onclick='javascript:deleteAllAdvertisement("{{url('/admin/advertisement/delete-selected')}}")' name="delete" id="delete" value="Delete Selected" class="btn btn-danger queue">
                        </div>
                    </div>



                    <!-- END PAGE CONTENT INNER -->
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <span class="pull-right"><span id="completed"></span>/<span id="quantity"></span></span>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
                    </div>
                    <div class="download-file-list" id="links">
                        <h1>Processing...</h1>

                    </div>

                </div>
                <div class="modal-footer">
                    <button id="close_button" disabled type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal End-->
    <script>
     var table;
        table = $(function() {
            $('#list_testimonial').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                stateSave: true,
                //bStateSave: true,
                ajax: {"url":'{{url("/admin/advertisement-data")}}',"complete":afterRequestComplete},
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
                    {data:   "Image",
                        render: function ( data, type, row ) {

                            if ( type === 'display' ) {
                                if(row.photo ==''){
                                    return '<img width="70px" src="{{url("/public/media/backend/images/product-default.jpeg")}}">';
                                } else {
                                    {{--return '<img width="70px" src="{{url("/storage/app/public/advertisement")}}/' + row.photo + '">';--}}
                                    return '<img width="280px" src="{{url("/storage/app/public/advertisement")}}/' + row.photo + '">';
                                }
                            }
                            return data;
                        },
                        "orderable": false,
                        name: 'Update'

                    },
                    {data:   "qr",name: 'qr'},
                    { data: 'brand.name', name: 'brand.name'},
                    { data: 'offer', name: 'offer'},
                    { data: 'mj_offer', name: 'mj_offer'},
                    { data: 'expiry', name: 'expiry'},
                    { data: 'expiry_status', name: 'expiry_status'},
                    { data: 'status', name: 'status' },
                    {data:   "download",
                        render: function ( data, type, row ) {

                            if ( type === 'display' ) {

                                var str =  '<a style="display: none" class="btn btn-xs btn-primary queue" href="{{url("/admin/advertisement/download/")}}/'+row.id+'">Download PDF</a><br><br>';
                                str += '<a style="display:none"  class="btn btn-xs btn-primary queue" href="{{url("/admin/advertisement/download-bmp/")}}/'+row.id+'">Download BMP</a>';
                                return str;
                            }
                            return data;
                        },
                        "orderable": false,
                        name: 'Update'

                    },

                    {data:   "Delete",
                        render: function ( data, type, row ) {

                            if ( type === 'display' ) {

                                return '<form id="testimonial_delete_'+row.id+'"  method="post" action="{{url("/admin/advertisement/delete")}}/'+row.id+'">{{ method_field("DELETE") }} {!! csrf_field() !!}<button style="display:none" onclick="confirmDelete('+row.id+');" class="btn btn-sm btn-danger queue" type="button">Delete</button></form>';
                            }
                            return data;
                        },
                        "orderable": false,
                        name: 'Delete'

                    }

                ]
            });
        });

        setInterval( function () {
    checkQueue();
}, 5000 );

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

        function checkQueue() {
        $.ajax({
            url:"{{url('/admin/check/queue')}}",
            type: "get",
            dataType:'json',
            success: function(result) {
                if(result){
                    $('#queue').show();
                    $('.queue').hide();
                    table.ajax.reload();
                }
                else {
                    $('#queue').hide();
                    $('.queue').show();
                }
            }
        });
    }
 var check_qr;
    function downloadFile() {
        $('.progress-bar').attr('style', 'width: 0% !important');
        $('.progress-bar').html('');
        checkQr();
        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $.ajax({
            url:"{{url('/admin/advertisement/download/multiple')}}",
            type:"get",
            data:{
                file_type:$('#file_type').val(),
                brand:$('#brand').val(),
            },
            success: function(result) {
                setTimeout(function(){
                    clearInterval(check_qr);
                    setTimeout(function(){
                        $('#completed').text($('#quantity').text());
                        $('.progress-bar').attr('style', 'width: 100% !important');
                        $('.progress-bar').html('100%');
                        $('#close_button').prop('disabled',false);
                    },300);
                },3000);
            }
        });
    }

   function checkQr(){
       check_qr = setInterval( function () {
           $.ajax({
               url:"{{url('/admin/download/pdf')}}",
               type:"get",
               success: function(result) {
                   if(result.completed != undefined){
                       console.log(result);
                       var per = ((parseInt(result.completed)/parseInt(result.quantity))*100);
                       var link = result.links == '' ? result.msg : result.links;
                       var complete = result.completed == null ? 0 : result.completed;
                       var qty = result.quantity == null ? 0 : result.quantity;
                       var bar = parseInt((parseInt(result.completed)/parseInt(result.quantity))*100) > 0 ? parseInt((parseInt(result.completed)/parseInt(result.quantity))*100) : 0;
                       $('.progress-bar').attr('style', 'width: '+per+'% !important');
                       $('.progress-bar').html(bar+'%');
                       $('#links').html(link);
                       $('#completed').text(complete);
                       $('#quantity').text(qty);
                   }
               }
           });
       }, 1000);
        }
    </script>
@endsection
