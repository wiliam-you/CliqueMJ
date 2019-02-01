@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

    <title>Edit Dispencery</title>

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
                    <a href="{{url('admin/dispencery/create/'.$id)}}">Manage Dispencery</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Edit Dispencery</a>

                </li>
            </ul>



            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i> Edit Dispencery
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
                                            <label class="col-md-6 control-label">Dispenceries<sup>*</sup></label>

                                            <div class="col-md-6">
                                                <select name="dispencery_id" type="text" class="form-control" id="property">
                                                    @foreach($dispencery as $disp)
                                                        @if($disp->clusterDispencery=='' || $disp->clusterDispencery->cluster_id==$cluster_dispencery->zone_id)
                                                            <option value="{{$disp->user_id}}" @if(old('dispencery_id',$cluster_dispencery->dispencery_id)==$disp->user_id) selected @endif>{{$disp->first_name}} {{$disp->last_name}}
                                                                <p>({{$disp->address}})</p>
                                                            </option>

                                                            <?php
                                                            $not_available = 1;
                                                            ?>

                                                        @endif

                                                    @endforeach
                                                    @if($not_available)
                                                        <option>No Dispensaries Available</option>
                                                    @endif
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