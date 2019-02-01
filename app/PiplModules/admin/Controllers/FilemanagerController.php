<?php 
namespace App\PiplModules\admin\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use GuillermoMartinez\Filemanager\Filemanager;

class FilemanagerController extends Controller {
  
  private $source;
  private $url;
    public function __construct(){
      $this->source="laravel-pipl-lib/storage/app/public/storage";
      $this->url="http://localhost/";
        // $this->middleware('auth');
    }
    public function loadView()
    {
      
        return view('filemanager');
    }
    public function getConnection($url='',$path='')
    {
      $source= $path!=''?base64_decode($path):$this->source;
      $url= $url!=''?base64_decode($url):$this->url;
        $extra = array(
            // path after of root folder
            // if /var/www/public_html is your document root web server
            // then source= usefiles o filemanager/usefiles
            "source" => $source,
            // url domain
            // so that the files and show well http://php-filemanager.rhcloud.com/userfiles/imagen.jpg
            // o http://php-filemanager.rhcloud.com/filemanager/userfiles/imagen.jpg
            "url" => $url,
            );                      
        $f = new Filemanager($extra);
        $f->run();
    }
    public function postConnection()
    {
      $source= $path!=''?base64_decode($path):$this->source;
      $url= $url!=''?base64_decode($url):$this->url;
        $extra = array(
            // path after of root folder
            // if /var/www/public_html is your document root web server
            // then source= usefiles o filemanager/usefiles
            "source" => $source,
            // url domain
            // so that the files and show well http://php-filemanager.rhcloud.com/userfiles/imagen.jpg
            // o http://php-filemanager.rhcloud.com/filemanager/userfiles/imagen.jpg
            "url" => $url,
            );
        if(isset($_POST['typeFile']) && $_POST['typeFile']=='images'){
            $extra['type_file'] = 'images';
        }
        $f = new Filemanager($extra);
        $f->run();
    }
    
}