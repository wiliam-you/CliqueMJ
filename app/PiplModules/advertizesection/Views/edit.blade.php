@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Update Advertise Section</title>

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
					<a href="{{url('admin/advertise-section/list')}}">Manage Advertise Section</a>
                                        <i class="fa fa-circle"></i>
					
				</li>
				<li>
					<a href="javascript:void(0);">Update Advertise Section</a>
					
				</li>
                        </ul>

  
    
      <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">
             <div class="portlet-title">
                        <div class="caption">
                                <i class="fa fa-gift"></i> Update A Advertise Section
                        </div>

             </div>
             <div class="portlet-body form">
                 <form class="form-horizontal" enctype="multipart/form-data" role="form" action="" method="post" >
            
                 {!! csrf_field() !!}
                 <div class="form-body">
                   <div class="row">
                        <div class="col-md-12">    
                        <div class="col-md-8">  
                         <div class="form-group @if ($errors->has('title')) has-error @endif">
                          <label class="col-md-6 control-label">Title<sup>*</sup></label>
                       
                            <div class="col-md-6">     
                            <input name="title" type="text" class="form-control" id="title" value="{{old('title',$advertize->title)}}">
                            @if ($errors->has('title'))
                              <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('title') }}</strong>
                              </span>
                              @endif
                          </div>
                       
                        </div>
                            <div class="form-group @if ($errors->has('description')) has-error @endif">
                                <label class="col-md-6 control-label">Description<sup>*</sup></label>

                                <div class="col-md-6">
                                    <textarea name="description" class="form-control">{{old('description',$advertize->description)}}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('description') }}</strong>
                              </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group @if ($errors->has('photo')) has-error @endif">
                                <label class="col-md-6 control-label">Image<sup>*</sup></label>

                                <div class="col-md-6">
                                    <input name="photo" type="file" class="form-control">
                                    @if ($errors->has('photo'))
                                        <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('photo') }}</strong>
                              </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group @if ($errors->has('photo')) has-error @endif">
                                <label class="col-md-6 control-label"></label>

                                <div class="col-md-6">
                                    <img src="{{url('/storage/app/public/advertize-section/'.$advertize->image)}}">
                                    @if ($errors->has('photo'))
                                        <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('photo') }}</strong>
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
    
            </form>
        </div>
    </div>
    </div>
    </div>
        
 @endsection