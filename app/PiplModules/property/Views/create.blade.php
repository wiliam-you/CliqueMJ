@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Create Property</title>

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
					<a href="{{url('admin/property/list')}}">Manage Property</a>
                                        <i class="fa fa-circle"></i>
					
				</li>
				<li>
					<a href="javascript:void(0);">Create Property</a>
					
				</li>
                        </ul>

  
    
      <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">
             <div class="portlet-title">
                        <div class="caption">
                                <i class="fa fa-gift"></i> Create A Property
                        </div>

             </div>
             <div class="portlet-body form">
                 <form class="form-horizontal" enctype="multipart/form-data" role="form" action="" method="post" >
            
                 {!! csrf_field() !!}
                 <div class="form-body">
                   <div class="row">
                        <div class="col-md-12">    
                        <div class="col-md-8">  
                         <div class="form-group @if ($errors->has('name')) has-error @endif">
                          <label class="col-md-6 control-label">Name<sup>*</sup></label>
                       
                            <div class="col-md-6">     
                            <input name="name" type="text" class="form-control" id="name" value="{{old('name')}}">
                            @if ($errors->has('name'))
                              <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('name') }}</strong>
                              </span>
                              @endif
                          </div>
                       
                        </div>
                       <div class="form-group @if ($errors->has('publish_status')) has-error @endif">
                          <label class="col-md-6 control-label">Publish Status <sup></sup></label>
                           <div class="col-md-6">     
                             <div class="radio-list">
                                <label class="radio-inline">
                                    <input  type="radio" name="publish_status" id="unpublish" value="0" @if(old("publish_status") === "0") checked @endif> Unpublished </label>
                                <label class="radio-inline">
                                    <input checked type="radio" name="publish_status" id="publish" value="1" @if(old("publish_status") === "1") checked @endif> Published </label>

                             </div>
                            @if ($errors->has('publish_status'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('publish_status') }}</strong>
                                </span>
                            @endif
        
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
    
            </form>
        </div>
    </div>
    </div>
    </div>
        
 @endsection