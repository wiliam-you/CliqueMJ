@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

    <title>Update Coupon</title>

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
                    <a href="{{url('admin/coupon/list/'.$cluster->id)}}">Manage Coupon</a>
                    <i class="fa fa-circle"></i>

                </li>
                <li>
                    <a href="javascript:void(0);">Update Coupon</a>

                </li>
            </ul>



            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i> Update A Coupon
                    </div>

                </div>
                <div class="portlet-body form">
                    <form class="form-horizontal" enctype="multipart/form-data" role="form" action="" method="post" >

                        {!! csrf_field() !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-8">
                                        <div class="form-group @if ($errors->has('code')) has-error @endif">
                                            <label class="col-md-6 control-label">Coupon Code<sup>*</sup></label>

                                            <div class="col-md-6">
                                                <input name="code" type="text" class="form-control" id="code" value="{{old('code',$coupon->code)}}">
                                                @if ($errors->has('code'))
                                                    <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('code') }}</strong>
                              </span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="form-group @if ($errors->has('coupon_value')) has-error @endif">
                                            <label class="col-md-6 control-label">Coupon Offer <span id="coupon_offer_lable"></span><sup>*</sup></label>

                                            <div class="col-md-6">
                                                <input name="coupon_value" type="text" class="form-control" id="coupon_value" value="{{old('coupon_value',$coupon->coupon_value)}}">
                                                @if ($errors->has('coupon_value'))
                                                    <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('coupon_value') }}</strong>
                              </span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="submit" id="submit" class="btn btn-primary  pull-right">Update</button>
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
@section('footer')
    <script>
        $(document).ready(function(){
            changeCouponOfferLable();
        });

        function changeCouponOfferLable()
        {
            if($('#percent').prop('checked'))
            {
                $('#coupon_offer_lable').text('(%)');
            }
            else if($('#price').prop('checked'))
            {
                $('#coupon_offer_lable').text('($)');
            }
            else
            {
                $('#coupon_offer_lable').text('(grams)');
            }
        }
    </script>
@endsection