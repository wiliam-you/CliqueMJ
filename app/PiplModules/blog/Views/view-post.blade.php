@extends(config("piplmodules.front-view-layout-location"))

@section('meta')
<title>{{$page_information->seo_title}}</title>
<meta name="keywords" content="{{$page_information->seo_keywords}}" />
<meta name="description" content="{{$page_information->seo_description}}" />
@endsection

@section("content")
<section class="container">

<h1>{{$page_information->title}}</h1>

@if($page->post_image)
<div class="row">
<img src="{{asset('storageasset/blog/'.$page->post_image)}}" class="img-responsive" />
</div>
@endif

<div>
{!! $page_information->description !!}
</div>

@if($page->post_attachments)
Attachment(s):
			<ul class="list-inline">
 					@foreach($page->post_attachments as $key=>$attachment)
                    <li><a target="new" href="{{asset('storageasset/blog/'.$attachment['original_name'])}}"><i class="fa fa-download"></i> {{$attachment['display_name']}}</a></li>
                    @endforeach
            </ul>
@endif

@if(count($page->tags))
Tags: 
<ul class="list-inline">
@foreach($page->tags as $key=>$tag)
<li><a href="{{url('blog/tags/'.$tag->slug)}}"><i class="badge">{{$tag->name}}</i></a></li>
@endforeach
</ul>

@endif
@if(Auth::check() && $page->allow_comments)

<hr />

<div class="row">
<div class="col-md-12">
<form method="post" enctype="multipart/form-data">
{!! csrf_field() !!}
<legend>Post comment</legend>
<div class="form-group @if ($errors->has('comment')) has-error @endif">
<label>Enter your comments here</label>
<textarea class="form-control" name="comment"></textarea>
	@if ($errors->has('comment'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('comment') }}</strong>
                                    </span>
        @endif
</div>
@if($page->allow_attachments_in_comments)
<div class="form-group @if ($errors->has('attachments')) has-error @endif">
<label>Select attachments</label>
<input class="form-control" type="file" multiple="multiple" name="attachments[]" />
	@if ($errors->has('attachments'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('attachments') }}</strong>
                                    </span>
        @endif
</div>
@endif
<div class="form-group">
<button type="submit" class="btn btn-sm btn-primary">Post</button>
</div>
</form>
</div>
</div>


<h2>Comments</h2>

        @foreach($page->comments()->get() as $comment)
        <div class="row">
            <div class="col-md-1 col-sm-6">
          @if(!empty($comment->commentUser->userInformation->profile_picture))
            <img src="{{asset('storageasset/avatars/thumbnails/'.$comment->commentUser->userInformation->profile_picture)}}" height="50"  />
            @endif
          
            </div>
             <div class="col-md-11 col-sm-6">
            <strong>{{ $comment->commentUser->userInformation->first_name}}</strong> &nbsp; {{$comment->comment}}
         
            @if(count($comment->comment_attachments))
                <br /><br />
                <ul class="list-inline">
                <li> Attachment(s): </li>
 					@foreach($comment->comment_attachments as $key=>$attachment)
                    <li><a target="new" href="{{asset('storageasset/blog/'.$attachment['original_name'])}}"><i class="fa fa-download"></i> {{$attachment['display_name']}}</a></li>
                    @endforeach
            </ul>
			
            @else
            <br /><br />
            @endif

            - <i>{{$comment->created_at->format('M d \a\t h:i a')}}</i>
            </div>
        </div>
        <br />
		@endforeach
@endif

</section>
@endsection