@extends('layouts.app')

 @section('meta')
    <title>Crop Example</title>
 @endsection
 
@section('content')

    <link rel="Stylesheet" type="text/css" href="{{url('/public/media/front/css/croppie.css')}}" />
    <script src="{{url('/public/media/front/js/croppie.min.js')}}"></script>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Crop Example</div>
                  @if (session('login-error'))
               <div class="alert alert-danger">
                {{ session('login-error') }}
            	</div>
                @endif
                @if (session('register-success'))
               <div class="alert alert-success">
                {{ session('register-success') }}
            	</div>
                @endif
             
                <div class="panel-body">
                    <form id='frm-upload-profile' class="form-horizontal" role="form" method="POST"  enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="col-md-4 control-label">Profile Image</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control" name="profile_image" id="upload">
                            </div>
                        </div>
                       
                        
                        <input type="hidden" name="imagebase64" id="imagebase64">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="button" class="btn btn-primary" id="profile_submit">
                                    <i class="fa fa-btn fa-sign-in"></i>Upload  
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <div class="demo" style="display:none"></div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div>
   
    </div>
    <script>
                var $uploadCrop;
                function readFile(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                          $('.demo').show();
                            $uploadCrop.croppie('bind', {
                                url: e.target.result
                            });
                            $('.demo').addClass('ready');
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $uploadCrop = $('.demo').croppie({
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'square'
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    }
                });
                $('#upload').on('change', function () {
                    readFile(this);
                });
                $('#profile_submit').on('click', function (ev) {
                    $uploadCrop.croppie('result', {
                        type: 'canvas',
                        size: 'original'
                    }).then(function (resp) {
                        $('#imagebase64').val(resp);
                        $('#frm-upload-profile').submit();
                    });
                    return false;
                });
            
 
</script>
<script src="{{url('/public/media/front/js/croppie-demo.js')}}"></script>
@endsection
