<?php
namespace App\Http\Controllers\Auth;
use App\User;
use App\UserInformation;
use App\UserAddress;
use App\PiplModules\roles\Models\Role;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Mail;
use GlobalValues;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/redirect-dashboard';
   

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
        
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        //only common files if we have multiple registration
        return Validator::make($data, [           
            'email' => 'required|email|max:355|unique:users',
            'password' => 'required|min:6|confirmed',
            'first_name' => 'required',
            'last_name' => 'required',
			
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    
    
    protected function profUserRegistration()
    {
        
    }
    protected function create(array $data)
    {
             //getting from global setting
              $site_email=GlobalValues::get('site-email');
              $site_title=GlobalValues::get('site-title');
            
              //Variable Declarations
                $arr_userinformation = array();  
                $arr_useraddress = array();  
                $hasAddress=0;
        
                /*** here we creating user in user table only with email and password fileds **/
                $created_user = User::create([
                    
                    'email' => $data['email'],
                    'password' => ($data['password']),
                ]);
		
                
		// update User Information
		/*
		* Adjusted user specific columns, which may not passed on front end and adjusted with the default values
		*/
		$data["user_type"] = isset($data["user_type"])?$data["user_type"]:"2";			// 1 may have several mean as per enum stored in the database. Here we 
		$data["role_id"] = isset($data["role_id"])?$data["role_id"]:"2";									// 2 means registered user
		$data["user_status"] = isset($data["user_status"])?$data["user_status"]:"0";		// 0 means not active
		$data["gender"] = isset($data["gender"])?$data["gender"]:"3";					// 3 means not specified
		$data["profile_picture"]= isset($data["profile_picture"])?$data["profile_picture"]:"";
		$data["facebook_id"]= isset($data["facebook_id"])?$data["facebook_id"]:"";
		$data["twitter_id"]= isset($data["twitter_id"])?$data["twitter_id"]:"";
		$data["google_id"]= isset($data["google_id"])?$data["google_id"]:"";
		$data["linkedin_id"]= isset($data["linkedin_id"])?$data["linkedin_id"]:"";
		$data["pintrest_id"]= isset($data["pintrest_id"])?$data["pintrest_id"]:"";
		$data["user_birth_date"]= isset($data["user_birth_date"])?$data["user_birth_date"]:"";
		$data["first_name"]= isset($data["first_name"])?$data["first_name"]:"";
		$data["last_name"]= isset($data["last_name"])?$data["last_name"]:"";
		$data["about_me"]= isset($data["about_me"])?$data["about_me"]:"";
		$data["user_phone"]= isset($data["user_phone"])?$data["user_phone"]:"";
		$data["user_mobile"]= isset($data["user_mobile"])?$data["user_mobile"]:"";
		
                //getting address Information.
                
                $data["addressline1"]= isset($data["addressline2"])?$data["addressline1"]:"";
                $data["addressline2"]= isset($data["addressline2"])?$data["addressline2"]:"";
                $data["user_country"]= isset($data["user_country"])?$data["user_country"]:NULL;
                $data["user_state"]= isset($data["user_state"])?$data["user_state"]:NULL;
                $data["user_city"]= isset($data["user_city"])?$data["user_city"]:NULL;
                $data["suburb"]= isset($data["suburb"])?$data["suburb"]:"";
                $data["user_custom_city"]= isset($data["user_custom_city"])?$data["user_custom_city"]:"";
                $data["zipcode"]= isset($data["zipcode"])?$data["zipcode"]:"";
               
                /** user information goes here ****/
                
                $arr_userinformation["profile_picture"] = $data["profile_picture"];
		$arr_userinformation["gender"] = $data["gender"];
		$arr_userinformation["activation_code"] = "";													// By default it'll be no activation code
		$arr_userinformation["facebook_id"] = $data["facebook_id"];
		$arr_userinformation["twitter_id"] = $data["twitter_id"];
		$arr_userinformation["google_id"] = $data["google_id"];
		$arr_userinformation["linkedin_id"] = $data["linkedin_id"];
		$arr_userinformation["pintrest_id"] = $data["pintrest_id"];
		$arr_userinformation["user_birth_date"] = $data["user_birth_date"];
		$arr_userinformation["first_name"] = $data["first_name"];
		$arr_userinformation["last_name"] = $data["last_name"];
		$arr_userinformation["about_me"] = $data["about_me"];
		$arr_userinformation["user_phone"] = $data["user_phone"];
		$arr_userinformation["user_mobile"] = $data["user_mobile"];
		$arr_userinformation["user_status"] = $data["user_status"];
		$arr_userinformation["user_type"] = $data["user_type"];
		$arr_userinformation["user_id"] = $created_user->id;
		
		$updated_user_info = UserInformation::create($arr_userinformation);
                
                
                /** user addesss informations goes here ****/
                if($data["addressline1"]!='')
                {
                     $arr_useraddress["addressline1"] = $data["addressline1"];
                     $hasAddress=1;
                }
                if($data["addressline2"]!='')
                {
                     $arr_useraddress["addressline2"] = $data["addressline2"];
                     $hasAddress=1;
                }
                if($data["user_country"]!='')
                {
                     $arr_useraddress["user_country"] = $data["user_country"];
                      $hasAddress=1;
                }
                  if($data["user_state"]!='')
                {
                     $arr_useraddress["user_state"] = $data["user_state"];
                      $hasAddress=1;
                }
                if($data["user_city"]!='')
                {
                     $arr_useraddress["user_city"] = $data["user_city"];
                      $hasAddress=1;
                }
                  if($data["suburb"]!='')
                {
                     $arr_useraddress["suburb"] = $data["suburb"];
                     $hasAddress=1;
                }
                if($data["user_custom_city"]!='')
                {
                     $arr_useraddress["user_custom_city"] = $data["user_custom_city"];
                      $hasAddress=1;
                }
                if($data["zipcode"]!='')
                {
                     $arr_useraddress["zipcode"] = $data["zipcode"];
                     $hasAddress=1;
                }
                if($created_user->id!='')
                {
                     $arr_useraddress["user_id"] = $created_user->id;
                              
                }
              
                if($hasAddress)
                {
                    UserAddress::create($arr_useraddress);
                }
                
		// asign role to respective user		
		$userRole = Role::where("slug","registered.user")->first();
		
		$created_user->attachRole($userRole);
		
                //sending an email to the user on successfull registration.
                
                $arr_keyword_values = array();
                $activation_code=$this->generateReferenceNumber();
                //Assign values to all macros
                $arr_keyword_values['FIRST_NAME'] = $updated_user_info->first_name;
                $arr_keyword_values['LAST_NAME'] =  $updated_user_info->last_name;
                $arr_keyword_values['VERIFICATION_LINK'] = url('verify-user-email/'.$activation_code);
                 $arr_keyword_values['SITE_TITLE'] =  $site_title;
                // updating activation code                 
                $updated_user_info->activation_code=$activation_code;
                $updated_user_info->save();   
                $email_template = EmailTemplate::where("template_key",'registration-successfull')->first();
                Mail::send('emailtemplate::registration-successfull',$arr_keyword_values, function ($message) use ($created_user,$email_template,$site_email,$site_title)  {
				
                    $message->to( $created_user->email, $created_user->name )->subject($email_template->subject)->from($site_email,$site_title);
				
		});
                
		return $created_user;
		
    }
    private function generateReferenceNumber()
   {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',mt_rand(0, 0xffff), mt_rand(0, 0xffff),mt_rand(0, 0xffff),mt_rand(0, 0x0fff) | 0x4000,mt_rand(0, 0x3fff) | 0x8000,mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff) );
  
   }
	
	
}
