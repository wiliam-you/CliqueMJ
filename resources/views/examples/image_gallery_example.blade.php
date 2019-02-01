@extends('layouts.app')

 @section('meta')
    <title>Image Gallery</title>
 @endsection
 
@section('content')
    
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Image Gallery</div>
                <div class="panel-body">
                    <?php
                    $config['model']='App\PiplModules\admin\Models\Gallery';
                    $config['fields']=array('image_name'=>'image','caption_title'=>'title','caption_description'=>'description');
                    $config['condition']=['status', '=', '1'];
                    $config['auto_slide']=0;
                    $config['interval']=1000;
                    $config['images_path']=url('/storage/app/gallery/');
//                    $config['images_thumbnail_path']=url('/storage/app/gallery/thumb');
                    $config['chunk']=5;
                      ImageGallery::loadImageGallery('theme3',$config);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
    <div>
   
    </div>
    




@endsection
