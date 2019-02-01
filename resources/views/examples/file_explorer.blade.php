@extends('layouts.app')

 @section('meta')
    <title>File Explorer Example</title>
 @endsection
 
@section('content')
     
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">File Explorer Example</div>
                <div class="panel-body">
                 <?php 
                      FileExplorer::loadFileExplorer("http://localhost/",'/laravel-pipl-lib/storage/app/public/storage');
                 ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>
    <div>
   
    </div>
    




@endsection
