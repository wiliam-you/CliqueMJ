@extends('layouts.app')

 @section('meta')
     <title>Home page</title>
 @endsection
 @include('includes.header')
@section('content')
    <section class="banner" style="background-image:url({{url('/public/media/front/')}}/img/bannerbg.png);">
<div class="container">
            <div class="screen">
                <div class="cover_code wow bounceInLeft" data-wow-duration="1s" data-wow-delay="1.2s">
                    <img src="{{url('/public/media/front/')}}/img/cover.png" alt="cover_img">
                </div>
                <div class="advertize wow bounceInDown">
                    <h3>Lorem ipsum dolor sit amet</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Pellentesque euismod</p>
                    <div class="adv_btns">
                        <button onclick="advertize()">Advertise</button>
                        <button class="b_active" onclick="dispencary()">Dispensary</button>
                    </div>
                </div>
                <div class="mob_adv wow bounceInRight" data-wow-duration="1s" data-wow-delay="1.2s">
                    <img src="{{url('/public/media/front/')}}/img/mobscreen.png" alt="cover_img">
                </div>
            </div>
        </div>
    </section>

    <section class="dispensary" id="dispencary">
        <div class="dispensary_heading"> <h2>DISPENSARY</h2></div>
        <div class="container-fluid dispensary_p">
            <div class="row">
                <div class="col-md-4 col-sm-4 kart wow bounceInLeft" data-wow-duration="1s" data-wow-delay="1.2s">
                    <div class="inner_info">
                        <img src="{{url('/public/media/front/')}}/img/shopping-bag.png" alt="kart image">
                        <h4>{{count($dispencary) > 0 ? $dispencary[0]->title : ''}}</h4>
                        <p>{{count($dispencary) > 0 ? $dispencary[0]->description : ''}}</p>
                    </div>
                </div><!-- col-md-4 -->
                <div class="col-md-4 col-sm-4 kart active_cart wow bounceInDown">
                    <div class="inner_info">
                        <img src="{{url('/public/media/front/')}}/img/line-chart.png" alt="kart image">
                        <h4>{{count($dispencary) > 0 ? $dispencary[1]->title : ''}}</h4>
                        <p>{{count($dispencary) > 0 ? $dispencary[1]->description : ''}}</p>
                    </div>
                </div><!-- col-md-4 -->
                <div class="col-md-4 col-sm-4 kart wow bounceInRight" data-wow-duration="1s" data-wow-delay="1.2s">
                    <div class="inner_info">
                        <img src="{{url('/public/media/front/')}}/img/like.png" alt="kart image">
                        <h4>{{count($dispencary) > 0 ? $dispencary[2]->title : ''}}</h4>
                        <p>{{count($dispencary) > 0 ? $dispencary[2]->description : ''}}</p>
                    </div>
                </div><!-- col-md-4 -->
            </div>
        </div>
    </section>

    <section class="adertize" id="advertize">
        <div class="advertize_heading"> <h2>ADVERTISE</h2></div>
        <div class="container-fluid adertize_product">
            <div class="row">
                <div class="col-md-4 col-sm-4 product_bg wow bounceInLeft" style="background-image:url({{url('/public/media/front/')}}/img/product1.png);" data-wow-duration="1s" data-wow-delay="1.2s">
                    <div class="product">
                        <img src="{{url('/storage/app/public/advertize-section/'.count($advertize) > 0 ? $advertize[0]->image : '')}}" alt="bag image">
                        <h3> {{count($advertize) > 0 ? $advertize[0]->title : ''}} </h3>
                        <p>{{count($advertize) > 0 ? $dispencary[0]->description : ''}}</p>
                    </div>
                </div><!-- col-md-4 -->
                <div class="col-md-4 col-sm-4 product_bg wow bounceInDown" style="background-image:url({{url('/public/media/front/')}}/img/product2.png);">
                    <div class="product">
                        <img src="{{url('/storage/app/public/advertize-section/'.count($advertize) > 0 ? $advertize[1]->image : '')}}" alt="bag image">
                        <h3> {{count($advertize) > 0 ? $advertize[1]->title : ''}} </h3>
                        <p>{{count($advertize) > 0 ? $dispencary[1]->description : ''}}</p>
                    </div>
                </div><!-- col-md-4 -->
                <div class="col-md-4 col-sm-4 product_bg wow bounceInRight" style="background-image:url({{url('/public/media/front/')}}/img/product3.png);" data-wow-duration="1s" data-wow-delay="1.2s">
                    <div class="product">
                        <img src="{{url('/storage/app/public/advertize-section/'.count($advertize) > 0 ? $advertize[2]->image : '')}}" alt="bag image">
                        <h3> {{count($advertize) > 0 ? $advertize[2]->title : ''}} </h3>
                        <p>{{count($advertize) > 0 ? $dispencary[2]->description : ''}}</p>
                    </div>
                </div><!-- col-md-4 -->
            </div><!-- row -->
        </div><!-- container-fluid-->
    </section>

    <section class="screen_shot" id="screen_shot">
        <div class="screen_heading"> <h2>SCREEN SHOTS</h2> </div>
        <div class="container">
            <div class="row">
                <div class="mob_bg"><img src="{{url('/public/media/front/')}}/img/center_screen.png">
                </div>
                <div class="owl-carousel owl-theme" id="sliderr">
                    @if(count($screen_shot) > 0)
                    @foreach($screen_shot as $screen)
                    <div class="item">
                        <img src="{{url('/storage/app/public/screen-shot/'.$screen->image)}}" alt="slide1">
                    </div>
                    @endforeach
                        @endif
                </div><!-- owl-carousel -->
            </div><!-- row -->
        </div><!-- contaner -->
    </section>

    <section id="get_start" class="get_started" style="background-image:url({{url('/public/media/front/')}}/img/get_startedbg.png);">
        <div class="get_heading"> <h2>GET STARTED</h2> </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="started wow bounceInLeft" data-wow-duration="1s" data-wow-delay="1.2s">
                        <h3>Sign Up</h3>
                        <h4>{{count($get_start) > 0 ? $get_start[0]->title : ''}}</h4>
                        <p>{{count($get_start) > 0 ? $get_start[0]->description : ''}}</p>
                        <!--<button>Sign Up</button>-->
                    </div>
                </div><!-- col-md-4 -->
                
                <div class="col-md-4 col-sm-4">
                    <div class="started wow bounceInDown">
                        <h3>Create Your Shop</h3>
                        <h4>{{count($get_start) > 0 ? $get_start[1]->title : ''}}</h4>
                        <p>{{count($get_start) > 0 ? $get_start[1]->description : ''}}</p>
                        <!--<button>Create Your Shop</button>-->
                    </div>
                </div><!-- col-md-4 -->
                <div class="col-md-4 col-sm-4">
                    <div class="started wow bounceInRight" data-wow-duration="1s" data-wow-delay="1.2s">
                        <h3>Contact Us</h3>
                        <h4>{{count($get_start) > 0 ? $get_start[2]->title : ''}}</h4>
                        <p>{{count($get_start) > 0 ? $get_start[2]->description : ''}}</p>
                        <!--<button>Contact Us</button>-->
                    </div>
                </div><!-- col-md-4 -->
            </div><!-- row -->
        </div><!-- container-fluid -->
    </section>
@endsection

