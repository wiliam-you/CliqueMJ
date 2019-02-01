@extends(config("piplmodules.back-view-layout-location"))

@section("meta")
<title>Update User Information</title>
@endsection

@section('content')
<div class="container">
    <h1>Update User Information</h1>
    
    @if (session('update-user-status'))
          <div class="alert alert-success">
                {{ session('update-user-status') }}
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
          </div>
    @endif
  	<div class="row">
    <div class="col-md-7 col-sm-12">
  	<form role="form" action="{{url('/admin/update-user/'.$userdata->id)}}" method="post" enctype="multipart/form-data">
    <legend>Personal Details</legend>
    {!! csrf_field() !!}
    
  <div class="form-group">
    <label for="first_name">First Name</label>
    <input name="first_name" type="text" class="form-control" id="first_name" value="{{old('first_name',$userdata->userInformation->first_name)}}">
    
    @if ($errors->has('first_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
        @endif
  </div>
  
  <div class="form-group">
    <label for="last_name">Last Name:</label>
    <input type="text" class="form-control" id="last_name" name="last_name" value="{{old('last_name',$userdata->userInformation->last_name)}}">
    
    @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
        @endif
  </div>
  
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" class="form-control" id="email" name="email" value="{{old('email',$userdata->email)}}">
    
      @if ($errors->has('email'))
               <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
               </span>
        @endif
                                
  </div>
  
  
  <div class="form-group">
    <label>Gender</label>
    <div class="radio-list">
    <label class="radio-inline">
    <input type="radio" name="gender" id="male" value="1" @if(old("gender",$userdata->userInformation->gender) === "1") checked @endif> Male </label>
    <label class="radio-inline">
    <input type="radio" name="gender" id="female" value="2" @if(old("gender",$userdata->userInformation->gender) === "2") checked @endif> Female </label>
    <label class="radio-inline">
    <input type="radio" name="gender" id="not-specified" value="3" @if(old("gender",$userdata->userInformation->gender) === "3") checked @endif> Not Specified </label>
	</div>
	@if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
        @endif
  </div>
  
  <div class="form-group">
    <label for="user_birth_date">Date of birth</label>
    <input type="text" class="form-control" id="user_birth_date" name="user_birth_date" value="{{old('user_birth_date',$userdata->userInformation->user_birth_date)}}">
    
    @if ($errors->has('user_birth_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_birth_date') }}</strong>
                                    </span>
        @endif
  </div>
  
  <div class="form-group">
    <label for="about_me">About Me</label>
    <textarea class="form-control" id="about_me" name="about_me">{{old('about_me',$userdata->userInformation->about_me)}}</textarea>
    
    @if ($errors->has('about_me'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('about_me') }}</strong>
                                    </span>
        @endif
  </div>
  
   <div class="form-group">
    <label for="user_phone">Phone</label>
    <input type="text" class="form-control" id="user_phone" name="user_phone" value="{{old('user_phone',$userdata->userInformation->user_phone)}}">
    
    @if ($errors->has('user_phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_phone') }}</strong>
                                    </span>
        @endif
  </div>
  
   <div class="form-group">
    <label for="user_mobile">Mobile</label>
    <input type="text" class="form-control" id="user_mobile" name="user_mobile" value="{{old('user_mobile',$userdata->userInformation->user_mobile)}}">
    
    @if ($errors->has('user_mobile'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_mobile') }}</strong>
                                    </span>
        @endif
  </div>
  
  <div class="form-group">
    <label for="user_type">Type</label>
    <div class="radio-list">
    <label class="radio-inline">
    <input type="radio" name="user_type" id="male" value="1"  @if(old("user_type",$userdata->userInformation->user_type) === "1") checked @endif> Freelancer </label>
    <label class="radio-inline">
    <input type="radio" name="user_type" id="female" value="2" @if(old("user_type",$userdata->userInformation->user_type) === "2") checked @endif> Employer </label>

	</div>
    
    @if ($errors->has('user_type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_type') }}</strong>
                                    </span>
        @endif

  </div>
  
  
  <button type="submit" class="btn btn-default">Submit</button>
</form>
  </div>
  <div class="col-md-4 col-sm-12 col-md-offset-1">
  <div class="row">
  <form role="form" action="{{url('/admin/update-user-password/'.$userdata->id)}}" method="post">
      {!! csrf_field() !!}
    <legend>Change Password</legend>
    <i class="help-block">Ignore this block, if you don't want to change the password of this user.</i>
    <div class="form-group">
    <label for="new_password">Enter new password</label>
    <input type="password" class="form-control" id="new_password" name="new_password" value="{{old('new_password')}}">
    
    @if ($errors->has('new_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
        @endif

  </div>
  
  <div class="form-group">
    <label for="new_password_confirmation">Confirm new password</label>
    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" value="{{old('new_password_confirmation')}}">
    
    @if ($errors->has('confirm_new_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('confirm_new_password') }}</strong>
                                    </span>
        @endif

  </div>
    
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
    </div>
    <br /><br />
    <div class="row">
    
    <form role="form" action="{{url('/admin/update-user-status/'.$userdata->id)}}" method="post">
      {!! csrf_field() !!}
    <legend>Update user status</legend>
    
    <div class="form-group">
    <label for="active_status">Active Status</label>
    <div class="radio-list">							<label class="radio-inline">
        <input type="radio" name="active_status" value="0"  @if(old("active_status",$userdata->userInformation->user_status) === "0") checked @endif> Inactive <i >(Email not verified) </i></label>
        <label class="radio-inline">
        <input type="radio" name="active_status" value="1" @if(old("active_status",$userdata->userInformation->user_status) === "1") checked @endif> Active </label>
        <label class="radio-inline">
        <input type="radio" name="active_status" value="2" @if(old("active_status",$userdata->userInformation->user_status) === "2") checked @endif> Blocked </label>

	</div>
    
    @if ($errors->has('user_type'))
                <span class="help-block">
                    <strong>{{ $errors->first('user_type') }}</strong>
                </span>
        @endif

  </div>
    
     <button type="submit" class="btn btn-default">Submit</button>
    </form>
    
    </div>
  </div>
  </div>

</div>
@endsection