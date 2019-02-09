@extends('layouts.app')

@section('content')
@include('includes.header')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                   <p style="color:RED">Your url is not valid, maybe is expired!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
