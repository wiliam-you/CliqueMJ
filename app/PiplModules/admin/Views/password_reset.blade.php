@extends(config("piplmodules.back-view-layout-login-location"))

@section("meta")
<title> Reset My Password</title>
@endsection

@section('content')
       <div class="page-lock">
	<div class="page-body">
		<div class="lock-head">
			 Reset My Password
		</div>
		
              @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
               <div class="lock-body">
                      <form role="form" method="POST" action="{{ url('/password/email') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                          
                                <input type="email" autocomplete="off" placeholder="Email" class="form-control placeholder-no-fix" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                          
                        </div>
                         <div class="form-group">
                             <a class="btn btn-link pull-right" href="{{ url('/admin/login') }}">Back To Login?</a>
                             
                            
                          
                        </div>
                        
                        <div class="form-group text-center">
                             	
                                <button type="submit" class="btn btn-primary">
                                    Send Reset Link
                                </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
