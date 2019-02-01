@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

    <title>Add Dispensary</title>

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
                    <a href="{{url('admin/dispencery/create/'.$id)}}">Add Dispensary</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Add Dispensary</a>

                </li>
            </ul>



            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i> Add Dispensary
                    </div>

                </div>
                <div class="portlet-body form">
                    <form class="form-horizontal" enctype="multipart/form-data" role="form" action="" method="post" >

                        {!! csrf_field() !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-8">
                                        <div class="form-group @if ($errors->has('dispencery_id')) has-error @endif">
                                            <label class="col-md-6 control-label">Dispensaries<sup>*</sup></label>

                                            <div class="col-md-6">
                                                <select name="dispencery_id" type="text" class="form-control" id="property">
                                                    @foreach($dispencery as $disp)
                                                        @if($disp->clusterDispencery=='')
                                                            <option value="{{$disp->user_id}}" @if(old('dispencery_id')==$disp->user_id) selected @endif>{{$disp->dispensary_name}}
                                                                <p>({{$disp->address}})</p>
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('dispencery_id'))
                                                    <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('dispencery_id') }}</strong>
                              </span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="submit" id="submit" class="btn btn-primary  pull-right">Add</button>
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