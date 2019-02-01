@extends(config("piplmodules.back-view-layout-login-location"))

@section("meta")
    <title>Login to Admin panel</title>
@endsection

@section('content')
    <div class="page-lock">
        <div class="page-body">
            <div class="lock-head">
                Admin Login Page
            </div>

            @if (session('login-error'))
                <div class="alert alert-danger">
                    {{ session('login-error') }}
                </div>
            @endif
            @if (session('register-success'))
                <div class="alert alert-success">
                    {{ session('register-success') }}
                    {{--<a href="javascript:void(0);" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>--}}
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="lock-body">
                <form class="lock-form pull-left" id='admin_login' name='admin_login' role="form" method="POST" action="{{ url('/login') }}">
                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                        <input type="email" autocomplete="off" placeholder="Email" class="form-control placeholder-no-fix" name="email" value="{{ old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif

                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                        <input type="password" autocomplete="off" placeholder="Password" class="form-control placeholder-no-fix" name="password">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif

                    </div>

                    <div class="form-group">

                        <div class="checkbox">


                            <input type="checkbox" id="remember" name="remember" <?php if (isset($_COOKIE['remember_flag']) && $_COOKIE['remember_flag']=='1') { ?>checked="checked" <?php } ?>>
                            <label for="rm7">Remember me</label>

                            <a class="btn btn-link pull-right" href="{{ url('/admin/password/reset') }}">Reset Password?</a>
                        </div>
                    </div>

                    <div class="form-group text-center">

                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
