@extends(config("piplmodules.front-view-layout-location"))

@section("meta")
<title>Blog Search</title>
<style>
.tree{list-style:none;padding:0;font-size: calc(100% - 2px);}
.tree > li > a {font-weight:bold;}
.subtree{list-style:none;padding-left:10px;}
.subtree li:before{content:"-";width:5px;position:relative;left:-5px;}
</style>
@endsection

@section("content")
<section class="container">

<h1>Search Results for "{{$keyword}}"</h1>
<div class="row">

@include('blog::left-section')

<div class="col-md-10 col-sm-12">
<div class="panel panel-default">
    <div class="panel-body">
    
    @if(count($posts) < 1)
    <div class="well">We didn't found any post yet.</div>
    @endif
    
    @foreach($posts as $key => $post)
    	
        @if( $key > 0 ) <hr  /> @endif
        
        <div class="row">
        @if($post->post_image)
       <div class="col-md-1 text-center">
            	<img src="{{ asset('storageasset/blog/thumbnails/'.$post->post_image) }}" class="img-responsive thumbnail" />
        </div>
          @endif
        <div class="@if($post->post_image) col-md-11 @else col-md-12 @endif">
            <h4><a href="{{ url('/blog/'.$post->post_url) }}" title="Click to view the post">{{ $post->title }}</a></h4>
            <div>{{ $post->short_description }}</div>
            <br />
            <div><i class="fa fa-calendar"></i> {{ $post->updated_at->format('M d, Y h:i A')}} &nbsp; <i class="fa fa-tag"></i> @foreach($post->tags as $tag) <a href="{{ url('/blog/tags/'.$tag->slug) }}"><i class="badge">{{$tag->name}}</i></a> @endforeach</div>
        </div>
        </div>
    @endforeach
    </div>
</div>
</div>
</div>

</section>
@endsection