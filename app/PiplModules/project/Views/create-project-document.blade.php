@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Create {{ucwords($project_title)}} Document</title>

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
          <a href="{{url('admin/project-documents/'.$project_id)}}">Manage {{ucwords($project_title)}} Documents</a>
                                        <i class="fa fa-circle"></i>
          
        </li>
				<li>
					<a href="javascript:void(0);">{{ucwords($project_title)}} Document</a>
					
				</li>
                        </ul>

  
    
      <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">
             <div class="portlet-title">
                        <div class="caption">
                                <i class="fa fa-gift"></i> Create {{ucwords($project_title)}} Document
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
                          <label class="col-md-6 control-label">Document<sup>*</sup></label>
                       
                            <div class="col-md-6">     
                            <input name="document" type="file" class="form-control" id="document" value="">
                            @if ($errors->has('document'))
                              <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('document') }}</strong>
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