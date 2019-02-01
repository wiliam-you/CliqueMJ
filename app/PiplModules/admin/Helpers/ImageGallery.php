<?php

namespace App\PiplModules\admin\Helpers;

class ImageGallery {

  public static function validate() {
    
  }

  /**
   * Load Image Gallery according to provided theme
   * @param  String(Required)  $theme  unique theme name. 
   * @param  Array(Required)   $config Options are:
   * @param  String(Required)  $config['model']= 'Model Name'
   * @param  String(Required)  $config['fields'] 'Column names of table to get data. ' 
   *                            Example: $config['fields']=array('image_name'=>'image','caption_title'=>'title','caption_description'=>'description');
   * @param  Array(optional)   $config['condition']= example ['status', '=', '1']
   * @param  String(Required)  $config['images_path']=Path where images are located
   * @param  String(Optional)  $config['images_thumbnail_path']Path where images thumbnail are located
   * @param  integer(Optional)  $config['auto_slide'] =values are 1 & 0
   * @param  integer(Optional)  $config['interval'] =in  milliseconds (default is 5000)
   * @param  String(Optional)  $config['chunk'] to display no of thumbnail in one slider(default is 5)
   * @return View 
   */
  public static function loadImageGallery($theme, $config) {
    
//generating unique slider id
    
    
    $config['main_slider_id'] = 'main_' . time() . uniqid();
    $config['thumb_slider_id'] = 'main_' . time() . uniqid();

    
    
    if (isset($config['condition'])) {
      if (!is_array($config['condition'][0])) {
        $config['condition'] = array($config['condition']);
      }
      $gallery_data = $config['model']::select($config['fields'])->where($config['condition'])->get();
    } else {
      $gallery_data = $config['model']::select($config['fields'])->get();
    }

    if ($theme == 'theme3') {
      if (isset($config['chunk'])) {
        $gallery_data = $gallery_data->chunk($config['chunk']);
      } else {
        $gallery_data = $gallery_data->chunk(5);
      }
    }else{
        $gallery_data = $gallery_data->chunk(5);
    }
    
    
    //rendering view from custome location
    view()->addLocation(app_path('PiplModules/admin/Helpers/image-gallery-themes/'));

    echo view($theme, array('gallery_data' => $gallery_data, 'config' => $config));
  }

}
