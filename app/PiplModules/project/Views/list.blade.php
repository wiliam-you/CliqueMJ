@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Manage Projects</title>

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
       <a href="javascript:void(0)">Manage Projects</a>

     </li>
   </ul>
   @if (session('status'))
   <div class="alert alert-success">
    {{ session('status') }}
    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
  </div>
  @endif
  <div class="row">
    <div class="col-md-12">
     <!-- BEGIN EXAMPLE TABLE PORTLET-->
     <div class="portlet box grey-cascade">
      <div class="portlet-title">
       <div class="caption">
       <i class="fa fa-list"></i>Manage Projects
      </div>
      <div class="tools">
        <a class="collapse" href="javascript:;" data-original-title="" title="">
        </a>
        <a class="config" data-toggle="modal" href="#portlet-config" data-original-title="" title="">
        </a>
        <a class="reload" href="javascript:;" data-original-title="" title="">
        </a>
        <a class="remove" href="javascript:;" data-original-title="" title="">
        </a>
      </div>
    </div>
    <div class="portlet-body">
     <div class="table-toolbar">
      <div class="row">
       <div class="col-md-6">
        <div class="btn-group">
         <a href="{{url('/admin/projects/create')}}" id="sample_editable_1_new" class="btn green">
           Add New <i class="fa fa-plus"></i>
         </a>
       </div>
     </div>

   </div>
 </div>
 <table class="table table-striped table-bordered table-hover" id="list_projects">
   <thead>
     <tr>
      <th class="table-checkbox">
       <input type="checkbox" class="group-checkable" id="select_all_delete" data-set="#list_projects .checkboxes"/>
     </th>
     
     <th>Title</th>
     <th>Short Description</th>
     <th>Category</th>
     <th>Created Date</th>
     <th>Language</th>
    
     <!--<th>View Comments</th>-->
     <th>Action</th>
   </tr>
 </thead>
</table>
<input type="button" onclick='javascript:deleteAll("{{url('/admin/projects/delete-selected')}}")' name="delete" id="delete" value="Delete Selected" class="btn btn-danger">
</div>
</div>



<!-- END PAGE CONTENT INNER -->
</div>
</div>
<!-- END CONTENT -->
</div>
<script>
  $(function() {
    $('#list_projects').DataTable({
      processing: true,
      serverSide: true,
      ajax: {"url":'{{url("/admin/projects-data")}}',"complete":afterRequestComplete},
      columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
      columns: [
      {data:   "id",
      render: function ( data, type, row ) {

        if ( type === 'display' ) {

          return '<div class="checker"> <span><input class="checkboxes" type="checkbox"  name="delete'+row.id+'" value="'+row.id+'"></span></div>';
        }
        return data;
      },
      "orderable": false,

    },
    
    { data: 'title', name: 'title'},
    { data: 'short_description', name: 'short_description'},
    { data: 'category', name: 'category'},
       { data: 'created_at', name: 'created_at' },
    {data: 'Language', name: 'Language'},

{data:   "Delete",
render: function ( data, type, row ) {

  if ( type === 'display' ) {

     var str= '<a  class="btn btn-sm btn-info" style="margin-top:5px;" href="{{url("/admin/project-images")}}/'+row.id+'">Manage Images</a></br> ';
     str+= '<a  class="btn btn-sm btn-success" style="margin-top:5px;" href="{{url("/admin/project-documents")}}/'+row.id+'">Manage Documents</a></br>';
     str+='<a  class="btn btn-sm btn-primary" style="margin-top:5px;" href="{{url("/admin/projects/update/")}}/'+row.id+'">Update</a></br>';
    str+= '<form id="project_delete_'+row.id+'"  method="post" action="{{url("/admin/project/delete")}}/'+row.id+'">{{ method_field("DELETE") }} {!! csrf_field() !!}<button style="margin-top:5px;" onclick="confirmDelete('+row.id+');" class="btn btn-sm btn-danger" type="button">Delete</button></form>';
    return str;
  }
  return data;
},
"orderable": false,
name: 'Delete'

}


]
});
  });
  function confirmDelete(id)
  {
    if(confirm("Do you really want to delete this project?"))
    {
      $("#project_delete_"+id).submit();
    }
    return false;
  }
</script>
@endsection
