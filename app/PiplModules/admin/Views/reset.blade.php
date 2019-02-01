@extends(config("piplmodules.back-view-layout-login-location"))

@section("meta")
<title> Reset Password</title>
@endsection

@section('content')
       <div class="page-lock">
	<div class="page-body">
		<div class="lock-head">
			 Reset Password
		</div>
		
              @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
               <div class="lock-body">
                    <form  role="form" method="POST" action="{{ url('/password/reset') }}">
                        {!! csrf_field() !!}

                        <input type="hidden" name="token" value="{{ $token }}">

                       <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                           
                          
                                <input type="email" class="form-control" name="email" value="{{ $email or old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                           
                          
                            <input type="password" placeholder="Type new password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                           
                          
                             <input type="password" placeholder="Confirm password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                        </div>
                      


                        
                        <div class="form-group text-center">
                             	
                                <button type="submit" class="btn btn-primary">
                                    Reset Password
                                </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
