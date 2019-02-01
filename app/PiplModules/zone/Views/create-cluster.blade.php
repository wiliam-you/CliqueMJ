@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

    <title>Create Cluster</title>

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
                    <a href="{{url('admin/cluster/list/'.$id)}}">Manage Cluster</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Create Cluster</a>

                </li>
            </ul>



            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i> Create A Cluster
                    </div>

                </div>
                <div class="portlet-body form">
                    <form class="form-horizontal" enctype="multipart/form-data" role="form" action="" method="post" >

                        {!! csrf_field() !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-8">
                                        <div class="form-group @if ($errors->has('title')) has-error @endif">
                                            <label class="col-md-6 control-label">Title<sup>*</sup></label>
                                                <div class="col-md-6">
                                                <input name="title" type="text" class="form-control" id="title" value="{{old('title')}}">
                                                @if ($errors->has('title'))
                                                    <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('title') }}</strong>
                              </span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="form-group @if ($errors->has('limit')) has-error @endif">
                                            <label class="col-md-6 control-label">Dispensary Limit<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input name="limit" type="number" min="3" value="3" class="form-control" id="limit" value="{{old('limit')}}">
                                                @if ($errors->has('limit'))
                                                    <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('limit') }}</strong>
                              </span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="submit" id="submit" class="btn btn-primary  pull-right">Create</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection