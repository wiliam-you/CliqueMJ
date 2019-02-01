@extends('layouts.app')
@section('meta')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="{{$page_information->page_meta_keywords}}">
    <meta name="keywords" content="{{$page_information->page_meta_descriptions}}">
    <title>{{$page_information->page_title}}</title>
@endsection
@section('content')
    @include('includes.header')
    <section class="about-sec">
    <div class="container">
        <div class="inner-page-info">
            <div class="row">
                <div class="col-sm-12">
                    <div class="privacy-text">
                        <div class="common-heading">
                            <h2>{{$page_information->page_title}}</h2>
                        </div>

                        <p>
                            {!! $page_information->page_content !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- container -->
</section>
@endsection