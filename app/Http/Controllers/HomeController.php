<?php

namespace App\Http\Controllers;

use App\PiplModules\admin\Helpers\GlobalValues;
use App\PiplModules\admin\Models\GlobalSetting;
use App\PiplModules\advertizesection\Models\FrontAdvertize;
use App\PiplModules\chooseussection\Models\ChooseUs;
use App\PiplModules\dispencarysection\Models\FrontDispencary;
use App\PiplModules\faq\Models\Faq;
use App\PiplModules\featuresection\Models\Feature;
use App\PiplModules\feedback\Models\Feedback;
use App\PiplModules\getstartedsection\Models\FrontGetStart;
use App\PiplModules\screenshotsection\Models\FrontScreenShot;
use Auth;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Validator;
use App\PiplModules\admin\Models\State;
use App\PiplModules\admin\Models\City;
use Session;
use Hash;
use URL;
use Storage;
use Datatables;
use Mail;
use Illuminate\Support\Facades\Artisan;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dispencary = FrontDispencary::all();
        $advertize = FrontAdvertize::all();
        $get_start = FrontGetStart::all();
        $screen_shot = FrontScreenShot::all();
        $feature = Feature::all();
        $choose_us = ChooseUs::all();
        $faqs = Faq::all();
        $available_on = GlobalSetting::where('slug','available-on')->first();
        $play_store = GlobalSetting::where('slug','play-store')->first();
        $app_store = GlobalSetting::where('slug','app-store')->first();
        $data['email'] = GlobalSetting::where('slug','contact-email')->first();
        $data['phone'] = GlobalSetting::where('slug','phone-no')->first();
        $data['street'] = GlobalSetting::where('slug','street')->first();
        $data['city'] = GlobalSetting::where('slug','city')->first();
        $data['address'] = GlobalSetting::where('slug','address')->first();
        $data['zipcode'] = GlobalSetting::where('slug','zip-code')->first();
        $heading = GlobalSetting::where('slug','banner-heading')->first();
        $description = GlobalSetting::where('slug','banner-description')->first();
        $feature_video = GlobalSetting::where('slug','feature-section-video')->first();
        $how_it_work_video = GlobalSetting::where('slug','how-it-work-video')->first();
        $faq_video = GlobalSetting::where('slug','faq-section-video')->first();

        return view('home',['dispencary'=>$dispencary,'advertize'=>$advertize,'get_start'=>$get_start,'screen_shot'=>$screen_shot,'feature'=>$feature,'available_on'=>$available_on,'play_store'=>$play_store,'app_store'=>$app_store,'choose_us'=>$choose_us,'faqs'=>$faqs,'address'=>$data,'heading'=>$heading,'description'=>$description,'faq_video'=>$faq_video,'feature_video'=>$feature_video,'how_it_work_video'=>$how_it_work_video]);
    }
    public function permissionDenied() {
        
        
            $arr_user= Auth::user();
            $arr_user_data=$arr_user->userInformation;
            return view('permission_denied',array("user_info"=>$arr_user_data));

    }
		
	/**
	*
	*  Checks, whether user has role of administrator. If yes, then forwards to Admin Panel. If user registered from front end, then checks it's email verified status 
	*  and redirect to error page is not activated. If valid email, then checks for status and forward to respective dashboard.
	*	
	*/
	
	public function toDashboard(Request $request)
	{
              $previous_url=URL::previous();
              $previous_url=(explode("/",$previous_url));
              if(end($previous_url)=='login')
              {
                   Session::put('admin-login_page','no');
              }
		// he is admin, redirect to admin panel
               $session_value=Session::get('admin-login_page');
               Session::put('admin-login_page','no');
		if(Auth::user()->isSuperadmin() || Auth::user()->isAdmin() || Auth::user()->userInformation->user_type=='1')
		{
                                      
                    if(Auth::user()->userInformation->user_status=="1")
			{  
                         if(Auth::user()->userInformation->user_type=="1")
                         {
                             return redirect("admin/dashboard");exit;
                         }
                            
                        }
                        elseif(Auth::user()->userInformation->user_status=="0")
			{
                            $errorMsg  = "We found your account is not yet verified. Kindly see the verification email, sent to your email address, used at the time of registration.";
                        }elseif(Auth::user()->userInformation->user_status=="2")
                        {
                            $errorMsg = "We apologies, your account is blocked by administrator. Please contact to administrator for further details.";
                        }
                        Auth::logout();
			return redirect("/admin/login")->with("login-error",$errorMsg);
		}
		// he is not admin. check whether he has activated, ask him to verify the account, otherwise forward to profile page.
		else
		{
			
			if(Auth::user()->userInformation->user_status=="1")
			{
                           if(Auth::user()->userInformation->user_type=="1")
                         {
                             return redirect("admin/dashboard");exit;
                         }else  if(Auth::user()->userInformation->user_type=="2")
                         {

                            if($session_value=='yes')
                            {
                                Auth::logout();
                               $errorMsg="Apologies, your email or password is invalid or you does not have admin user privilages.";
                               return redirect("/login")->with("login-error",$errorMsg); 
                                
                            }
                            elseif(Auth::user()->is_delete==1)
                                {
                                    Auth::logout();
                                   $errorMsg="Your account has been delete, please contact admin.";
                                   return redirect("/login")->with("login-error",$errorMsg); 
                                }else{
                                //dispencery login function
                                return redirect("/dispensary/dashboard");exit;
                            }
                         }
//                         else  if(Auth::user()->userInformation->user_type=="3")
//                         {
//
//                             if($session_value=='yes')
//                            {
//                                Auth::logout();
//                                $errorMsg="Apologies, your email or password is invalid or you does not have admin user privilages.";
//                               return redirect("/admin/login")->with("login-error",$errorMsg);
//
//                            }else{
//                                return redirect("/login");exit;
//                            }
//                         }
                         else{
                               Auth::logout();
                           return redirect("/login")->with('login-error','Something going wrong');
                         }
			
			}
			elseif(Auth::user()->userInformation->user_status=="0" || Auth::user()->userInformation->user_status=="2" )
			{
				// some issue with the account activation. Redirect to login page.
				
				$is_register = $request->session()->pull('is_sign_up');	
				if(Auth::user()->userInformation->user_status=="0")
				{
					if($is_register)
					{
						$successMsg  = "Congratulations! your account is successfully created. We have sent email verification email to your email address. Please verify";
						
						Auth::logout();
						return redirect("login")->with("register-success",$successMsg);

					}
					else
					{
						$errorMsg  = "We found your account is not yet verified. Kindly see the verification email, sent to your email address, used at the time of registration.";
					}
				}
				else
				{
					$errorMsg = "We apologies, your account is blocked by administrator. Please contact to administrator for further details.";
				}
				
				Auth::logout();
				
				return redirect("login")->with("login-error",$errorMsg);
			}
			
		}
		
	}
	
	public function despenceryDashboard()
    {
        return view('pages.dispencery-dashboard');
    }

    public function runQueue(){
        Artisan::call('queue:listen',['--timeout'=>'0']);
    }

    public function dispenceryProfile()
    {
        if (Auth::user()) {
            $states  = State::translatedIn(\App::getLocale())->get();
            $cities  = City::translatedIn(\App::getLocale())->get();
            $arr_user_data = Auth::user();
            return view('pages.dispencery-profile', array("user_info" => $arr_user_data,'states'=>$states,'cities'=>$cities));
        } else {
            $errorMsg = "Error! Something is wrong going on.";
            Auth::logout();

            return redirect("auth.login");
        }
    }

    public function addNameDispenceryProfile(Request $request)
    {
        $data = $request->all();
        $arr_user_data = Auth::user();
        $validate_response = Validator::make($data, array(
            'first_name' => 'required',
            'last_name' => 'required',
            'user_mobile' => 'numeric',
            'address' => 'required',
            'post_code' => 'required|numeric|between:99,999999',
            'city' => 'required',
            'state' => 'required',
        ), array(
                'user_mobile.min' => 'Please enter valid user mobile number.',
                'user_mobile.regex' => 'Please enter 10 digit mobile number.',
                'post_code.between'=>'Please enter valid post code'
            )
        );


        if ($validate_response->fails()) {
            return redirect('dispensary/profile')
                ->withErrors($validate_response)
                ->withInput();
        } else {
            // update User Information
            /*
             * Adjusted user specific columns, which may not passed on front end and adjusted with the default values
             */


            /** user information goes here *** */
            if (isset($data["profile_picture"])) {
                $arr_user_data->userInformation->profile_picture = $data["profile_picture"];
            }
            if (isset($data["gender"])) {
                $arr_user_data->userInformation->gender = $data["gender"];
            }
            if (isset($data["user_status"])) {
                $arr_user_data->userInformation->user_status = $data["user_status"];
            }

            if (isset($data["first_name"])) {
                $arr_user_data->userInformation->first_name = $data["first_name"];
            }
            if (isset($data["last_name"])) {
                $arr_user_data->userInformation->last_name = $data["last_name"];
            }
            if (isset($data["address"])) {
                $arr_user_data->userInformation->address = $data["address"];
            }

            if (isset($data["user_mobile"])) {
                $arr_user_data->userInformation->user_mobile = $data["user_mobile"];
            }

            if($request->hasFile('photo'))
            {

                $uploaded_file = $request->file('photo');

                $extension = $uploaded_file->getClientOriginalExtension();

                $new_file_name = time().".".$extension;

                Storage::put('public/dispencery/'.$new_file_name,file_get_contents($uploaded_file->getRealPath()));

                $arr_user_data->userInformation->profile_picture  = $new_file_name;
            }

            $arr_user_data->userInformation->opening_hour = $request->opening_hour;
            $arr_user_data->userInformation->opening_minut = $request->opening_minut;
            $arr_user_data->userInformation->opening = $request->opening;
            $arr_user_data->userInformation->closing_hour = $request->closing_hour;
            $arr_user_data->userInformation->closing_minut = $request->closing_minut;
            $arr_user_data->userInformation->closing = $request->closing;
            $arr_user_data->userInformation->post_code = $request->post_code;
            $arr_user_data->userInformation->user_birth_date = $request->user_birth_date;
            $arr_user_data->userInformation->city_id = $request->city;
            $arr_user_data->userInformation->state_id = $request->state;

            if($request->latitude!='')
            {
                $arr_user_data->userInformation->lat = $request->latitude;
            }
            if($request->longitude!='')
            {
                $arr_user_data->userInformation->long = $request->longitude;
            }

            $arr_user_data->userInformation->save();

            $succes_msg = "Your profile has been updated successfully!";
            return redirect("dispensary/profile")->with("profile-updated", $succes_msg);
        }
    }

    public function addEmailDispenceryProfile(Request $data)
    {
        $data_values = $data->all();
        $arr_user_data = Auth::user();
        $validate_response = Validator::make($data_values, array(
            'email' => 'required|email|max:500|unique:users',
            'confirm_email' => 'required|email|same:email',
        ));

        if ($validate_response->fails()) {
            return redirect('vendor/profile')
                ->withErrors($validate_response)
                ->withInput();
        } else {
            //updating user email
            $arr_user_data->email = $data->email;
            $arr_user_data->save();

            //updating user status to inactive
//            $arr_user_data->userInformation->user_status = 0;

//            $arr_user_data->userInformation->save();
//            dd($arr_user_data->userInformation->activation_code);
//sending email with verification link
            //sending an email to the user on successfull registration.
//            $site_email = GlobalValues::get('site-email');
//            $site_title = GlobalValues::get('site-title');
//            $arr_keyword_values = array();
//            $activation_code = $this->generateReferenceNumber();
//            //Assign values to all macros
//            $arr_keyword_values['FIRST_NAME'] = $arr_user_data->userInformation->first_name;
//            $arr_keyword_values['LAST_NAME'] = $arr_user_data->userInformation->last_name;
//            $arr_keyword_values['VERIFICATION_LINK'] = url('verify-vendor-email/' . $activation_code);
//            $arr_keyword_values['SITE_TITLE'] = $site_title;
//            // updating activation code
////            $arr_user_data->userInformation->activation_code = $activation_code;
//            $arr_user_data->userInformation->save();
//            $email_template = EmailTemplate::where("template_key", 'vendor-email-change')->first();
//            Mail::send('emailtemplate::vendor-email-change', $arr_keyword_values, function ($message) use ($arr_user_data, $email_template, $site_email, $site_title) {
//
//                $message->to($arr_user_data->email)->subject($email_template->subject)->from($site_email, $site_title);
//            });

            $successMsg = "Congratulations! your email has been updated successfully.";
            Auth::logout();
            return redirect("/login")->with("register-success", $successMsg);
        }
    }

    public function addPasswordDispenceryProfile(Request $data)
    {
        $current_password = $data->current_password;
        $data_values = $data->all();
        $arr_user_data = Auth::user();
        $user_password_chk = Hash::check($current_password, $arr_user_data->password);
//            dd($arr_user_data);
        $validate_response = Validator::make($data_values, array(
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ));

        if ($validate_response->fails()) {
            return redirect('dispensary/profile')
                ->withErrors($validate_response)
                ->withInput();
        } else {
            if ($user_password_chk) {
                //updating user Password
                $arr_user_data->password = $data->new_password;
                $arr_user_data->save();
                Auth::logout();
                return redirect('/login')->with('success','Your password has been changed successfully, Please login with new password');
//                $succes_msg = "Congratulations! your password has been updated successfully!";
//                return redirect("vendor/profile")->with("profile-updated", $succes_msg);
            } else {
                $errorMsg = "Error! current entered password is not valid.";
                return redirect("dispencery/profile")->with("password-update-fail", $errorMsg);
            }
        }
    }

    public function feedbackList()
    {
        Feedback::where('dispencery_id',Auth::user()->id)->update(['is_dispensary_read'=>1]);
        return view('pages.feedback');
    }

    public function feedbackData()
    {
        $all_feedback = Feedback::where('dispencery_id',Auth::user()->id)->get();

        $all_feedback=$all_feedback->sortBy('id');

        foreach($all_feedback as $feedback)
        {
            $feedback->name = $feedback->user?$feedback->user->userInformation->first_name.' '.$feedback->user->userInformation->last_name:'-';
        }

        return DataTables::of($all_feedback)
//                     ->addColumn('name', function($feedback){
//                      return '';
//                   })
            ->make(true);
    }

    public function sendEmail(Request $request)
    {
        $site_email=GlobalValues::get('contact-email');
        $site_title=GlobalValues::get('site-title');
        $arr_keyword_values = array();
        //Assign values to all macros
        $arr_keyword_values['MESSAGE'] = $request->message;
        $arr_keyword_values['NAME'] =  $request->name;
        $arr_keyword_values['EMAIL'] =  $request->email;
        $arr_keyword_values['SITE_TITLE'] =  $site_title;
        // updating activation code
        Mail::send('EmailTemplate::contact-us', $arr_keyword_values, function ($message) use ($request,$site_email,$site_title) {

            $message->to($site_email)->subject('Contact Us')->from($request->email,$site_title);
        });

        return redirect('/');
    }
	
}
