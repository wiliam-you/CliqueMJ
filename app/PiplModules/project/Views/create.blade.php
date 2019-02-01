@extends(config("piplmodules.back-view-layout-location"))

@section("meta")
<title>Create a Project</title>
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
       <a href="javascript:void(0);">Create a Project</a>

     </li>
   </ul>



   <!-- BEGIN SAMPLE FORM PORTLET-->
   <div class="portlet box blue">
     <div class="portlet-title">
      <div class="caption">
        <i class="fa fa-gift"></i> Create a Project
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
              <input class="form-control input-sm" name="title" value="{{old('title')}}" />
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
              <textarea class="form-control input-sm" name="short_description" rows="5" style="resize: none" >{{old('short_description')}}</textarea>
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
             <textarea class="form-control input-sm" name="description" id="description" >{{old('description')}}</textarea>
              @if ($errors->has('description'))
              <span class="help-block">
                <strong class="text-danger">{{ $errors->first('description') }}</strong>
              </span>
              @endif
           </div>
         </div>
     
         <div class="form-group @if ($errors->has('slug')) has-error @endif">
          <label class="col-md-3 control-label">URL<sup>*</sup></label>
          <div class="col-md-9">     
            <input class="form-control input-sm" name="slug" value="{{old('slug')}}" />
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
             <option value="{{$ls_category->id}}" @if($ls_category->id == old('category')) selected @endif>{{$ls_category->name}}</option>
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
               <input type="text" name="budget_min" class="form-control input-sm" value="{{old('budget_min')}}">
             </div>
                      @if ($errors->has('budget_min'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('budget_min') }}</strong>
                                    </span>
                            @endif
           </div>
           <div class="col-md-4 col-sm-12 @if ($errors->has('budget_max')) has-error @endif">
             <div class="input-group">
               <span class="input-group-addon">Max</span>
               <input type="text" name="budget_max" class="form-control input-sm" value="{{old('budget_max')}}">
             </div>
                 @if ($errors->has('budget_max'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('budget_max') }}</strong>
                                    </span>
                            @endif
           </div>
           <div class="col-md-4 col-sm-12 @if ($errors->has('budget_type')) has-error @endif">
             <div class="input-group">
               <span class="input-group-addon">Type <sup>*</sup></span>
               <select class="form-control input-sm" name="budget_type">
                <option @if(old('budget_type','F') === '') selected="selected" @endif value="">Choose Status</option>
                <option @if(old('budget_type','F') === 'F') selected="selected" @endif value="F">Fixed</option>
                <option @if(old('budget_type','F') === 'H') selected="selected" @endif value="H">Hourly</option>
                <option @if(old('budget_type','F') === 'D') selected="selected" @endif value="D">Daily</option>
                <option @if(old('budget_type','F') === 'W') selected="selected" @endif value="M">Monthly</option>
                <option @if(old('budget_type','F') === 'M') selected="selected" @endif value="W">Weekly</option>
                <option @if(old('budget_type','F') === 'Y') selected="selected" @endif value="Y">Yearly</option>
              </select>
            </div>
                           @if ($errors->has('budget_type'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('budget_type') }}</strong>
                                    </span>
                            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-3 control-label">Duration</label>
      <div class="col-md-9">
       <div class="row">
        <div class="input-daterange " id="datepicker">
        <div class="col-md-4 col-sm-12 @if ($errors->has('start')) has-error @endif">
         <div class="input-group">
           <span class="input-group-addon">Start Date <sup>*</sup></span>
          <input type="text" class="input-sm form-control input-sm" id='start' name="start" value="{{old('start')}}" />
         </div>
                @if($errors->has('start'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('start') }}</strong>
                                    </span>
                            @endif
       </div>
           
        <div class="col-md-4 col-sm-12 @if ($errors->has('end')) has-error @endif">
         <div class="input-group">
           <span class="input-group-addon">End Date </span>
          <input type="text" class="input-sm form-control input-sm" name="end" id="end" value="{{old('end')}}" />
         </div>
              @if ($errors->has('end'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('end') }}</strong>
                                    </span>
                            @endif
       </div>
       </div>
           
        <div class="col-md-4 col-sm-12 @if ($errors->has('currency')) has-error @endif">
         <div class="input-group">
           <span class="input-group-addon">Currency</span>
           <select name="currency" class="form-control input-sm">
             @foreach($currencies as $code=>$currency)
             <option value="{{$code}}" @if(old('currency','USD') == $code) selected="selected" @endif>{{$code}} - {{$currency}}</option>
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
          <option @if(old('status','D') === '') selected="selected" @endif value="">Choose Status</option>
          <option @if(old('status','D') === 'P') selected="selected" @endif value="P">Pending For Approval</option>
          <option @if(old('status','D') === 'A') selected="selected" @endif value="A">Approved</option>
          <option @if(old('status','D') === 'B') selected="selected" @endif value="B">Blocked</option>
          <option @if(old('status','D') === 'C') selected="selected" @endif value="C">Completed</option>
          <option @if(old('status','D') === 'D') selected="selected" @endif value="D">Draft</option>
          <option @if(old('status','D') === 'R') selected="selected" @endif value="R">Rejected</option>
          <option @if(old('status','D') === 'E') selected="selected" @endif value="E">Expired</option>
          <option @if(old('status','D') === 'T') selected="selected" @endif value="T">Trashed</option>

        </select>
      </div>
      <div class="col-md-6">
        <label class="radio-inline control-label">Is Featured?</label>
        <label class="radio-inline">
          <input type="radio" name="is_featured" value="Y"> Yes
        </label>
        <label class="radio-inline">
          <input type="radio" name="is_featured" value="N" checked="checked"> No
        </label>
      </div>
    </div>
  </div>
</div>  

<div class="form-group @if ($errors->has('tags')) has-error @endif">
  <label class="col-md-3 control-label">Tags</label>
  <div class="col-md-9">
   <input type="text" id="tags" class="form-control input-sm" name="tags" value="{{old('tags')}}"  />
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
              <input type='text' class="form-control input-sm" name="project_location" id="project_location"   value="{{old('project_location')}}">
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
                                    <option value="" selected="">--Select--</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}" @if($country->id== old('country')) selected="" @endif>{{$country->name}}</option>
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
                                <input type="text" name="zipcode" id="zipcode" class="form-control" value="{{old('zipcode')}}"/>
                            @if ($errors->has('zipcode'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('zipcode') }}</strong>
                                    </span>
                            @endif
                          </div>
                       
                      </div>

            <div class="form-group">
                          <label class="col-md-3 control-label">Location<sup>*</sup></label>
                       
                            <div class="col-md-9">     
                                <input type='text' class="form-control input-sm" name="project_location" id="project_location"   value="{{old('project_location')}}">
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
                                <input type='text' class="form-control input-sm" name="project_location" id="project_location"   value="{{old('address')}}">
                            @if ($errors->has('project_location'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('project_location') }}</strong>
                                    </span>
                            @endif
                          </div>
                       
                      </div>
@endif

   <div class="form-group @if ($errors->has('images.0')) has-error @endif">
          <label class="col-md-3 control-label">Images</label>
          <div class="col-md-9">
              <input type='file' name='images[]' class="form-control input-sm" multiple="" />
           @if ($errors->has('images.0'))
           <span class="help-block">
            <strong class="text-danger">{{ $errors->first('images.0') }}</strong>
          </span>
          @endif
        </div>
        </div>

<div class="form-group @if ($errors->has('documents.0')) has-error @endif">
  <label class="col-md-3 control-label">Documents</label>
  <div class="col-md-9">
      <input type='file' name='documents[]' class="form-control input-sm" multiple="" />
   @if ($errors->has('documents.0'))
   <span class="help-block">
    <strong class="text-danger">{{ $errors->first('documents.0') }}</strong>
  </span>
  @endif
</div>
</div>
<!--<div class="form-group">
  <a data-toggle="collapse" href="#collapsable-div">Advanced</a>
  <div id="collapsable-div" class="collapse fade">
    Collapsed Div
  </div>
</div>-->

<div class="form-group">
 <div class="col-md-12">   
  <button type="submit" id="submit" class="btn btn-primary  pull-right">Create</button>
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

            strhtml+='<div class="col-md-4 col-sm-12"><label><input type="checkbox"  name="required_category_skills[]" value="'+ele.id+'" /> '+ele.name+'</label></div>';

          });

          strhtml+='</div>';

          jQuery('#category_skills_container').html(strhtml);

        } else{
         jQuery('#category_skills_container').html('');
       }
     }

   });

  });
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
            
            @if($location_type=='googleMap')
       
            google.maps.event.addDomListener(window, 'load', initialize);
            @endif
            
            
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
             getAllStates({{old('country')}});
    
    setTimeout(function(){
      $("#state").val({{old('state')}});
      getAllCities({{old('state')}});
      
      setTimeout(function(){
      $("#city").val({{old('city')}});
    },1000)
    
    },1000)
        });
$('#start').on('changeDate', function(ev){
    $(this).datepicker('hide');
});
$('#end').on('changeDate', function(ev){
    $(this).datepicker('hide');
});
                  
</script>  


@endsection