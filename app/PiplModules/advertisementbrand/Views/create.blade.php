@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Create Advertisement Brand</title>

@endsection

@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="page-content-wrapper">
		<div class="page-content">
                    <!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="{{url('admin/dashboard')}}">Dashboard</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="{{url('admin/advertisementbrand/list')}}">Manage Advertisement Brand</a>
                                        <i class="fa fa-circle"></i>
					
				</li>
				<li>
					<a href="javascript:void(0);">Create Advertisement Brand</a>
					
				</li>
                        </ul>

  
    
      <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">
             <div class="portlet-title">
                        <div class="caption">
                                <i class="fa fa-gift"></i> Create A Advertisement Brand
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    var dateFormat = "mm/dd/yy",
        from = $( "#start_date" )
            .datepicker({
                defaultDate: "+1w",
                changeMonth: true,
            })
            .on( "change", function() {
                to.datepicker( "option", "minDate", getDate( this ) );
            }),
        to = $( "#end_date" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
        })
            .on( "change", function() {
                from.datepicker( "option", "maxDate", getDate( this ) );
            });

    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }

        return date;
    }
</script>
 @endsection