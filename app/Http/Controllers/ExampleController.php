<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PiplModules\admin\Helpers\FileExplorer;


class ExampleController extends Controller {
  
  /**
   * Create a new authentication controller instance.
   *
   * @return void
   */
  public function __construct() {
    //  $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
  }

  
  public function loadGallery()
  {
    return view('examples.image_gallery_example');
  }
  
  
  public function fileExplorerView()
  {
    return view('examples.file_explorer');
  }
  
  public function ajaxfileExplorerData(Request $request)
  {
    
    echo FileExplorer::loadData($request->dir);
  }

}
