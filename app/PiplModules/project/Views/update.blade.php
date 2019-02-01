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
           <label class="col-md-3 control-label">Description<sup>*</sup></label>
           <div class="col-md-9">   
             <textarea class="form-control input-sm" name="description" id="description" >{{old('description',$project_data->short_description)}}</textarea>
              @if ($errors->has('description'))
              <span class="help-block">
                <strong class="text-danger">{{ $errors->first('description') }}</strong>
              </span>
              @endif
           </div>
         </div>
         <div class="form-group @if ($errors->has('url')) has-error @endif">
          <label class="col-md-3 control-label">URL<sup>*</sup></label>
          <div class="col-md-9">     
            <input class="form-control input-sm" name="slug" value="{{old('slug',$project_data->slug)}}" />
            @if ($errors->has('slug')) 
            <span class="help-block"> 
              <strong class="text-danger">{{ $errors->first('slug') }}</strong> 
            </span>
            @endif
          </div>
        </div>         
        @if(count($tree)>0 && empty($locale))
        <div class="form-group @if ($errors->has('category')) has-error @endif">
          <label for="category" class="col-md-3 control-label">Select Category </label>
          <div class="col-md-9">     
            <select name="category" id="lst_category" class="form-control input-sm">
             <option value="0">No Category</option>
             @foreach($tree as $ls_category)
             <option value="{{$ls_category->id}}" @if($project_data->project_category_id== $ls_category->id) selected='' @endif>{{$ls_category->display}}</option>
             @endforeach
           </select>
           @if ($errors->has('category'))
           <span class="help-block">
            <strong class="text-danger">{{ $errors->first('category') }}</strong>
          </span>
          @endif
        </div>
      </div>
      @endif

      @if(count($tree)>0 && empty($locale))
      <div class="form-group" id="category_skills">
        <label for="skills" class="col-md-3 control-label">Select Required Skills</label>
        <div class="col-md-9" id="category_skills_container"></div>
      </div>
      @endif

      <div class="form-group">
        <label class="col-md-3 control-label">Budget</label>
        <div class="col-md-9">
          <div class="row">
            <div class="col-md-4 col-sm-12 @if ($errors->has('budget_min')) has-error @endif">
             <div class="input-group">
               <span class="input-group-addon">Min <sup>*</sup></span>
               <input type="text" name="budget_min" class="form-control input-sm" value="{{old('budget_min',$project_data->budget_min)}}">
             </div>
           </div>
           <div class="col-md-4 col-sm-12 @if ($errors->has('budget_max')) has-error @endif">
             <div class="input-group">
               <span class="input-group-addon">Max</span>
               <input type="text" name="budget_max" class="form-control input-sm" value="{{old('budget_max',$project_data->budget_max)}}">
             </div>
           </div>
           <div class="col-md-4 col-sm-12 @if ($errors->has('budget_type')) has-error @endif">
             <div class="input-group">
               <span class="input-group-addon">Type <sup>*</sup></span>
               <select class="form-control input-sm" name="budget_type">
                <option @if(old('budget_type',$project_data->budget_type) === '') selected="selected" @endif value="">Choose Status</option>
                <option @if(old('budget_type',$project_data->budget_type) === 'F') selected="selected" @endif value="F">Fixed</option>
                <option @if(old('budget_type',$project_data->budget_type) === 'H') selected="selected" @endif value="H">Hourly</option>
                <option @if(old('budget_type',$project_data->budget_type) === 'D') selected="selected" @endif value="D">Daily</option>
                <option @if(old('budget_type',$project_data->budget_type) === 'W') selected="selected" @endif value="M">Monthly</option>
                <option @if(old('budget_type',$project_data->budget_type) === 'M') selected="selected" @endif value="W">Weekly</option>
                <option @if(old('budget_type',$project_data->budget_type) === 'Y') selected="selected" @endif value="Y">Yearly</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-3 control-label">Duration</label>
      <div class="col-md-9">
       <div class="row">
         <div class="col-md-8 col-sm-12">
           <div class="input-daterange input-group" id="datepicker">
            <span class="input-group-addon">Start Date <sup>*</sup></span>
            <input type="text" class="input-sm form-control input-sm @if ($errors->has('start')) has-error @endif" name="start" id="start" value="{{old('start',$project_data->start_date)}}" />
            <span class="input-group-addon">End Date</span>
            <input type="text" class="input-sm form-control input-sm @if ($errors->has('end')) has-error @endif" name="end" id="end" value="{{old('end',$project_data->end_date)}}" />
          </div>
        </div>
        <div class="col-md-4 col-sm-12 @if ($errors->has('currency')) has-error @endif">
         <div class="input-group">
           <span class="input-group-addon">Currency</span>
           <select name="currency" class="form-control input-sm">
             @foreach($currencies as $code=>$currency)
             <option value="{{$code}}" @if(old('currency',$project_data->currency) == $code) selected="selected" @endif>{{$code}} - {{$currency}}</option>
             @endforeach
           </select>
         </div>
       </div>
     </div>
   </div>
 </div>

 <div class="form-group @if ($errors->has('status')) has-error @endif">
  <label class="col-md-3 control-label">Project Status<sup>*</sup></label>
  <div class="col-md-9">
    <div class="row">
      <div class="col-md-6">
        <select class="form-control input-sm" name="status">
          <option @if(old('status',$project_data->status) === '') selected="selected" @endif value="">Choose Status</option>
          <option @if(old('status',$project_data->status) === 'P') selected="selected" @endif value="P">Pending For Approval</option>
          <option @if(old('status',$project_data->status) === 'A') selected="selected" @endif value="A">Approved</option>
          <option @if(old('status',$project_data->status) === 'B') selected="selected" @endif value="B">Blocked</option>
          <option @if(old('status',$project_data->status) === 'C') selected="selected" @endif value="C">Completed</option>
          <option @if(old('status',$project_data->status) === 'D') selected="selected" @endif value="D">Draft</option>
          <option @if(old('status',$project_data->status) === 'R') selected="selected" @endif value="R">Rejected</option>
          <option @if(old('status',$project_data->status) === 'E') selected="selected" @endif value="E">Expired</option>
          <option @if(old('status',$project_data->status) === 'T') selected="selected" @endif value="T">Trashed</option>

        </select>
      </div>
      <div class="col-md-6">
        <label class="radio-inline control-label">Is Featured?</label>
        <label class="radio-inline">
          <input type="radio" name="is_featured" value="Y" @if($project_data->is_featured=="Y") checked="checked" @endif> Yes
        </label>
        <label class="radio-inline">
          <input type="radio" name="is_featured" value="N" @if($project_data->is_featured=="N") checked="checked" @endif> No
        </label>
      </div>
    </div>
  </div>
</div>  

<div class="form-group @if ($errors->has('tags')) has-error @endif">
  <label class="col-md-3 control-label">Tags</label>
  <div class="col-md-9">
   <input type="text" id="tags" class="form-control input-sm" name="tags" value="{{old('tags',$project_data->tags)}}"  />
   @if ($errors->has('tags'))
   <span class="help-block">
    <strong class="text-danger">{{ $errors->first('tags') }}</strong>
  </span>
  @endif
</div>
</div>
      


@if($location_type=='single')
<div class="form-group @if ($errors->has('project_location')) has-error @endif">
  <label class="col-md-3 control-label">Project Location<sup>*</sup></label>
     <div class="col-md-9">     
              <textarea class="form-control input-sm" name="project_location" rows="5" style="resize: none" >{{old('project_location',$project_data->project_location)}}</textarea>
              @if ($errors->has('project_location'))
              <span class="help-block">
                <strong class="text-danger">{{ $errors->first('project_location') }}</strong>
              </span>
              @endif
     </div>
</div>
@elseif($location_type=='group')

 <div class="form-group">
                          <label class="col-md-3 control-label">Select Country<sup>*</sup></label>
                            <div class="col-md-9">     
                                <select name="country" id="country" onchange="getAllStates(this.value)" class="form-control">
                                    <option value="" >--Select--</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}"  @if($country->id== $project_data->country_id) selected="" @endif>{{$country->name}}</option>
                            @endforeach
                            </select>
                            @if ($errors->has('country'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('country') }}</strong>
                                    </span>
                            @endif
                          </div>
</div>

            <div class="form-group">
                          <label class="col-md-3 control-label">Select State<sup>*</sup></label>
                       
                            <div class="col-md-9">     
                            <select name="state" id="state" class="form-control" onchange="getAllCities(this.value)" >
                               <option value="">--Select--</option>
                            </select>
                            @if ($errors->has('state'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('state') }}</strong>
                                    </span>
                            @endif
                          </div>
                       
                      </div>
            <div class="form-group">
                          <label class="col-md-3 control-label">Select City<sup>*</sup></label>
                       
                            <div class="col-md-9">     
                            <select name="city" id="city" class="form-control">
                               <option value="">--Select--</option>
                            </select>
                            @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('city') }}</strong>
                                    </span>
                            @endif
                          </div>
                       
                      </div>

            <div class="form-group">
                          <label class="col-md-3 control-label">Zip Code<sup>*</sup></label>
                       
                            <div class="col-md-9">     
                                <input type="text" name="zipcode" id="zipcode" class="form-control" value="{{old('zipcode',$project_data->zipcode)}}"/>
                            @if ($errors->has('zipcode'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('zipcode') }}</strong>
                                    </span>
                            @endif
                          </div>
                       
                      </div>

            <div class="form-group">
                          <label class="col-md-3 control-label">Address<sup>*</sup></label>
                       
                            <div class="col-md-9">     
                            <textarea class="form-control input-sm" name="project_location" rows="5" style="resize: none" >{{old('address',$project_data->project_location)}}</textarea>
                            @if ($errors->has('project_location'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('project_location') }}</strong>
                                    </span>
                            @endif
                          </div>
                       
                      </div>

@elseif($location_type=='googleMap')

<div class="form-group">
    <label class="col-md-3 control-label">Address<sup>*</sup></label>
    <div class="col-md-9">     
        <input type='text' class="form-control input-sm" name="project_location" id="project_location"   value="{{old('address',$project_data->project_location)}}">
        @if ($errors->has('project_location'))
        <span class="help-block">
            <strong class="text-danger">{{ $errors->first('project_location') }}</strong>
        </span>
        @endif
    </div>

</div>
@endif


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
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyBHVJXtjmQBtOurwdptGS9zlA3t-AAZmtQ"></script>
<script>
  var categorySkills = <?php echo json_encode($skills);?>;
  
  var projectSkills = {!!json_encode($project_skills)!!};
    var pskills = [];
        jQuery(projectSkills).each(function(indx, ele){
          
            pskills.push(ele);
          
        });
  
  
  var selected_category_db={{$project_data->project_category_id}};
  jQuery(document).ready(function(){

    jQuery(".input-daterange").datepicker({
      startDate:"0d",
      format:"yyyy-mm-dd"
    });

    jQuery("#lst_category").bind("change",function() {


      var selCat = jQuery("#lst_category").val();

      if(selCat != ''){
        var skills = [];
        jQuery(categorySkills).each(function(indx, ele){
          if(ele.project_category_id == selCat){
            skills.push(ele);
          }
        });

        if(skills.length){
          var strhtml = '<div class="row">';
          
          jQuery(skills).each(function(indx,ele){

            if(indx>0 && (indx%3)<1){
              strhtml+='</div><div class="row">';
            }

            strhtml+='<div class="col-md-4 col-sm-12">';
            if(selCat==selected_category_db && ($.inArray(ele.id+"", pskills)!=-1))
            {
              
            strhtml+='<label>'; 
            strhtml+='<input type="checkbox" checked name="required_category_skills[]" value="'+ele.id+'" />';
            strhtml+=''+ele.name+'</label>';
            }else{
              
            strhtml+='<label>'; 
            strhtml+='<input type="checkbox" name="required_category_skills[]" value="'+ele.id+'" />';
            strhtml+=''+ele.name+'</label>';  
            }
          strhtml+='</div>';
            

          });
          strhtml+='</div>';
          jQuery('#category_skills_container').html(strhtml);

        } else{
         jQuery('#category_skills_container').html('');
       }
     }

   });

  });
  
     function getAllStates(country_id)
        {
          
            if(country_id!='' && country_id!=0)
            {
                $.ajax({
                   url:"{{url('/admin/states/getAllStates')}}/"+country_id,
                   method:'get',
                   success:function(data)
                   {

                        $("#state").html(data);

                   }

                });
            }
        }
        
        function getAllCities(state_id)
        {
            if(state_id!='' && state_id!=0)
            {
                $.ajax({
                   url:"{{url('/admin/cities/getAllCities')}}/"+state_id,
                   method:'get',
                   success:function(data)
                   {
                        $("#city").html(data);
                   }

                });
            }
        }
  
  $(window).ready(function(){
    jQuery("#lst_category").change();
    getAllStates({{$project_data->country_id}});
    
    setTimeout(function(){
      $("#state").val({{$project_data->state_id}});
      getAllCities({{$project_data->state_id}});
      
      setTimeout(function(){
      $("#city").val({{$project_data->city_id}});
    },1000)
    
    },1000)
  })
  CKEDITOR.replace( 'description' );
  
  
   var   autocomplete;
   function initialize() {
                var input = document.getElementById('project_location');
                var options = {};
								  autocomplete =  new google.maps.places.Autocomplete(input, options);
								 autocomplete.addListener('place_changed', function() {
 
          			var place = autocomplete.getPlace();
								var latitude = place.geometry.location.lat();
								var longitude = place.geometry.location.lng();
						
          });
            }
            google.maps.event.addDomListener(window, 'load', initialize);
            
            
            $('#start').on('changeDate', function(ev){
    $(this).datepicker('hide');
});
$('#end').on('changeDate', function(ev){
    $(this).datepicker('hide');
});
</script>  

@endsection