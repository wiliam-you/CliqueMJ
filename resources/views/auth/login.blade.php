@extends('layouts.app')
@section('meta')
    <title>User Login</title>
@endsection
@section('content')
    @include('includes.header')
    <section class="login_form">

        @if (session('login-error'))
            <div class="alert alert-danger">
                {{ session('login-error') }}
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </div>
        @endif
        @if (session('register-success'))
            <div class="alert alert-success">
                {{ session('register-success') }}
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </div>
        @endif

        <div class="container">
            <div class="main_login">
                <div class="login_heading"> <h2>LOGIN HERE</h2> </div>
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" id="email" class="form-control" name="email"  value="{{old('email')}}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong class="text text-danger">{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="pwd">Password:</label>
                        <input type="password" id="password" class="form-control" name="password"  value="">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong class="text text-danger">{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form_footer">
                        <div class="forgot_pwd">
                        <p><a href="{{ url('/password/reset') }}">Forgot your password?</a></p>
                        </div>
                        <div class="sbmt_btn">
                            <button type="submit">Submit</button>
                        </div>
                        <div class="clearfix"></div>

                    </div><!-- form_footer -->
                </form>
            </div><!-- main_login -->
        </div><!-- container -->
    </section>
@endsection

