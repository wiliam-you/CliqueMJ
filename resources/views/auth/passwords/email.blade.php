@extends('layouts.app')
@section('meta')
    <title>Forgot Password</title>
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
        <div class="container">
            <div class="main_login">
                <div class="login_heading">
                    <h3>FORGOT PASSWORD REQUEST</h3>
                    <p>Enter the E-mail associated with your account, then click Submit. We'll e-mail you a new password.</p>
                </div>

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong class="text text-danger">{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form_footer">
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
