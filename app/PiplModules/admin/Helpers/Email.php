<?php

namespace App\PiplModules\admin\Helpers;

//    error_reporting(E_ALL);
//    ini_set('display_errors', 1);

use Intervention\Image\Image;
use Illuminate\Support\Facades\Storage;
use Validator;
use Mail;
use GlobalValues;
use App\PiplModules\emailtemplate\Models\EmailTemplate;

class Email {

  /**
   * Send Mail
   * @param  String  $template_key  unique template key
   * @param  Array   $arr_keyword_values eg. $arr_keyword_values['FIRST_NAME']='Hancy';
   * @param  Array   $header 

   * @return Boolean 
   */
  public static function mail($template_key, $arr_keyword_values = array(), $header) {

    if (!isset($header['from'])) {
      $header['from'][0] = GlobalValues::get('site-email');
      $header['from'][1] = GlobalValues::get('site-title');
    }

    $arr_keyword_values['SITE_TITLE'] = isset($header['from'][1]) ? $header['from'][1] : '';
    $email_template = EmailTemplate::where("template_key", $template_key)->first();

   return Mail::send('emailtemplate::' . $template_key, $arr_keyword_values, function ($message) use ($email_template, $header) {

     
      $message->subject($email_template->subject);


      if (is_array($header['to'])) {
        for ($i = 0; $i < count($header['to']); $i++) {
          if (is_array($header['to'][$i])) {
            //for mutiple recipients with title
            if (isset($header['to'][$i][1])) {
              $message->to($header['to'][$i][0], $header['to'][$i][1]);
            } else {
              //for mutiple recipients without title
              $message->to($header['to'][$i][0]);
            }
          } else {

            if (isset($header['to'][1])) {
              //for single recipient with title
              $message->to($header['to'][0], $header['to'][1]);
            } else {
              //for single recipient with title
              $message->to($header['to'][0]);
            }
          }
        }
      } else {
        //for single recipient without title
        $message->to($header['to']);
      }


      if (isset($header['from'][1])) {
        $message->from($header['from'][0], $header['from'][1]);
      } else {
        $message->from($header['from'][0]);
      }
      
      //attachment
      if(isset($header['attachment']))
      {
        if(is_array($header['attachment']))
        {
          foreach($header['attachment'] as $file)
          {
        $message->attach($file);
          }
        }else{
        $message->attach($header['attachment']);
        }
      }
      
      return true;
    });
  }

}
