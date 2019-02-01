@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Manage {{ucwords($project_title)}} Documents</title>

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
       <a href="{{url('admin/projects')}}">Manage Projects</a> <i class="fa fa-circle"></i>
     </li>
     <li>
       <a href="javascript:;">Manage {{ucwords($project_title)}} Documents</a>
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
        <i class="fa fa-list"></i>Manage {{ucwords($project_title)}} Documents
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
         <a href="{{url('/admin/create-project-documents/'.$project_id)}}" id="sample_editable_1_new" class="btn green">
           Add New <i class="fa fa-plus"></i>
         </a>
       </div>
     </div>

   </div>
 </div>
 <table class="table table-striped table-bordered table-hover" id="list_categories">
   <thead>
     <tr>
      <th class="table-checkbox">
       <input type="checkbox" class="group-checkable" id="select_all_delete" data-set="#list_categories .checkboxes"/>
     </th>
     
     <th>Documents</th>
     
     <th>Created Date</th>
     <th>Updated Date</th>
     <th>Update</th>
     <th>Delete</th>
   </tr>
 </thead>
</table>
<input type="button" onclick='javascript:deleteAll("{{url('/admin/delete-selected-project-documents/')}}")' name="delete" id="delete" value="Delete Selected" class="btn btn-danger">
</div>
</div>



<!-- END PAGE CONTENT INNER -->
</div>
</div>
<!-- END CONTENT -->
</div>
<script>
  $(function() {
    $('#list_categories').DataTable({
      processing: true,
      serverSide: true,
      ajax: {"url":'{{url("/admin/project-documents-data/".$project_id)}}',"complete":afterRequestComplete},
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
    
    { data: 'path',
      render: function ( data, type, row ) 
    {

      if ( type === 'display' ) {

        return '<a href="{{url('/')}}/admin/download-document/'+row.path+'" >'+row.name+'</a>';
      }
      return data;
    },
    "orderable": false,
    name: 'path'},
    { data: 'created_at', name: 'created_at' },
    { data: 'updated_at', name: 'updated_at' },
    { data:   "Update",
    render: function ( data, type, row ) 
    {

      if ( type === 'display' ) {

        return '<a  class="btn btn-sm btn-primary" href="{{url("/admin/project-documents/edit/".$project_id)}}/'+row.id+'">Update</a>';
      }
      return data;
    },
    "orderable": false,
    name: 'Update'

  },

  {data:   "Delete",
  render: function ( data, type, row ) {

    if ( type === 'display' ) {

      return '<form id="category_delete_'+row.id+'"  method="post" action="{{url("/admin/delete-project-documents/".$project_id)}}/'+row.id+'">{{ method_field("DELETE") }} {!! csrf_field() !!}<button onclick="confirmDelete('+row.id+');" class="btn btn-sm btn-danger" type="button">Delete</button></form>';
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
    if(confirm("Do you really want to delete this document?"))
    {
      $("#category_delete_"+id).submit();
    }
    return false;
  }
</script>
@endsection
