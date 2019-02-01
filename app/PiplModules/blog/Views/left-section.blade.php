<div class="col-md-2 col-sm-12">
<h3>Search</h3>

<form role="form" method="post" action="{{ url('/blog/search') }} ">
{!! csrf_field() !!}
<div class="row">
<div class="form-group @if(!empty(session('search-error'))) has-error @endif">

<div class="input-group">
<input type="text" name="searchText" value="{{ old('searchText',isset($keyword)?$keyword:'')}}"  class="form-control"/>
<span class="input-group-btn" id="basic-addon1"><button type="submit" class="btn btn-default">Search</button></span>
</div>
@if(!empty(session('search-error')))
<span class="help-block">
                                        <strong class="text-danger">{{ session('search-error') }}</strong>
                                    </span>
@endif
</div>
</div>
</form>

<hr />

<h3>Categories</h3>
<hr />
<ul class="tree">
@foreach ($category_tree as $category)
<a href="{{ url('/blog/categories/'.$category->slug) }}" title="Click to view posts ">{!! $category->display !!}</a>
@endforeach
</ul>
</div>