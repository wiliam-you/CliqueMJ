
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/bower_components/filemanager-ui/dist/css/filemanager-ui.min.css">
    <div id="filemanager1" class="filemanager"></div>   

    <script type="text/javascript" src="{{url('/')}}/public/bower_components/filemanager-ui/dist/js/filemanager-ui.js"></script>   
    <script type="text/javascript">
            $("#filemanager1").filemanager({
                url:'{{url("/")}}/filemanager/connection/{{$url}}/{{$path}}',
                languaje: "US",
                upload_max: 5,
                views:'thumbs',
                insertButton:true,
                token:"{{csrf_token()}}",
                ext: ["jpeg","gif","jpg","png","svg","txt","pdf","odp","ods","odt","rtf","doc","docx","xls","xlsx","ppt","pptx","csv","ogv","mp4","webm","m4v","ogg","mp3","wav","zip","rar","php","log"]
            });
        
    </script>
    