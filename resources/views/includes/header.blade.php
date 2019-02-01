<?php
$data['logo'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','site-logo')->first();
?>
<header>
    @if(\Request::Segment(1)=='privacy' || \Request::Segment(1)=='terms')
    <div class="custom-header clearfix">
        <div class="logo">
            <a href="javascript:void(0)"><img src="{{url('/public/media/front/img/logo.png')}}" alt="logo_img"></a>
        </div>
        {{--<div class="mob-menu">
            <span></span>
        </div>--}}
        {{--<div class="navigation-bar">
            <ul>
                <li><a href="{{url('/home#banner')}}"> Home </a></li>
                @if(Auth::check())
                        <li><a href="{{url('/dispencery/logout')}}"> Logout </a></li>
                @else
                    <li><a href="{{url('/login')}}"> Login </a></li>
                @endif
            </ul>
        </div>--}}
    </div>
    @else
        <div class="custom-header clearfix">
        <div class="logo">
            <a href="{{ url('/') }}/"><img src="{{url('/public/media/front/img/logo.png')}}" alt="logo_img"></a>
        </div>
        <div class="mob-menu">
            <span></span>
        </div>
        <div class="navigation-bar">
            <ul>
                <li><a href="{{url('/home#banner')}}"> Home </a></li>
                {{--<li><a href="{{url('/about-us')}}"> About Us </a></li>--}}
                @if(Auth::check())
                        <li><a href="{{url('/dispencery/logout')}}"> Logout </a></li>
                @else
                    <li><a href="{{url('/login')}}"> Login </a></li>
                @endif
            </ul>
        </div>
    </div>
    @endif
</header>
