@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Create Feedback</title>

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
					<a href="{{url('admin/feedback/all/'.$id)}}">Manage Feedback</a>
          <i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="javascript:void(0);">Create Feedback</a>
					
				</li>
                        </ul>

  
    
      <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">
             <div class="portlet-title">
                        <div class="caption">
                                <i class="fa fa-gift"></i> Create A Feedback
                        </div>

             </div>
             <div class="portlet-body form">
                 <form class="form-horizontal" enctype="multipart/form-data" role="form" action="" method="post" >
            
                 {!! csrf_field() !!}
                 <div class="form-body">
                   <div class="row">
                        <div class="col-md-12">    
                        <div class="col-md-8">  
                         <div class="form-group @if ($errors->has('feedback')) has-error @endif">
                          <label class="col-md-6 control-label">Feedback<sup>*</sup></label>
                       
                            <div class="col-md-6">     
                                <textarea name="feedback" class="form-control" id="feedback">{{old('feedback')}}</textarea>
                            @if ($errors->has('feedback'))
                              <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('feedback') }}</strong>
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