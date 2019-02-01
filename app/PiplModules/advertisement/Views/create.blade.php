@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

    <title>Create Advertisement Offer</title>

@endsection

@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- BEGIN PAGE BREADCRUMB -->
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <a href="{{url('admin/dashboard')}}">Dashboard</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="{{url('admin/advertisement/list')}}">Manage Clique Offer</a>
                    <i class="fa fa-circle"></i>

                </li>
                <li>
                    <a href="javascript:void(0);">Create Clique Offer</a>

                </li>
            </ul>



            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i> Create A Clique Offer
                    </div>

                </div>
                <div class="portlet-body form">
                    <form class="form-horizontal" enctype="multipart/form-data" role="form" action="" method="post" >

                        {!! csrf_field() !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-8">
                                        <div class="form-group @if ($errors->has('zone_id')) has-error @endif">
                                            <label class="col-md-6 control-label">Zone<sup>*</sup></label>

                                            <div class="col-md-6">
                                                <select name="zone_id" class="form-control">
                                                    @foreach($all_zone as $zone)
                                                        <option value="{{$zone->id}}">{{$zone->title}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('zone_id'))
                                                    <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('zone_id') }}</strong>
                              </span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="form-group @if ($errors->has('brand_id')) has-error @endif">
                                            <label class="col-md-6 control-label">Brand Name<sup>*</sup></label>

                                            <div class="col-md-6">
                                                <select name="brand_id" class="form-control">
                                                    @foreach($all_brand as $brand)
                                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('brand_id'))
                                                    <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('brand_id') }}</strong>
                              </span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="form-group @if ($errors->has('offer')) has-error @endif">
                                            <label class="col-md-6 control-label">Offer<sup>*</sup></label>

                                            <div class="col-md-6">
                                                <input name="offer" type="text" class="form-control" id="name" value="{{old('offer')}}">
                                                @if ($errors->has('offer'))
                                                    <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('offer') }}</strong>
                              </span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="form-group @if ($errors->has('customer_limit')) has-error @endif">
                                            <label class="col-md-6 control-label">Customer limit<sup>*</sup></label>

                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="customer_limit" value="{{old('customer_limit')}}">
                                                @if ($errors->has('customer_limit'))
                                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('customer_limit') }}</strong>
                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="form-group @if ($errors->has('expiry_date')) has-error @endif">
                                            <label class="col-md-6 control-label">Expiry Date<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input type="text" id="start_date" class="form-control" name="start_date" value="{{old('start_date')}}" placeholder="Start Date">
                                                <input type="text" id="end_date" class="form-control" name="end_date" value="{{old('end_date')}}" placeholder="End Date">
                                                @if ($errors->has('expiry_date'))
                                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('expiry_date') }}</strong>
                                    </span>
                                                @endif

                                            </div>

                                        </div>
                                        <div class="form-group @if ($errors->has('photo')) has-error @endif">
                                            <label class="col-md-6 control-label">Photo<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input type="file" class="form-control" name="photo">
                                                @if ($errors->has('photo'))
                                                    <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('photo') }}</strong>
                                </span>
                                                @endif

                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Is MJ Offer<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input type="checkbox" name="mj_offer" value="1">
                                            </div>

                                        </div>
                                        <div class="form-group @if ($errors->has('publish_status')) has-error @endif" style="display: none">
                                            <label class="col-md-6 control-label">Publish Status <sup></sup></label>
                                            <div class="col-md-6">
                                                <div class="radio-list">
                                                    <label class="radio-inline">
                                                        <input  type="radio" name="publish_status" id="unpublish" value="0" @if(old("publish_status") === "0") checked @endif> Unpublished </label>
                                                    <label class="radio-inline">
                                                        <input checked type="radio" name="publish_status" id="publish" value="1" @if(old("publish_status") === "1") checked @endif> Published </label>

                                                </div>
                                                @if ($errors->has('publish_status'))
                                                    <span class="help-block">
                                    <strong>{{ $errors->first('publish_status') }}</strong>
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var dateFormat = "mm/dd/yy",
            from = $( "#start_date" )
                .datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    minDate: 0,
                })
                .on( "change", function() {
                    to.datepicker( "option", "minDate", getDate( this ) );
                }),
            to = $( "#end_date" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
            })
                .on( "change", function() {
                    from.datepicker( "option", "maxDate", getDate( this ) );
                });

        function getDate( element ) {
            var date;
            try {
                date = $.datepicker.parseDate( dateFormat, element.value );
            } catch( error ) {
                date = null;
            }

            return date;
        }
    </script>
@endsection