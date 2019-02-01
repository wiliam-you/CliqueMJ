<?php

namespace App\PiplModules\admin\Helpers;


class FileExplorer {

  /**
   * Load file explorer of provided path
   * @param  String(Required)  $url  domain url. 
   * @param  String(Required)  $path  absolute path of the directory. 
   * @return View 
   */
  public static function loadFileExplorer($url,$path) {
    view()->addLocation(app_path('PiplModules/admin/Helpers/file-explorer/'));
    
    echo view('filemanager',array('url'=>base64_encode($url),'path'=>base64_encode($path)));
  }
  }


