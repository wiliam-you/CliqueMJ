@extends('layouts.vendor')

@section("meta")

<title>Update Product</title>

@endsection

@section('content')
<div class="page-content-wrapper">
		<div class="page-content">
                    <!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="{{url('dispensary/dashboard')}}">Dashboard</a>
				</li>
				<li>
					<a href="{{url('dispencery/product/list/')}}">Manage Product</a>
                </li>
				<li>
					<a href="javascript:void(0);">Update Product</a>
					
				</li>
                        </ul>

  
    
      <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="">
            <div class="x_title"><h2>Update Product</h2>
                <div class="clearfix"></div>
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
                            <input name="name" type="text" class="form-control" id="name" value="{{old('name',$product->name)}}">
                            @if ($errors->has('name'))
                              <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('name') }}</strong>
                              </span>
                              @endif
                          </div>
                       
                        </div>
                        <div class="form-group @if ($errors->has('property_id')) has-error @endif">
                          <label class="col-md-6 control-label">Property<sup>*</sup></label>
                       
                            <div class="col-md-6">     
                            <select name="property_id" type="text" class="form-control" id="property">
                            @foreach($properties as $property)
                              <option value="{{$property->id}}" @if(old('property',$product->property_id)==$property->id) selected @endif>{{$property->name}}</option>
                            @endforeach
                            </select>
                            @if ($errors->has('property_id'))
                              <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('property_id') }}</strong>
                              </span>
                              @endif
                          </div>
                       
                        </div>
                            <div class="form-group @if ($errors->has('quantity')) has-error @endif">
                                <label class="col-md-6 control-label">Add Size<sup>*</sup></label>

                                <div class="col-md-6">
                                    <table class="table" id="size">
                                        <tr><th>Size (grams)</th><th>Price</th><th>Quantity</th></tr>
                                        @if(count($product->productSizes)>0)
                                            @foreach($product->productSizes as $index=>$size)
                                                <tr><td><input readonly type="text" name="size[]" class="form-control" value="{{$size->size}}"></td><td><input type="text" name="price[]" class="form-control" value="{{$size->price}}"></td><td><input type="text" name="quantity[]" class="form-control" value="{{$size->quantity}}"></td></tr>
                                            @endforeach
                                        @endif
                                    </table>
                                    <input type="hidden" value="{{$err=0}}">
                                    @for($i=0;$i<5;$i++)
                                        @if ($errors->has('size.'.$i) || $errors->has('quantity.'.$i) || $errors->has('price.'.$i))
                                            <input type="hidden" value="{{$err=1}}">
                                        @endif
                                    @endfor
                                    @if ($err==1)
                                        <span class="help-block">
                                              <strong class="text-danger">Please fill all fields</strong>
                                            </span>
                                    @endif

                                </div>

                            </div>
                        <div class="form-group @if ($errors->has('description')) has-error @endif">
                          <label class="col-md-6 control-label">Description<sup>*</sup></label>
                       
                            <div class="col-md-6">     
                            <textarea name="description" class="form-control" id="description">{{old('description',$product->description)}}</textarea>
                            @if ($errors->has('description'))
                              <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('description') }}</strong>
                              </span>
                              @endif
                          </div>
                       
                        </div>
                        <div class="form-group @if ($errors->has('photo')) has-error @endif">
                          <label class="col-md-6 control-label">Photo</label>
                           <div class="col-md-6">     
                            <input type="file" class="form-control" name="photo">
                               @if ($errors->has('photo'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('photo') }}</strong>
                                </span>
                               @endif
        
                          </div>
                       
                        </div>
                            <div class="form-group">
                                <label class="col-md-6 control-label"></label>
                                <div class="col-md-6">
                                    <img width="80px" src="{{url("storage/app/public/product/".$product->image)}}">
                                    @if ($errors->has('photo'))
                                        <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('photo') }}</strong>
                                </span>
                                    @endif

                                </div>

                            </div>
                       <div class="form-group @if ($errors->has('publish_status')) has-error @endif">
                          <label class="col-md-6 control-label">Publish Status</label>
                           <div class="col-md-6">     
                             <div class="radio-list">
                                            <label class="radio-inline">
                                            <input type="radio" name="publish_status" id="unpublish" value="0" @if(old("publish_status",$product->status) === "0") checked @endif> Unpublished </label>
                                            <label class="radio-inline">
                                            <input type="radio" name="publish_status" id="publish" value="1" @if(old("publish_status",$product->status) === "1") checked @endif> Published </label>
                                            
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
<script>
    $(document).ready(function(){
        checkTable();
    })
    function checkTable() {
        var tr = $('#size tbody').children().length;
        if(tr == 1)
        {
            $('#size').append('<tr><td><input type="text" name="size[]" class="form-control"></td><td><input type="text" name="price[]" class="form-control"></td><td><input type="text" name="quantity[]" class="form-control"></td></tr>');
        }
    }
    var cnt = 1;
    function addSize() {
        $('#size').append('<tr id="'+cnt+'"><td><input type="text" name="size[]" class="form-control"></td><td><input type="text" name="price[]" class="form-control"></td><td><input type="text" name="quantity[]" class="form-control"></td><td><button type="button" class="btn btn-danger" onclick="deleteSize('+cnt+')"><i class="fa fa-trash-o"></i></button></td></tr>');
        cnt++;
    }
    function deleteSize(id) {
        $('#'+id).remove();
        checkTable();
    }
</script>
 @endsection