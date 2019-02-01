@extends('layouts.app')

@section('meta')
    <title> Home Page</title>
 @endsection
 
@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    Landing Page.
                   
                   <form method="post" action='pi/upload-file' enctype="multipart/form-data">
                       {{csrf_field()}}
   
   audio <input type='file' name='file_audio[]' />
   documents <input type='file' name='file_documents' />
   profile <input type='file' name='profile_image' />
    </br>
   video<input type='file' name='file_video' />
    <input type="submit" value="upload">
    </br>
</form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
