@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Update Patient Profile</title>

@endsection

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">

        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb hide">
            <li>
                <a href="{{url('admin/dashboard')}}">Home</a><i class="fa fa-circle"></i>
            </li>
            <li class="active">
                Dashboard
            </li>
        </ul>

        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('admin/manage-patient')}}">Manage patient</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="javascript:void(0);">Update  patient Profile</a>
            </li>
        </ul>
        <div class="profile-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase">Update patient Profile</span>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="@if(!($errors->has('email') || $errors->has('confirm_email')|| $errors->has('current_password')|| $errors->has('new_password') || $errors->has('confirm_password') || session('password-update-fail'))) active @endif">
                                    <a href="#tab_1_1" data-toggle="tab">Personal Informations</a>
                                </li>
                                <li class="@if($errors->has('email') || $errors->has('confirm_email')) active @endif">
                                    <a href="#tab_1_3" data-toggle="tab">Change Email</a>
                                </li>
                                <li class="@if($errors->has('current_password')|| $errors->has('new_password') || $errors->has('confirm_password') || session('password-update-fail')!='') active @endif">
                                    <a href="#tab_1_2" data-toggle="tab">Change Password</a>
                                </li>

                            </ul>
                        </div>
                        @if (session('profile-updated'))
                        <div class="alert alert-success">
                            {{ session('profile-updated') }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        </div>
                        @endif
                        @if (session('password-update-fail'))
                        <div class="alert alert-danger">
                            {{ session('password-update-fail') }}
                        </div>
                        @endif
                        <div class="portlet-body">
                            <div class="tab-content">
                                
                                
                                <!-- PERSONAL INFO TAB -->
                                <div class="tab-pane @if(!($errors->has('email') || $errors->has('confirm_email')|| $errors->has('current_password')|| $errors->has('new_password') || $errors->has('confirm_password') || session('password-update-fail'))) active @endif" id="tab_1_1">
                                    <form name="update_profile"  id="update_profile" role="form" method="post" action="{{ url('admin/update-registered-patient/'.$user_info->id)}}" enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                            <label class="control-label">First Name <sup style='color:red;'>*</sup></label>

                                            <input type="text" class="form-control" name="first_name" value="{{old('first_name',$user_info->userInformation->first_name)}}">
                                            @if ($errors->has('first_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('first_name') }}</strong>
                                            </span>
                                            @endif

                                        </div>

                                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                            <label class="control-label">Last Name <sup style='color:red;'>*</sup></label>

                                            <input type="text" class="form-control" name="last_name" value="{{old('last_name',$user_info->userInformation->last_name)}}">
                                            @if ($errors->has('last_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                            @endif

                                        </div>

                                        
                                        {{--<div class="form-group{{ $errors->has('user_status') ? ' has-error' : '' }}">--}}
                                            {{--<label class="control-label">Status<sup style='color:red;'>*</sup> </label>--}}

                                            {{--<select class="form-control" name="user_status" id="user_status">--}}
                                                {{--<option value="">--Select Status--</option>--}}
                                                {{--<option value="1" @if($user_info->userInformation->user_status==1) selected=selected @endif>Active</option>--}}
                                                {{--<option value="2" @if($user_info->userInformation->user_status==2) selected=selected @endif>Blocked</option>--}}

                                            {{--</select>--}}
                                            {{--@if ($errors->has('user_status'))--}}
                                            {{--<span class="help-block">--}}
                                                {{--<strong>{{ $errors->first('user_status') }}</strong>--}}
                                            {{--</span>--}}
                                            {{--@endif--}}

                                        {{--</div>--}}
                                        <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                                            <label class="control-label">Date Of Birth <sup style='color:red;'>*</sup> </label>

                                            <input class="form-control" id="dob" name="dob" value="{{old('dob',$user_info->userInformation->user_birth_date)}}">
                                            @if ($errors->has('dob'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('dob') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
                                            <label class="control-label">Contact Number<sup style='color:red;'>*</sup> </label>

                                            <input type="text" class="form-control" name="contact" value="{{old('contact',$user_info->userInformation->user_mobile)}}">
                                            @if ($errors->has('contact'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('contact') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                                            <label class="control-label">Image</label>

                                            <input type="file" class="form-control" name="photo" value="{{old('photo')}}" accept="image/*">
                                            @if ($errors->has('photo'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('photo') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        @if($user_info->userInformation->profile_picture!='')
                                        <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                                            <img width="100px" src="{{url('/storage/app/public/patient/'.$user_info->userInformation->profile_picture)}}" alt="Patient image">
                                        </div>
                                        @endif
                                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                            <label class="control-label">Address</label>

                                            <textarea class="form-control" name="address">{{old('address',$user_info->userInformation->address)}}</textarea>

                                        </div>

                                        <div class="margiv-top-10">
                                            <input type="submit" class="btn green-haze" value="Save Changes">
                                            <a href="{{url('admin/manage-users')}}" class="btn default">
                                                Cancel 
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                
                                
                    <!--EMAIL CHANGE TAB-->            
                                <div class="tab-pane @if($errors->has('email') || $errors->has('confirm_email'))  active @endif" id="tab_1_3">
                                    <form name="update_email"  id="update_email" role="form" method="POST" action="{{ url('/admin/update-registered-patient-email/'.$user_info->id) }}">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label class="control-label">Current Email: </label>
                                            <label class="control-label">{{$user_info->email}}</label>
                                        </div>
                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label class="control-label">New Email</label>

                                            <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}">
                                            @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="form-group{{ $errors->has('confirm_email') ? ' has-error' : '' }}">
                                            <label class="control-label">Confirm Email</label>
                                            <input type="text" class="form-control" id="confirm_email" name="confirm_email" value="{{old('confirm_email')}}">
                                            @if ($errors->has('confirm_email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('confirm_email') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="margiv-top-10">
                                            <input type="submit" class="btn green-haze" value="Change Email">
                                            <a href="{{url('admin/manage-users')}}" class="btn default">
                                                Cancel 
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                
                                
                                
                                <!-- CHANGE PASSWORD TAB -->
                                <div class="tab-pane @if($errors->has('current_password')|| $errors->has('new_password') || $errors->has('confirm_password') || session('password-update-fail')) active @endif" id="tab_1_2">
                                    <form name="update_password"  id="update_password" role="form" method="POST" action="{{ url('/admin/update-registered-patient-password/'.$user_info->id) }}">
                                        {!! csrf_field() !!}

                                    
                                        <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                            <label class="control-label">New Password</label>

                                            <input type="password" class="form-control" id="new_password" name="new_password" value="{{old('new_password')}}">
                                            @if ($errors->has('new_password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('new_password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                                            <label class="control-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="{{old('confirm_password')}}">
                                            @if ($errors->has('confirm_password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('confirm_password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="margiv-top-10">
                                            <input type="submit" class="btn green-haze" value="Change Password">
                                            <a href="{{url('admin/manage-users')}}" class="btn default">
                                                Cancel 
                                            </a>
                                        </div>
                                    </form>
                                </div>

                                <!-- END CHANGE PASSWORD TAB -->
                                <!-- PRIVACY SETTINGS TAB -->

                                <!-- END PRIVACY SETTINGS TAB -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<script>
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear() - 21, nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    var date_of_birth = '';
    if($('#dob').val()=='')
    {
        date_of_birth = now;
    }
    else
    {
        date_of_birth = $('#dob').val();
    }
    $(function() {
        $('input[name="dob"]').daterangepicker({
            startDate: date_of_birth,
            singleDatePicker: true,
            showDropdowns: true,
            maxDate: now
        });
    });
</script>
@endsection
