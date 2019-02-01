@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Create patient</title>

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
					<a href="{{url('admin/manage-patient')}}">Manage patient</a>
                                        <i class="fa fa-circle"></i>
					
				</li>
				<li>
					<a href="javascript:void(0)">Create New patient</a>
					
				</li>
                        </ul>
       
        <div class="portlet box blue">
             <div class="portlet-title">
                        <div class="caption">
                                <i class="fa fa-gift"></i> Create patient
                        </div>

             </div>
             <div class="portlet-body form">
              <form role="form" class="form-horizontal"  method="post" enctype="multipart/form-data">
                  <input type='hidden' name='user_type' id='user_type' value='2'>
                {!! csrf_field() !!}
                <div class="form-body">
                <div class="row">
                    <div class="col-md-12">    
                      <div class="col-md-8">     
                          <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                          <label class="col-md-6 control-label">First Name:<sup>*</sup></label>
                       
                          <div class="col-md-6">     
                          <input name="first_name" type="text" class="form-control" id="first_name" value="{{old('first_name')}}">
                          @if ($errors->has('first_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                          @endif
                        </div>
                       
                  </div>
                          
                   
                  <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                        <label class="col-md-6 control-label">Last Name:<sup>*</sup></label>
                         <div class="col-md-6">         
                           <input type="text" class="form-control" id="last_name" name="last_name" value="{{old('last_name')}}">
                          
                           @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                           @endif
                        </div>
                  </div>
                   <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-md-6 control-label">Email:<sup>*</sup></label>
                         <div class="col-md-6">      
                       <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}">
                        @if ($errors->has('email'))
                                 <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                 </span>
                          @endif
                  </div>
                  </div>
		
                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-md-6 control-label">Password:<sup>*</sup></label>
                        <div class="col-md-6">    
                         <input type="Password" class="form-control" id="password" name="password" value="{{old('password')}}">
                        @if ($errors->has('password'))
                                 <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                 </span>
                          @endif
                  </div>
                  </div>
                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label class="col-md-6 control-label">Confirm Password:<sup>*</sup></label>
                        <div class="col-md-6">    
                         <input type="Password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{old('password_confirmation')}}">
                            @if ($errors->has('password_confirmation'))
                                 <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                 </span>
                          @endif
                        </div>
                    </div>
                     
                  <div class="form-group {{ $errors->has('contact') ? ' has-error' : '' }}">
                        <label class="col-md-6 control-label">Contact Number:<sup>*</sup></label>
                         <div class="col-md-6">      
                       <input type="text" class="form-control" name="contact" value="{{old('contact')}}">
                        @if ($errors->has('contact'))
                                 <span class="help-block">
                                    <strong>{{ $errors->first('contact') }}</strong>
                                 </span>
                          @endif
                  </div>
                  </div>
		<div class="form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
                        <label class="col-md-6 control-label">Date of birth:<sup>*</sup></label>
                         <div class="col-md-6">      
                       <input class="form-control" id="user_birth_date" name="dob" value="{{old('dob')}}">
                        @if ($errors->has('dob'))
                                 <span class="help-block">
                                    <strong>{{ $errors->first('dob') }}</strong>
                                 </span>
                          @endif
                  </div>
                  </div>
		<div class="form-group {{ $errors->has('photo') ? ' has-error' : '' }}">
                        <label class="col-md-6 control-label">Image:<sup>*</sup></label>
                         <div class="col-md-6">      
                       <input type="file" class="form-control" name="photo" value="{{old('photo')}}" accept="image/*">
                        @if ($errors->has('photo'))
                                 <span class="help-block">
                                    <strong>{{ $errors->first('photo') }}</strong>
                                 </span>
                          @endif
                  </div>
                  </div>
		 <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                        <label class="col-md-6 control-label">Address:<sup>*</sup></label>
                         <div class="col-md-6">      
                       <textarea class="form-control" id="address" name="address">{{old('address')}}</textarea>
                        @if ($errors->has('address'))
                                 <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                 </span>
                          @endif
                  </div>
                  </div>
                  
                </div>
                          
                   <div class="form-group">
                         <div class="col-md-12">   
                            <button type="submit" id="submit" class="btn btn-primary  pull-right">Create User</button>
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
<script>
 var nowTemp = new Date();

 var now = new Date(nowTemp.getFullYear() - 21, nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    $(function() {
        $('input[name="dob"]').daterangepicker({
            startDate:now,
            singleDatePicker: true,
            showDropdowns: true,
            maxDate: now
        });
    });
</script>
     
 @endsection
