

<link href="{{url('public/media/front/css/image-gallery-themes/theme1.css')}}" rel="stylesheet">	
<script src="{{url('public/media/front/js/imagezoom.js')}}" >	</script>




 <div class="pipl-wrapper clearfix"> 
                        
                        <div class="pipl-left-slider clearfix">
                <div id="{{$config['thumb_slider_id']}}" class="carousel slide prod-imgSliderThumb vertical" data-interval="false">
                    <div class="carousel-inner">
                         <?php
                        $counter=1;
                        $item_counter=0;
                        ?>
                         @foreach($gallery_data as $data)
                        <div class="item @if($counter==1) active @endif">
                        @foreach($data as $obj)
                            <div data-target="#{{$config['main_slider_id']}}" data-slide-to="{{$item_counter++}}" class="thumb @if($counter==1) active <?php $counter++;?> @endif">
                                @if(isset($config['images_thumbnail_path']))
                                <img src="{{$config['images_thumbnail_path']}}/{{$obj->$config['fields']['image_name']}}">
                                @else
                                <img src="{{$config['images_path']}}/{{$obj->$config['fields']['image_name']}}">
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                    <a class="pipl-left carousel-control" href="#{{$config['thumb_slider_id']}}" role="button" data-slide="prev">
                        <span class="fa fa-angle-up"></span>
                    </a>
                    <a class="pipl-right carousel-control" href="#{{$config['thumb_slider_id']}}" role="button" data-slide="next">
                        <span class="fa fa-angle-down"></span>
                    </a>
                </div>
            </div>
                        
                    	<div id="{{$config['main_slider_id']}}"  class="carousel  prod-imgSlider pipl-right-slider vertical" @if(isset($config['auto_slide']) && $config['auto_slide']) data-ride="carousel"  @if(isset($config['interval']) && $config['interval']) data-interval="{{$config['interval']}}" @endif   @endif>
                    <div class="carousel-inner prod-img-data-img">
                      <?php
                        $counter=1;
                        ?>
                        @foreach($gallery_data as $data)
                        @foreach($data as $obj)
                        <div class="item @if($counter==1) active <?php $counter++;?> @endif">
                            <img src="{{$config['images_path']}}/{{$obj->$config['fields']['image_name']}}" data-imagezoom="true">
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
                </div>
                
                		
                    </div>