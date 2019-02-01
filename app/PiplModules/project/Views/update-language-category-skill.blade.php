@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Update Category Skill</title>

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
                <a href="{{url('admin/project-categories')}}">Manage Categories</a>
                <i class="fa fa-circle"></i>

            </li>
              <li>
          <a href="{{url('admin/project-category-skills/'.$category_id)}}">Manage Category Skills</a>
                                        <i class="fa fa-circle"></i>
          
        </li>
            <li>
                <a href="javascript:void(0);">Update Category SKill</a>

            </li>
        </ul>



        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Update Category
                </div>

            </div>
            <div class="portlet-body form">
                <form class="form-horizontal" role="form" action="" method="post" >

                    {!! csrf_field() !!}
                    <div class="form-body">
                   <div class="row">
                        <div class="col-md-12">    
                        <div class="col-md-8">  
                         <div class="form-group">
                          <label class="col-md-6 control-label">Name<sup>*</sup></label>
                       
                            <div class="col-md-6">     
                             <input class="form-control" name="name" value="{{old('name',$skill->name)}}" />
                            @if ($errors->has('name'))
                              <span class="help-block">
                                  <strong class="text-danger">{{ $errors->first('name') }}</strong>
                              </span>
                              @endif
                          </div>
                       
                      </div>
                        <div class="form-group">
                          <label class="col-md-6 control-label">Description</label>
                       
                            <div class="col-md-6">     
                                <textarea name="description" class="form-control" style="resize: none;">{{old('description',$skill->description)}}</textarea>
                          </div>
                       
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