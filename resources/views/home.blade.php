@extends('layouts.app')

 @section('meta')
     <title>Home page</title>
 @endsection
 @include('includes.header')
@section('content')
    <section id="banner" class="banner">
        <div class="banner-inner clearfix">
            <div class="left-banner-content">
                <div class="left-content">
                    <h1>{{$heading->value}}</h1>
                    <p>{{$description->value}}</p>
                    <div class="app-buttons">
                        <div class="apple_btn"> <a href="{{$app_store->value}}"><span> <img src="{{url('/public/media/front/img/apple.png')}}"> </span> </a></div>
                        <div class="google_btn"> <a href="{{$play_store->value}}"> <span> <img src="{{url('/public/media/front/img/google_play.png')}}"> </span> </a></div>
                    </div>
                </div>
            </div>
            <div class="right-banner-content">
                <div class="right-content">
                    <img src="{{url('/public/media/front/img/banner_img.png')}}" alt="banner_img">
                </div>
            </div>
        </div>
    </section>

    <section id="why-cu" class="why-choose-us" style=" background: url({{url('/public/media/front/img/happy_people_bg.jpg')}}">
        <div class="how-head">
            <h3>Why Choose Us</h3>
            <img src="{{url('/public/media/front/img/heading-bottom.png')}}" alt="heading-bottom-img">
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-6 wow fadeInUp">
                    <div class="choose-options">
                        <div class="opt-sec">
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">{{$choose_us[0]->title}}</h4>
                                    <p>{{$choose_us[0]->description}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="opt-sec">
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-leaf" aria-hidden="true"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">{{$choose_us[1]->title}}</h4>
                                    <p>{{$choose_us[1]->description}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="opt-sec no-dis">
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-trophy" aria-hidden="true"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">{{$choose_us[2]->title}}</h4>
                                    <p>{{$choose_us[2]->description}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="why-choose-us-img">
                        <img src="{{url('/public/media/front/img/banner_img.png')}}" alt="why_choose_bg_img">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="what-we-do" id="what_we_do">
        <div class="container">
            <div class="do-inner">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="inner-icon-sec">
                            <img src="{{url('/public/media/front/img/store.png')}}" alt="heading-bottom-img">
                            <h4>REACH</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="inner-icon-sec">
                            <img src="{{url('/public/media/front/img/coin.png')}}" alt="heading-bottom-img">
                            <h4>MOTIVATE</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="inner-icon-sec">
                            <img src="{{url('/public/media/front/img/finger-touch.png')}}" alt="heading-bottom-img">
                            <h4>INTERACT</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="center-heading">
                            <h1> WHAT WE DO</h1>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-4">
                        <div class="inner-icon-sec">
                            <img src="{{url('/public/media/front/img/teamwork.png')}}" alt="heading-bottom-img">
                            <h4>CAPTURE</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="inner-icon-sec">
                            <img src="{{url('/public/media/front/img/computer.png')}}" alt="heading-bottom-img">
                            <h4>LEARN</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="inner-icon-sec">
                            <img src="{{url('/public/media/front/img/flat-world-map.png')}}" alt="heading-bottom-img">
                            <h4>SUPPLY</h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>  


    <section id="works" class="how-it-work clearfix">
        <video autoplay muted loop id="myVideo">
          <source src="{{url('/storage/app/public/global-settings/'.$how_it_work_video->value)}}" type="video/mp4">
        </video>

        <div class="container">
            <div class="how-it">
                <div class="row">
                	<div class="how-head">
                            <h3>How It Work</h3>
                            <img src="{{url('/public/media/front/img/heading-bottom.png')}}" alt="heading-bottom-img">
                    </div>
                    <div class="inner-how">
                        <div class="col-sm-4">
                            <div class="how-step">
                                <div class="banner-item-inner">
                                    <div class="inner-img">
                                        <img src="{{url('/public/media/front/img/drawing.png')}}" alt="drwing_img">
                                    </div>
                                </div>
                                <h2 class="step-no">{{count($get_start) > 0 ? $get_start[0]->heading : ''}}</h2>
                                <h4 class="step-name">{{count($get_start) > 0 ? $get_start[0]->title : ''}}</h4>
                                <p class="step-desc">{{count($get_start) > 0 ? $get_start[0]->description : ''}}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="how-step">
                                <div class="banner-item-inner">
                                    <div class="inner-img">
                                        <img src="{{url('/public/media/front/img/atom.png')}}" alt="drwing_img">
                                    </div>
                                </div>
                                <h2 class="step-no">{{count($get_start) > 0 ? $get_start[1]->heading : ''}}</h2>
                                <h4 class="step-name">{{count($get_start) > 0 ? $get_start[1]->title : ''}}</h4>
                                <p class="step-desc">{{count($get_start) > 0 ? $get_start[1]->description : ''}}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="how-step">
                                <div class="banner-item-inner">
                                    <div class="inner-img">
                                        <img src="{{url('/public/media/front/img/choices.png')}}" alt="drwing_img">
                                    </div>
                                </div>
                                <h2 class="step-no">{{count($get_start) > 0 ? $get_start[2]->heading : ''}}</h2>
                                <h4 class="step-name">{{count($get_start) > 0 ? $get_start[2]->title : ''}}</h4>
                                <p class="step-desc">{{count($get_start) > 0 ? $get_start[2]->description : ''}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="features" class="features wow fadeInUp">
        <div class="how-head">
            <h3>Features</h3>
            <img src="{{url('/public/media/front/img/heading-bottom.png')}}" alt="heading-bottom-img">
        </div>
        <div class="container">
            <div class="row">
                <div class="feature-content">
                    <div class="col-sm-3">
                        <div class="feature-items">
                            <i class="fa fa-file"></i>
                            <h3>{{$feature[0]->title}}</h3>
                            <p>{{$feature[0]->description}}</p>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="feature-items">
                            <i class="fa fa-wifi"></i>
                            <h3>{{$feature[1]->title}}</h3>
                            <p>{{$feature[1]->description}}</p>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="feature-items">
                            <i class="fa fa-wifi"></i>
                            <h3>{{$feature[2]->title}}</h3>
                            <p>{{$feature[2]->description}}</p>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="feature-items">
                            <i class="fa fa-file"></i>
                            <h3>{{$feature[3]->title}}</h3>
                            <p>{{$feature[3]->description}}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="available-on" class="available_on wow fadeInUp" style="background: url({{url('/public/media/front/img/available_bg.png')}}">
        <div class="how-head">
            <h3>Available On</h3>
            <img src="{{url('/public/media/front/img/heading-bottom.png')}}" alt="heading-bottom-img">
        </div>
        <div class="available-inner">
            <p>{{$available_on->value}}</p>

            <div class="available-inner-buttons">
                <div class="app-buttons">
                    <div class="apple_btn"> <a href="{{$app_store->value}}"><span> <img src="{{url('/public/media/front/img/apple.png')}}"> </span> </a></div>
                        <div class="google_btn"> <a href="{{$play_store->value}}"> <span> <img src="{{url('/public/media/front/img/google_play.png')}}"> </span> </a></div>
                </div>
            </div>
        </div>
    </section>

<!--     <section id="screen_shot" class="screen_shots">
        <div class="how-head">
            <h3>Screen Shots</h3>
            <img src="{{url('/public/media/front/img/heading-bottom.png')}}" alt="heading-bottom-img">
        </div>

        <div class="container">
            <div class="row">
                <div class="screen-shot-inner">
                    <div class="mob_bg"><img src="{{url('/public/media/front/img/center_screen.png')}}">
                    </div>
                    <div class="owl-carousel owl-theme" id="screenshot_slider">

                        @foreach($screen_shot as $screen)
                        <div class="item">
                            <img src="{{url('/storage/app/public/screen-shot/'.$screen->image)}}" alt="slide1">
                        </div>
                        @endforeach
                        {{--<div class="item">--}}
                            {{--<img src="{{url('/public/media/front/img/screen2.png')}}" alt="slide1">--}}
                        {{--</div>--}}
                        {{--<div class="item">--}}
                            {{--<img src="{{url('/public/media/front/img/screen1.png')}}" alt="slide1">--}}
                        {{--</div>--}}
                        {{--<div class="item">--}}
                            {{--<img src="{{url('/public/media/front/img/screen3.png')}}" alt="slide1">--}}
                        {{--</div>--}}
                        {{--<div class="item">--}}
                            {{--<img src="{{url('/public/media/front/img/screen4.png')}}" alt="slide1">--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </section> -->




     {{--<section id="faq" class="faq">
        <div class="how-head">
            <h3>FAQ</h3>
            <img src="{{url('/public/media/front/img/heading-bottom.png')}}" alt="heading-bottom-img">
        </div>

        <div class="container">
            <div class="row">
                <div class="inner-faq">
                    <div class="col-sm-7 wow fadeInUp">
                        <div class="main-accordian-sec">
                            <div class="accordian-heading">
                                <h3> Question about Clique ? Answers are here... </h3>
                            </div>
                            @foreach($faqs as $index => $faq)
                            <button class="accordion @if($index == 0) active @endif">{{$faq->question}} </button>
                            <div class="panel"  @if($index == 0) style="display: block; max-height: 160px;" @endif>
                                <p>{{$faq->answer}}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-sm-4 col-sm-offset-1">

                        <div class="tab-video-bg wow fadeInUp">
                            <div class="video-bg">
                                <img src="{{url('/public/media/front/img/video_back.png')}}" alt="video_bg">
                                <div class="video-sec">{!! $faq_video->value !!}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>--}}

    <section id="contact" class="contact-us">
        <div class="how-head">
            <h3>Contact Us</h3>
            <img src="{{url('/public/media/front/img/heading-bottom.png')}}" alt="heading-bottom-img">
        </div>

        <div class="container">
            <div class="row">
                <div class="inner-contact-us">
                    <div class="col-sm-8 wow fadeInUp">
                        <div class="contact-form">
                            <div class="block-address clearfix">
                                <ul>
                                    <li>
                                    	<div class="add-item">
                                        <img src="{{url('/public/media/front/img/call-answer.png')}}" alt="call_img">
                                        <p>Phone Number</p>
                                        <p class="sml">{{$address['phone']->value}}</p>
                                    	</div>
                                    </li>
                                    <li>
                                    	<div class="add-item">
                                        <img src="{{url('/public/media/front/img/message-closed-envelope.png')}}" alt="call_img">
                                        <p>Email Address</p>
                                        <p class="sml">{{$address['email']->value}}</p>
                                    	</div>
                                    </li>
                                    <li>
                                    	<div class="add-item">
                                    	<img src="{{url('/public/media/front/img/locations_icon.png')}}" alt="call_img">
                                        <p>Address</p>
                                        <p class="sml">{{$address['address']->value}},{{$address['street']->value}},{{$address['city']->value}},{{$address['zipcode']->value}}</p>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                            <form action="{{url('send/enquiry/email')}}" method="POST">
                                {!! csrf_field() !!}
                                <div class="f-control">
                                    <input required type="text" name="name" placeholder="Full Name">
                                </div>
                                <div class="f-control">
                                    <input type="email" name="email" placeholder="Email Address">
                                </div>
                                <div class="f-control">
                                    <textarea required name="message" placeholder="Your Message..."></textarea>
                                </div>
                                <div class="submit-btn">
                                    <button type="submit">SEND MESSAGE</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-sm-4 wow fadeInUp">
                        <div class="map_bg">
                            <div class="tab-video-bg">
                                <div class="video-bg map2-bg">
                                    <img src="{{url('/public/media/front/img/video_back.png')}}" alt="video_bg">
                                    <div class="video-sec">
                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d18459370.998883445!2d-109.85424328584168!3d36.99295829983662!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited+States!5e0!3m2!1sen!2sin!4v1527750370653" frameborder="0" style="border:0" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

{{--$2y$10$FwN9Heg0GXf6jbS73D6PHO3kAscOxRgqARJsk3ANA4k8ZWuKhwBLy--}}