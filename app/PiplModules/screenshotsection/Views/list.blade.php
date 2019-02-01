@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Add Screen Shots</title>
@endsection

@section('content')
<div class="page-content-wrapper">
		<div class="page-content">
                    <!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="{{url('admin/dashboard')}}">Dashboard</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="javascript:void(0);">Add Screen Shots</a>
					
				</li>
                        </ul>

  
    
      <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">
             <div class="portlet-title">
                        <div class="caption">
                                <i class="fa fa-gift"></i> Add Screen Shots
                        </div>

             </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                </div>
            @endif
             <div class="portlet-body form">

                 <div class="container">
                    <form action="{{ url('admin/screen-shot-section/list') }}" method="post" enctype="multipart/form-data" class="mtop">

                         {{ csrf_field() }}

                         <input type="file" id="uploadFile" name="uploadFile[]" multiple/>
                        @if ($errors->has('uploadFile'))
                            <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('uploadFile') }}</strong>
                              </span>
                        @endif

                         <input type="submit" class="btn btn-success" name='submitImage' value="Upload Image"/>

                     </form>


                     <br/>


                     <div id="image_preview" class="row">
                         @if(count($images)>0)
                             @foreach($images as $image)
                                 <div class="col-md-3">
                                <img src="{{url('/storage/app/public/screen-shot/'.$image->image)}}">
                                     <form id="delete_screen_shot_{{$image->id}}" action="{{url('admin/remove/screen-shot/'.$image->id)}}">
                                         {!! csrf_field() !!}
                                         <a href="javascript:void(0)"><i class="fa fa-times" onclick="confirmDelete({{$image->id}})"></i></a>

                                     </form>
                                 </div>

                                 @endforeach
                             @endif

                     </div>



                 </div>
 </div>
    </div>
    </div>
    </div>
    @endsection
@section('footer')
    <script>
        function confirmDelete(id)
        {
            if(!$('#delete'+id).prop('checked')){
                alert('Please select a record');
                return false
            }
            if(confirm("Do you really want to delete this user?"))
            {

                $("#delete_screen_shot_"+id).submit();
            }
            return false;
        }
    </script>
@endsection