
<link href="{{url('public/media/front/css/image-gallery-themes/theme2.css')}}" rel="stylesheet">	
<div id="{{$config['main_slider_id']}}" class="carousel slide pipl-prod-imgSlider" @if(isset($config['auto_slide']) && $config['auto_slide']) data-ride="carousel"  @if(isset($config['interval']) && $config['interval']) data-interval="{{$config['interval']}}" @endif   @endif>
                    
         <ol class="carousel-indicators">
              <?php
                        $indicator_counter=0;
                        ?>
                        @foreach($gallery_data as $data)
                        @foreach($data as $obj)
            <li data-target="#{{$config['main_slider_id']}}"  class="@if($indicator_counter==0) active  @endif" data-slide-to="{{$indicator_counter++}}"></li>
                      @endforeach
                        @endforeach
         </ol>
     <div class="carousel-inner prod-img-data-img">
                        <?php
                        $counter=1;
                        ?>
                        @foreach($gallery_data as $data)
                        @foreach($data as $obj)
                        <div class="item @if($counter==1) active <?php $counter++;?> @endif">
                            <img src="{{$config['images_path']}}/{{$obj->$config['fields']['image_name']}}">
                            
                            @if(isset($config['fields']['caption_title']) || isset($config['fields']['caption_description']))
                            <div class="carousel-caption">
                                @if(isset($config['fields']['caption_title']))
                                <h3>Chania{{$obj->$config['fields']['caption_title']}}</h3>
                                @endif
                                
                                @if(isset($config['fields']['caption_description']))
                                <p>{{$obj->$config['fields']['caption_description']}}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                        @endforeach
                    </div>
     
        <a class="left carousel-control" href="#{{$config['main_slider_id']}}" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#{{$config['main_slider_id']}}" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
                </div>

