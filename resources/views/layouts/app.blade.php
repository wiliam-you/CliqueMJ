<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('meta')
    <link rel="stylesheet" href="{{url('/public/media/front/')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{url('/public/media/front/')}}/css/main.css">
    <link rel="stylesheet" href="{{url('/public/media/front/')}}/css/owl.carousel.css">
    <link rel="stylesheet" href="{{url('/public/media/front/')}}/css/owl.theme.css">
    <link rel="stylesheet" href="{{url('/public/media/front/')}}/css/animate.css">
    <link rel="stylesheet" href="{{url('/public/media/front/')}}/css/responsive.css">
    <!--[if lt IE 9]>
    <script src="{{url('public/media/front/bootstrap/js/html5shiv.min.js')}}"></script>
    <script src="{{url('public/media/front/bootstrap/js/respond.min.js')}}"></script>
    <![endif]-->
    <script type="text/javascript"  src="{{url('public/media/front/js/jquery-v2.1.3.js')}}"></script>
    <script>
        var javascript_site_path = '{{url('')}}/';
    </script>

</head>
<body>

<?php

$data['facebook'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','facebook-link')->first();
$data['youtube'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','youtube-link')->first();
$data['instagram'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','instagram-link')->first();
$data['twitter'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','twitter-link')->first();
$data['pinterest'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','pinterest-link')->first();
$data['zip'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','zip-code')->first();
$data['street'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','street')->first();
$data['city'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','city')->first();
$data['address'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','address')->first();
$data['phone'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','phone-no')->first();
$data['logo'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','site-logo')->first();
$data['email'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','contact-email')->first();
$data['title'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','site-title')->first();
$data['footer'] = \App\PiplModules\admin\Models\GlobalSetting::where('slug','footer-text')->first();
?>

@yield('header')
@yield('content')
@if(\Request::Segment(1) !='privacy' && \Request::Segment(1) !='terms')
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="footer-about-info">
                        <img src="{{url('/public/media/front/img/logo.png')}}" alt="footer_logo">
                        <p>{{$data['footer']->value}}</p>
                    </div>
                </div>

                <div class="col-sm-2 col-sm-offset-1">
                    <ul class="ftr-link">
                        <li><a href="{{url('/home#banner')}}"> Home </a></li>
                        <li><a href="{{url('/home#works')}}"> How it Works </a></li>
                        <li><a href="{{url('/home#features')}}"> Features </a></li>
                        <li><a href="{{url('/home#why-cu')}}"> How it Works </a></li>
                    </ul>
                </div>

                <div class="col-sm-3">
                    <ul class="ftr-link">
                        <li><a href="{{url('/home#what_we_do')}}"> What We Do </a></li>
                        <li><a href="{{url('/home#available-on')}}"> Available On</a></li>
                        <li><a href="{{url('/home#contact')}}"> Contact us </a></li>
                    </ul>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="copy-right">
                        <p>Copyright 2018 CliqueMJ - All Rights Reserved.</p>
                    </div>
                </div>
                <div class="col-sm-5 col-sm-offset-1">
                    <div class="social">
                        <ul>
                            <li><a class="fb" href="{{url($data['facebook']->value)}}"><i class="fa fa-facebook"></i></a></li>
                            <li><a class="insta" href="{{url($data['instagram']->value)}}"><i class="fa fa-instagram"></i></a></li>
                            <li><a class="twt" href="{{url($data['twitter']->value)}}"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </footer>
@endif
<script src="{{url('/public/media/front/')}}/js/jquery.js"></script>
<script src="{{url('/public/media/front/')}}/js/bootstrap.min.js"></script>
<script src="{{url('/public/media/front/')}}/js/owl.carousel.min.js"></script>
<script src="{{url('/public/media/front/')}}/js/wow.min.js"></script>
<script src="{{url('/public/media/front/')}}/js/custom.js"></script>
<script>
    function getStart(){
        window.location.hash = 'get_start';
    }
    function screenShot()
    {
        window.location.hash = 'screen_shot';
    }
    function advertize()
    {
        window.location.hash = 'advertize';
    }
    function dispencary()
    {
        window.location.hash = 'dispencary';
    }

    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight){
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    }
</script>
@yield('footer')
</body>
</html>
