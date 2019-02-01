@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Create {{ucwords($project_title)}} Image</title>

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
          <a href="{{url('admin/project-images/'.$project_id)}}">Manage {{ucwords($project_title)}} Images</a>
                                        <i class="fa fa-circle"></i>
          
        </li>
				<li>
					<a href="javascript:void(0);">Create {{ucwords($project_title)}} Image</a>
					
				</li>
                        </ul>

  
    
      <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">
             <div class="portlet-title">
                        <div class="caption">
                                <i class="fa fa-gift"></i> Create {{ucwords($project_title)}} Image
                        </div>

             </div>
             <div class="portlet-body form">
                 <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" >
            
                 {!! csrf_field() !!}
                 <div class="form-body">
                   <div class="row">
                        <div class="col-md-12">    
                        <div class="col-md-8">  
                         <div class="form-group">
                          <label class="col-md-6 control-label">Image<sup>*</sup></label>
                       
                            <div class="col-md-6">     
                            <input name="image" type="file" class="form-control" id="image" value="">
                            @if ($errors->has('image'))
                              <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('image') }}</strong>
                              </span>
                              @endif
                          </div>
                       
                      </div>
                    <div class="form-group">
                          <label class="col-md-6 control-label">Description</label>
                       
                            <div class="col-md-6">     
                            <textarea class="form-control" style="resize: none;" name="description">{{old('description')}}</textarea>
                           
                          </div>
                       
                      </div>
                            
                    
                       
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
        <style>
            .submit-btn{
                padding: 10px 0px 0px 18px;
            }
        </style>
 @endsection