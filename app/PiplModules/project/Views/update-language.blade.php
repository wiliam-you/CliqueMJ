@extends(config("piplmodules.back-view-layout-location"))

@section("meta")
<title>Update Project</title>
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
       <a href="{{url('admin/projects')}}">Manage Projects</a>
       <i class="fa fa-circle"></i>

     </li>
     <li>
       <a href="javascript:void(0);">Update Project</a>

     </li>
   </ul>



   <!-- BEGIN SAMPLE FORM PORTLET-->
   <div class="portlet box blue">
     <div class="portlet-title">
      <div class="caption">
        <i class="fa fa-gift"></i> Update Project
      </div>

    </div>
    <div class="portlet-body form">
      <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data">

       {!! csrf_field() !!}
       <div class="form-body">
        <div class="row">
          <div class="col-md-12">    
           <div class="form-group @if ($errors->has('title')) has-error @endif">
            <label class="col-md-3 control-label">Title<sup>*</sup></label>
            <div class="col-md-9">     
              <input class="form-control input-sm" name="title" value="{{old('title',$project_data->title)}}" />
              @if ($errors->has('title')) 
              <span class="help-block"> 
                <strong class="text-danger">{{ $errors->first('title') }}</strong> 
              </span>
              @endif
            </div>
          </div>
              
          <div class="form-group @if ($errors->has('short_description')) has-error @endif">
            <label class="col-md-3 control-label">Short Description <sup>*</sup></label>
            <div class="col-md-9">     
              <textarea class="form-control input-sm" name="short_description" rows="5" style="resize: none" >{{old('short_description',$project_data->short_description)}}</textarea>
              @if ($errors->has('short_description'))
              <span class="help-block">
                <strong class="text-danger">{{ $errors->first('short_description') }}</strong>
              </span>
              @endif
            </div>
          </div>
          <div class="form-group @if ($errors->has('description')) has-error @endif">
           <label class="col-md-3 control-label">Description</label>
           <div class="col-md-9">   
             <textarea class="form-control input-sm" name="description" id="description" >{{old('description',$project_data->short_description)}}</textarea>
              @if ($errors->has('description'))
              <span class="help-block">
                <strong class="text-danger">{{ $errors->first('description') }}</strong>
              </span>
              @endif
           </div>
         </div>


  

    

 



      





<div class="form-group">
 <div class="col-md-12">   
  <button type="submit" id="submit" class="btn btn-primary  pull-right">Update</button>
</div>
</div>

</div> 

</div>
</div>
</div>

</div>

</form>
</div>
</div>
</div>
</div>

<script src="{{url('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript" src="{{url('/public/vendor/datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{url('/public/vendor/datepicker/css/bootstrap-datepicker.min.css')}}">
<script>

  CKEDITOR.replace( 'description' );
</script>  
@endsection