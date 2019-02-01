<?php

namespace App\PiplModules\admin\Helpers;


use Intervention\Image\Image;
use Illuminate\Support\Facades\Storage;
use Validator;

class FileUpload {
  const max_upload_file_size="10M";
  
  public static function validator($request,$file_inputs,$file_type,$arrValidateMessage) {
    
    
      $i = 0;
      $j = 0;
      $arr_rules = array();
      $arr_message= array();
      
      $validate_response = array();
      
         
       foreach ( $file_inputs as $inputFile) {
          $rule=$file_type[$i];
          
            $type='';
              if(is_array($_FILES[$inputFile]['type']))
              {
                  $type=$_FILES[$inputFile]['type'][0]; 
              }else{
                  $type=$_FILES[$inputFile]['type'];
              }
          switch ($rule) {
            
            //validating for image type
            case 'image':
              if (is_array($request[$inputFile])) {
                $arr_rules[$inputFile . '.*'] = 'required|mimes:jpeg,jpg,png';
                if(isset($arrValidateMessage[$i]['custom_messages'][$rule]))
                {
                  $arr_message[$inputFile . ".*.mimes"] = $arrValidateMessage[$i]['custom_messages'][$rule];
                }
                if(isset($arrValidateMessage[$i]['custom_messages']['required']))
                {
                  $arr_message[$inputFile . ".*.required"] = $arrValidateMessage[$i]['custom_messages']['required'];
                }
              } else {
                $arr_rules[$inputFile] = 'required|mimes:jpeg,jpg,png';
                if(isset($arrValidateMessage[$i]['custom_messages'][$rule]))
                {
                $arr_message[$inputFile . ".mimes"] = $arrValidateMessage[$i]['custom_messages'][$rule];
                }
                
                if(isset($arrValidateMessage[$i]['custom_messages']['required']))
                {
                  $arr_message[$inputFile . ".required"] = $arrValidateMessage[$i]['custom_messages']['required'];
                }
              }
              break;

              
              //validating for audio type
            case 'audio':
            
               Validator::extend('audio', function($attribute, $value, $parameters, $validator)use($type) {
                        return $type=='audio/mpeg';
                    });

              if (is_array($request[$inputFile])) {
                $arr_rules[$inputFile . '.*'] = 'required|audio';
                $arr_message[$inputFile.".audio"] = "Please select valid audio file, please check once again.";
                if(isset($arrValidateMessage[$i]['custom_messages'][$rule]))
                {
                $arr_message[$inputFile . ".*.audio"] = $arrValidateMessage[$i]['custom_messages'][$rule];
                }
                if(isset($arrValidateMessage[$i]['custom_messages']['required']))
                {
                  $arr_message[$inputFile . ".*.required"] = $arrValidateMessage[$i]['custom_messages']['required'];
                }
                
              } else {
                $arr_rules[$inputFile] = 'required|audio';
                
                $arr_message[$inputFile.".audio"] = "Please select valid audio file, please check once again.";
                
                if (isset($arrValidateMessage[$i]['custom_messages'][$rule])) {
                  $arr_message[$inputFile . ".audio"] = $arrValidateMessage[$i]['custom_messages'][$rule];
                }

                if (isset($arrValidateMessage[$i]['custom_messages']['required'])) {
                  $arr_message[$inputFile . ".required"] = $arrValidateMessage[$i]['custom_messages']['required'];
                }
              }
              break;
              
              //validating for video type
            case 'video':
            
               Validator::extend('video', function($attribute, $value, $parameters, $validator)use($type) {
                        return in_array($type,array(
                            'video/mpeg',
                            'video/quicktime',
                            'video/x-msvideo',
                            'video/x-sgi-movie',
                            'video/x-ms-wmv',
                            'video/3gpp',
                            'video/mp4'
                        ));    
                    });

              if (is_array($request[$inputFile])) {
                $arr_rules[$inputFile . '.*'] = 'required|video';
                $arr_message[$inputFile.".video"] = "Please select valid video file, please check once again.";
                if(isset($arrValidateMessage[$i]['custom_messages'][$rule]))
                {
                $arr_message[$inputFile . ".*.video"] = $arrValidateMessage[$i]['custom_messages'][$rule];
                }
                if(isset($arrValidateMessage[$i]['custom_messages']['required']))
                {
                  $arr_message[$inputFile . ".*.required"] = $arrValidateMessage[$i]['custom_messages']['required'];
                }
                
              } else {
                $arr_rules[$inputFile] = 'required|video';
                
                $arr_message[$inputFile.".video"] = "Please select valid video file, please check once again.";
                
                if (isset($arrValidateMessage[$i]['custom_messages'][$rule])) {
                  $arr_message[$inputFile . ".video"] = $arrValidateMessage[$i]['custom_messages'][$rule];
                }

                if (isset($arrValidateMessage[$i]['custom_messages']['required'])) {
                  $arr_message[$inputFile . ".required"] = $arrValidateMessage[$i]['custom_messages']['required'];
                }
              }
              break;
              
         
              
              //validating for any type
            case '*' :
                  if (is_array($request[$inputFile])) {
                $arr_rules[$inputFile . '.*'] = 'required';
                if(isset($arrValidateMessage[$i]['custom_messages']['required']))
                {
                  $arr_message[$inputFile . ".*.required"] = $arrValidateMessage[$i]['custom_messages']['required'];
                }
              } else {
                $arr_rules[$inputFile] = 'required';
                if(isset($arrValidateMessage[$i]['custom_messages']['required']))
                {
                  $arr_message[$inputFile . ".required"] = $arrValidateMessage[$i]['custom_messages']['required'];
                }
              }
              
              
              break;
          }
        
      

      $i++;
      }
      
      
      if(count($arr_message)>0)
      {
      $validate_response = Validator::make($request->all(), $arr_rules,$arr_message);
      }else{
      $validate_response = Validator::make($request->all(), $arr_rules);
      }
      
       if ($validate_response->fails()) {
         
                 $errors= $validate_response->errors()->all();
                 $errors['error'] = 1;
                return $errors;
              }else{
                $errors['error'] = 0;
                return $errors;
              }
  }
  
  private static function initDefaults(){
    
  }

  /**
   * Upload any file at desired destination
   *@param  Request $request
   *@param  array   $file_inputs e.g. array('name_of_input_element','name_of_input_element') (Required)
   * @param  array $file_type =array('*','audio','video','image') (Required)
   * @param  array $destination_path (Required)
   * @param  array $image_options e.g. array(array(resize=true|false,width,height,destination),array(width,height,destination),...) (Optional)
   
   * @param array $arrValidateMessage  eg. 
   * array(
            array("custom_messages"=>array("audio"=>"Not valid audio file","required"=>"Please upload your album song buddy")),
            array("custom_messages"=>array("required"=>"select file")),
            array("custom_messages"=>array("image"=>"Not valid image file","required"=>"Please select your profile image.")),
            null,
          )
   * @return  (success)array orignal_file_name,new_file_name
   * @return  (error)array 
   eg.  [
  0 => "Please upload your album song buddy"
  1 => "select file."
  2 => "Please select your profile image."
  3 => "The file video field is required."
  "error" => 1
]
   */
  public static function upload(
                      $request ,
                      $file_inputs, 
                      $file_type, 
                      $destination_path, 
                      $image_options = null, 
                      $arrValidateMessage = null
                      ) 
    {
    
    FileUpload::initDefaults();
    
    $files = array();
    
    if(!is_array($file_inputs))
    {
      $file_inputs=array($file_inputs);
    }
    
    foreach ( $file_inputs as $inputFile) {
      $files[] = $request[$inputFile];
      }
    
    if(!is_array($file_type))
    {
      $file_type=(array)$file_type;
    }
    if(!is_array($destination_path))
    {
      $destination_path=(array)$destination_path;
    }
    


    if(isset($arrValidateMessage['custom_messages']))
    {
      $arrValidateMessage=array($arrValidateMessage);
    }
      $array_return_data = array();
        $i = 0;
        $j = 0;
          $validateResponse=FileUpload::validator($request, $file_inputs,$file_type,$arrValidateMessage);
          if($validateResponse['error']==1)
          {
            return $validateResponse;
          }    
          
      if(isset($image_options[0]['resize']))
      {
        $image_options=array($image_options);
      }
      
    foreach ($files as $file) {
      if (is_array($file)) {
        $file_array = $file;
        
        foreach ($file_array as $file) {
          
          if ($file) {
            
            $extension = $file->getClientOriginalExtension();
            if (!is_dir(storage_path($destination_path[$j]))) {
              Storage::makeDirectory($destination_path [$j]);
              chmod(storage_path('app/' . $destination_path[$j]), 0777);
            }
            
            $new_file_name = str_replace(".", "-", microtime(true)) . "." . $extension;
            Storage::put($destination_path[$j] . $new_file_name, file_get_contents($file->getRealPath()));
            $array_return_data['original_file_name'] [$i] = $file->getClientOriginalName();
            $array_return_data['new_file_name'][$i] = $new_file_name;


            // make thumbnail
            
            if (isset($image_options) && $image_options[$j][0]['resize'] && (in_array($file_type[$j], array('image')))) {
              foreach ($image_options[$j] as $option) {
                if (!is_dir(storage_path($option['destination']))) {
                  Storage::makeDirectory($option['destination']);
                  chmod(storage_path('app/' . $option['destination']), 0777);
                }
                $thumbnail = \Intervention\Image\Facades\Image::make(storage_path('app/' . $destination_path[$j] . $new_file_name));
                $thumbnail->resize($option["width"], $option["height"]);
                $thumbnail->save(storage_path('app/' . $option['destination'] . $new_file_name));
              }
            }
            $i++;
          }
        }
      } else {
        if ($file) {
          $extension = $file->getClientOriginalExtension();
          if (!is_dir(storage_path($destination_path[$j]))) {
            Storage::makeDirectory($destination_path[$j]);
            chmod(storage_path('app/' . $destination_path[$j]), 0777);
          }
          $new_file_name = str_replace(".", "-", microtime(true)) . "." . $extension;
          Storage::put($destination_path[$j] . $new_file_name, file_get_contents($file->getRealPath()));
          $array_return_data['original_file_name'][$i] = $file->getClientOriginalName();
          $array_return_data['new_file_name'] [$i] = $new_file_name;

          
          // make thumbnail
          if (isset($image_options) && isset($image_options[$j]['resize']) && $image_options[$j]['resize'] && (in_array($file_type[$j], array('image')))) {
            foreach ($image_options[$j] as $option) {
              if (!is_dir(storage_path($option['destination']))) {
                Storage::makeDirectory($option['destination']);
                chmod(storage_path('app/' . $option['destination']), 0777);
              }
              $thumbnail = \Intervention\Image\Facades\Image::make(storage_path('app/' . $destination_path[$j] . $new_file_name));
              $thumbnail->resize($option["width"], $option["height"]);
              $thumbnail->save(storage_path('app/' . $option['destination'] . $new_file_name));
            }
          }
          $i++;
        }
      }
      $j++;
    }

    return $array_return_data;
  }
  
}
