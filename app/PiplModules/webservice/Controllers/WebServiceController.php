<?php

namespace App\PiplModules\webservice\Controllers;

use App\Http\Controllers\Controller;
use App\PiplModules\admin\Helpers\GlobalValues;
use App\PiplModules\admin\Models\GlobalSetting;
use App\PiplModules\advertisement\Models\Advertisement;
use App\PiplModules\contentpage\Models\ContentPage;
use App\PiplModules\coupon\Models\Coupon;
use App\PiplModules\coupon\Models\PatientCoupon;
use App\PiplModules\coupon\Models\PatientUseCoupon;
use App\PiplModules\EmailTemplate\Models\EmailTemplate;
use App\PiplModules\feedback\Models\Feedback;
use App\PiplModules\product\Models\Product;
use App\PiplModules\property\Models\Property;
use App\PiplModules\webservice\Models\AdvertisementOfferReport;
use App\PiplModules\webservice\Models\PatientAdvertisementOffer;
use App\PiplModules\webservice\Models\PatientRecord;
use App\PiplModules\webservice\Models\PatientSharedOffer;
use App\PiplModules\webservice\Models\ForgetPassword;
use App\PiplModules\webservice\Models\Testimonial;
use App\PiplModules\zone\Models\Cluster;
use App\PiplModules\genericmessages\Models\GenericMessage;
use App\User;
use App\UserInformation;
use Auth;
use Carbon\Carbon;
use Datatables;
use FCM;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Storage;
use Validator;

class WebServiceController extends Controller {

    private $placeholder_img;

    public function __construct() {
        $this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
    }

//function to insert registration field for web services        
    public function registrationData(Request $request) {
        $data = $request->all();
        $validate_response = Validator::make($data, array(
                'email_id' => 'unique:users,email',
            )
        );

        if ($validate_response->fails()) {
            return json_encode(['error_code' => '2', 'msg' => 'Email Already Registered']);
        } else {
            $user = new User();
            $user->email = $request->email_id;
            $user->password = $request->password;
            $user->save();

            $data["user_type"] = isset($data["user_type"]) ? $data["user_type"] : "3";   // 1 may have several mean as per enum stored in the database. Here we 
            $data["role_id"] = isset($data["role_id"]) ? $data["role_id"] : "2";         // 2 means registered user
            $data["user_status"] = isset($data["user_status"]) ? $data["user_status"] : "0";  // 0 means not active
            $data["gender"] = isset($data["gender"]) ? $data["gender"] : "3";     // 3 means not specified
            $data["profile_picture"] = isset($data["profile_picture"]) ? $data["profile_picture"] : "";
            $data["facebook_id"] = isset($data["facebook_id"]) ? $data["facebook_id"] : "";
            $data["twitter_id"] = isset($data["twitter_id"]) ? $data["twitter_id"] : "";
            $data["google_id"] = isset($data["google_id"]) ? $data["twittergoogle_id"] : "";
            $data["linkedin_id"] = isset($data["linkedin_id"]) ? $data["linkedin_id"] : "";
            $data["pintrest_id"] = isset($data["pintrest_id"]) ? $data["pintrest_id"] : "";
            $data["user_birth_date"] = $request->date_of_birth;
            $data["first_name"] = $request->first_name;
            $data["last_name"] = $request->last_name;
            $data["about_me"] = isset($data["about_me"]) ? $data["about_me"] : "";
            $data["user_phone"] = isset($data["user_phone"]) ? $data["user_phone"] : "";
            $data["user_mobile"] = isset($data["user_mobile"]) ? $data["user_mobile"] : "";
            $data["address"] = isset($data["address"]) ? $data["address"] : "";

            //getting address Information.

            $data["addressline1"] = isset($data["addressline2"]) ? $data["addressline1"] : "";
            $data["addressline2"] = isset($data["addressline2"]) ? $data["addressline2"] : "";
            $data["user_country"] = isset($data["user_country"]) ? $data["user_country"] : NULL;
            $data["user_state"] = isset($data["user_state"]) ? $data["user_state"] : NULL;
            $data["user_city"] = isset($data["user_city"]) ? $data["user_city"] : NULL;
            $data["suburb"] = isset($data["suburb"]) ? $data["suburb"] : "";
            $data["user_custom_city"] = isset($data["user_custom_city"]) ? $data["user_custom_city"] : "";
            $data["zipcode"] = isset($data["zipcode"]) ? $data["zipcode"] : "";

            /** user information goes here *** */
            $arr_userinformation["profile_picture"] = $data["profile_picture"];
            $arr_userinformation["gender"] = $data["gender"];
            $arr_userinformation["activation_code"] = "";             // By default it'll be no activation code
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
            $arr_userinformation["user_status"] = 1;
            $arr_userinformation["user_type"] = 3;
            $arr_userinformation["address"] = $data["address"];
            $arr_userinformation["user_id"] = $user->id;
            $arr_userinformation["application_id"] = substr(md5(rand()),0,12);

            $updated_user_info = UserInformation::create($arr_userinformation);

            //sending an email to the user on successfull registration.

            $arr_keyword_values = array();
            $activation_code = $this->generateReferenceNumber();
            $site_title = GlobalSetting::where('slug', 'site-title')->first();
            $site_title = $site_title->value;
            $site_email = GlobalSetting::where('slug', 'site-email')->first();
            $site_email = $site_email->value;


            //Assign values to all macros
            $arr_keyword_values['FIRST_NAME'] = $updated_user_info->first_name;
            $arr_keyword_values['LAST_NAME'] = $updated_user_info->last_name;
            $arr_keyword_values['EMAIL'] = $request->email_id;
            $arr_keyword_values['PASSWORD'] = $request->password;
            $arr_keyword_values['SITE_TITLE'] = $site_title;
            // updating activation code                 
            $email_template = EmailTemplate::where("template_key", 'registration-successfull')->first();
            Mail::send('EmailTemplate::registration-successfull', $arr_keyword_values, function ($message) use ($user, $email_template, $site_email, $site_title) {

                $message->to($user->email, $user->userInformation->first_name)->subject($email_template->subject)->from($site_email, $site_title);
            });

            $user = User::where('id', $user->id)->with('UserInformation')->first();

            $user->userInformation->times_of_login = 1;
            $user->userInformation->token = $request->token;
            $user->userInformation->flag = $request->flag;
            $user->userInformation->is_loggedin = '1';
            $user->userInformation->save();

            return json_encode(['error_code' => '0', 'msg' => 'Register Successfully', 'user' => $user]);
        }
    }

    //Login with facebook and google+
    public function socialLogin(Request $request) {
        if ($request->google_id && $request->google_id != '') {
            $user_info = UserInformation::where('google_id', $request->google_id)->where('is_delete',0)->first();
        } elseif ($request->fb_id && $request->fb_id != '') {
            $user_info = UserInformation::where('facebook_id', $request->fb_id)->where('is_delete',0)->first();
        }

        if ($user_info != '') {
            $user = User::where('id', $user_info->user_id)->with('userInformation')->first();
            $user->userInformation->times_of_login += 1;
            $user->userInformation->token = $request->token;
            $user->userInformation->flag = $request->flag;
            $user->userInformation->is_loggedin = '1';
            $user->userInformation->save();
            $user->is_loggedin = '1';
            $user->save();
            return json_encode(['error_code' => '0', 'msg' => 'Login Successful', 'user' => $user,'social_login'=>1]);
        } else {
            $email = User::where('email',$request->email_id)->first();
            if($email !='')
            {
                $user = User::where('email', $request->email_id)->with('userInformation')->first();
                $user->userInformation->times_of_login += 1;
                $user->userInformation->token = $request->token;
                $user->userInformation->flag = $request->flag;
                $user->userInformation->is_loggedin = '1';
                $user->userInformation->save();
                $user->is_loggedin = '1';
                $user->save();
                return json_encode(['error_code' => '0', 'msg' => 'Login Successful', 'user' => $user,'social_login'=>1]);
            }
            else
            {
                $user = new User();
                $user->email = $request->email_id;
                $user->password = '';
                $user->is_loggedin = '1';
                $user->save();

                $data["user_type"] = isset($data["user_type"]) ? $data["user_type"] : "3";   // 1 may have several mean as per enum stored in the database. Here we
                $data["role_id"] = isset($data["role_id"]) ? $data["role_id"] : "2";         // 2 means registered user
                $data["user_status"] = isset($data["user_status"]) ? $data["user_status"] : "0";  // 0 means not active
                $data["gender"] = isset($data["gender"]) ? $data["gender"] : "3";     // 3 means not specified
                $data["profile_picture"] = isset($data["profile_picture"]) ? $data["profile_picture"] : "";
                $data["facebook_id"] = $request->login_type == 'fb' ? $request->fb_id : '';
                $data["twitter_id"] = isset($data["twitter_id"]) ? $data["twitter_id"] : "";
                $data["google_id"] = $request->login_type == 'google' ? $request->google_id : '';
                $data["linkedin_id"] = isset($data["linkedin_id"]) ? $data["linkedin_id"] : "";
                $data["pintrest_id"] = isset($data["pintrest_id"]) ? $data["pintrest_id"] : "";
                $data["user_birth_date"] = isset($data["date_of_birth"]) ? $data["date_of_birth"] : "";
                $data["first_name"] = $request->first_name;
                $data["last_name"] = $request->last_name;
                $data["about_me"] = isset($data["about_me"]) ? $data["about_me"] : "";
                $data["user_phone"] = isset($data["user_phone"]) ? $data["user_phone"] : "";
                $data["user_mobile"] = isset($data["user_mobile"]) ? $data["user_mobile"] : "";
                $data["address"] = isset($data["address"]) ? $data["address"] : "";

                /** user information goes here *** */
                $arr_userinformation["profile_picture"] = $data["profile_picture"];
                $arr_userinformation["gender"] = $data["gender"];
                $arr_userinformation["activation_code"] = "";             // By default it'll be no activation code
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
                $arr_userinformation["user_status"] = 1;
                $arr_userinformation["user_type"] = 3;
                $arr_userinformation["address"] = $data["address"];
                $arr_userinformation["user_id"] = $user->id;
                $arr_userinformation["application_id"] = substr(md5(rand()),0,12);

                $updated_user_info = UserInformation::create($arr_userinformation);

                $user = User::where('id', $user->id)->with('UserInformation')->first();

                $user->userInformation->times_of_login = 1;
                $user->userInformation->token = $request->token;
                $user->userInformation->flag = $request->flag;
                $user->userInformation->is_loggedin = '1';
                $user->userInformation->save();

                return json_encode(['error_code' => '0', 'msg' => 'Register Successfully', 'user' => $user,'social_login'=>1]);
            }

        }
    }

    public function setDateOfBirth(Request $request)
    {
        $user = User::where('id', $request->pat_id)->with('UserInformation')->first();

        if($user)
        {
            $user->userInformation->user_birth_date = $request->date_of_birth;

            $user->userInformation->save();
            return json_encode(['error_code' => '0', 'msg' => 'Birth Date Store Successfully', 'user' => $user]);
        }
        else
        {
            return json_encode(['error_code' => '1', 'msg' => 'Something went wrong', 'user' => $user]);
        }

    }

    public function login(Request $request) {
        $user = User::where('email', $request->email_id)->where('is_delete',0)->first();
        if ($user && $user->userInformation->user_type == 3) {
            if (Hash::check($request->password, $user->password)) {
                $user_info = UserInformation::where('user_id', $user->id)->where('user_status', '1')->first();
                if ($user_info) {
                    $user = User::where('id', $user->id)->with('UserInformation')->first();

                    $user->userInformation->times_of_login += 1;
                    $user->userInformation->token = $request->token;
                    $user->userInformation->flag = $request->flag;
                    $user->userInformation->is_loggedin = '1';
                    $user->userInformation->save();

                    $user->is_loggedin = '1';
                    $user->save();
                    return json_encode(['error_code' => '0', 'msg' => 'Login Successful', 'user' => $user,'social_login'=>0]);
                } else {
                    return json_encode(['error_code' => '1', 'msg' => 'Account not active']);
                }
            } else {
                return json_encode(['error_code' => '1', 'msg' => 'Invalid email or password']);
            }
        } else {
            return json_encode(['error_code' => '1', 'msg' => 'User not registered']);
        }
    }

    public function getCouponDetail(Request $request)
    {
        $user = User::where('id',$request->pat_id)->where('is_delete',0)->first();

        //new code
        $total_adv_redeem = $user->userInformation->getTotalOfferRedeem();
        $cnt_mj_offer = 0;
        $cnt_not_mj_offer = 0;
        if(isset($total_adv_redeem)) {
            foreach ($total_adv_redeem as $k => $item) {
                $advertisement = Advertisement::find($item->offer_id);
	                if($advertisement){
	                	if($advertisement->is_mj_offer == '1') {
	                    $cnt_mj_offer += 1;
	                }
	                else {
	                    $cnt_not_mj_offer += 1;
	                }
                }
                
            }
        }
        $total_adv_redeem1 = $cnt_not_mj_offer;
        //new code
        $total_coupon_used = $user->userInformation->getTotalCouponUsedCount->count() + $cnt_mj_offer;
        if($user)
        {
            return json_encode(['error_code' => '0', 'total_coupo_used' => $total_coupon_used,'total_advertisement_offer_redeem'=>$total_adv_redeem1]);
        }
        else
        {
            return json_encode(['error_code' => '0', 'total_coupo_used' => '0','total_free_gram_earned' => '0','total_advertisement_offer_redeem'=>'0']);
        }

    }

    public function forgotPassword(Request $request) {
        $user = User::where('email', $request->email_id)->first();

        if ($user != '') {
            $arr_keyword_values = array();
            //$new_password = time();
            $site_title = GlobalSetting::where('slug', 'site-title')->first();
            $site_title = $site_title->value;
            $site_email = GlobalSetting::where('slug', 'site-email')->first();
            $site_email = $site_email->value;
            // Assign values to all macros

            $forgetPassword = new ForgetPassword;
            $forgetPassword->user_id = $user->id;
            //$forgetPassword->created_at = time();
            $forgetPassword->md5 = str_random(32);
            $forgetPassword->save();

            $arr_keyword_values['FIRST_NAME'] = $user->userInformation->first_name;
            $arr_keyword_values['LAST_NAME'] = $user->userInformation->last_name;
            $arr_keyword_values['PASSWORD'] = url("forget_password/reset?md5=" . $forgetPassword->md5);
            $arr_keyword_values['SITE_TITLE'] = $site_title;
            // // updating password
            //$user->password = $new_password;
            $user->save();

            $email_template = EmailTemplate::where("template_key", 'resend-password')->first();
            Mail::send('EmailTemplate::resend-password', $arr_keyword_values, function ($message) use ($user, $email_template, $site_email, $site_title) {
                $message->to($user->email, $user->userInformation->first_name)->subject($email_template->subject)->from($site_email, $site_title);
            });
            return json_encode(['error_code' => '0', 'msg' => 'Please check your email to reset your password']);
        } else {
            return json_encode(['error_code' => '1', 'msg' => 'Email not registered yet']);
        }
    }

    public function changePassword(Request $request) {
        $user = User::where('id', $request->user_id)->with('UserInformation')->first();
        if ($user != '') {
            if (Hash::check($request->old_pass, $user->password)) {
                $user->password = $request->new_pass;
                $user->save();
                return json_encode(['error_code' => '0', 'msg' => 'Password has been changed.', 'user' => $user]);
            } else {
                return json_encode(['error_code' => '1', 'msg' => 'Incorrect old password.', 'user' => $user]);
            }
        } else {
            return json_encode(['error_code' => '1', 'msg' => 'User not found.']);
        }
    }

    public function changeProfile(Request $request) {
        $user = User::where('id', $request->user_id)->with('UserInformation')->first();
        if ($user != '') {
            $user->userInformation->first_name = $request->first_name != '' ? $request->first_name : $user->first_name;
            $user->userInformation->last_name = $request->last_name != '' ? $request->last_name : $user->last_name;
            $user->userInformation->address = $request->address != '' ? $request->address : $user->address;
            $user->userInformation->user_mobile = $request->contact_number != '' ? $request->contact_number : $user->user_mobile;
            $user->userInformation->user_birth_date = $request->date_of_birth != '' ? Carbon::parse($request->date_of_birth)->format('m/d/Y') : $user->user_birth_date;

            $msg = 'Profile information updated successfully!';
            $error_code = 0;

            if ($request->hasFile('user_profile_img')) {

                $uploaded_file = $request->file('user_profile_img');


                $extension = $uploaded_file->getClientOriginalExtension();

                $new_file_name = time() . "." . $extension;

                Storage::put('public/patient/' . $new_file_name, file_get_contents($uploaded_file->getRealPath()));

                $user->userInformation->profile_picture = $new_file_name;
            }

            if ($request->has('user_profile_img')) {

                try {
                    $data = $request->user_profile_img;
                    list($type, $data) = explode(';', $data);
                    list(, $data) = explode(',', $data);
                    $data = base64_decode($data);
                    $filename = str_replace(" ", "", microtime()) . '.png';
                    Storage::put('public/patient/' . $filename, $data);

                    $img = Image::make(base_path() . 'public/patient/' . $filename);
                    $img->save(base_path() . 'public/patient/' . $filename);

                    $user->userInformation->profile_picture = $filename;
                } catch (Exception $e) {
                    $msg = $e->getMessage();
                    $error_code = 1;
                }
            }

            $user->userInformation->save();
            return json_encode(['error_code' => $error_code, 'msg' => $msg, 'user' => $user]);
        } else {
            return json_encode(['error_code' => '1', 'msg' => 'User not found.']);
        }
    }

    public function dispenceryByRange(Request $request) {
        $disp = UserInformation::where('user_type',2)->get();

        $disp = $disp->filter(function($user){
            return $user->user->is_delete == 0;
        });

        $ids = '';
        foreach ($disp as $d) {
            $ids[] = $d->id;
        }
        $disp = UserInformation::whereIn('id', $ids)->with('user')->get();
        if (count($disp) > 0) {
            for ($i = 0; $i < count($disp); $i++) {
                $disp[$i]['real_opening_time'] = $disp[$i]->opening_hour . ':' . $disp[$i]->opening_minut . ':00 ' . $disp[$i]->opening;
                $disp[$i]['real_closing_time'] = $disp[$i]->closing_hour . ':' . $disp[$i]->closing_minut . ':00 ' . $disp[$i]->closing;
            }

            return json_encode(['error_code' => 0, 'Dispenceries' => $disp]);
        } else {
            return json_encode(['error_code' => 1, 'msg' => 'Dispensary not available']);
        }
    }

    public function getDispenceryProduct(Request $request) {
        $property = Property::where('status', 1)->where('is_delete',0)->get();
        $products = Product::where('user_id', $request->dis_id)->where('status', 1)->where('is_delete', 0)->with('productSizes')->get();
        $all_product = [];
        if ($products != '') {
            foreach ($property as $prop) {
                foreach ($products as $product) {
                    if ($product->property_id == $prop->id) {
                        $all_product[] = $product;
                    }
                }
            }

            return json_encode(['error_code' => 0, 'products' => $all_product, 'property' => $property]);
        } else {
            return json_encode(['error_code' => 1, 'products' => $all_product, 'msg' => 'No products found', 'property' => $property]);
        }
    }

    public function dispenceryFeedback(Request $request) {
        $feedback = new Feedback();

        $feedback->dispencery_id = $request->dis_id;
        $feedback->patient_id = $request->pat_id;
        $feedback->feedback = $request->feedback;
        $feedback->rating = $request->rate;

        if ($feedback->save()) {
            $user = User::find($request->dis_id);
            $rate = 0;
            $total_count = count($user->feedbackDispencery);
            if ($total_count > 0) {
                foreach ($user->feedbackDispencery as $rating) {
                    $rate += $rating->rating / 5;
                }
                $user->userInformation->rating = ($rate / $total_count) * 5;
            } else {
                $user->userInformation->rating = 0;
            }

            $user->userInformation->save();

            return json_encode(['error_code' => 0, 'msg' => 'Feedback stored successfully']);
        } else {
            return json_encode(['error_code' => 1, 'msg' => 'Error']);
        }
    }

    private function generateReferenceNumber() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    public function dispensaryAlgo(Request $request) {
        $coupon = Coupon::where('unique_code', $request->coupon_id)->first();
        if ($coupon != '') {
            $dispensary = User::find($coupon->dispensary_id);
            $current_week = $dispensary->clusterDispencery->cluster->current_week;
            if ($current_week != '') {
                if ($dispensary->clusterDispencery->cluster->getAvailableCoupon) {
                    if ($dispensary->clusterDispencery->cluster->clusterDispencery->count() == $dispensary->clusterDispencery->cluster->limit) {
                        if ($dispensary->clusterDispencery->cluster->clusterDispencery[$current_week - 1]->dispencery_id != $coupon->dispensary_id) {
                            $patient_coupon = PatientCoupon::where('patient_id', $request->pat_id)->where('to_dispensary_id', $dispensary->clusterDispencery->cluster->clusterDispencery[$current_week - 1]->dispencery_id)->first();
                            if ($patient_coupon == '') {
                                $patient = new PatientCoupon();
                                $patient->patient_id = $request->pat_id;
                                $patient->coupon_id = $coupon->id;
                                $patient->offer = $coupon->coupon_value;
                                $patient->start_date = Carbon::parse($dispensary->clusterDispencery->cluster->cluster_start_date)->addDays(($current_week - 1) * 7)->format('m/d/Y');
                                $patient->end_date = Carbon::parse($dispensary->clusterDispencery->cluster->cluster_start_date)->addDays($current_week * 7)->format('m/d/Y');
                                $patient->from_dispensary_id = $coupon->dispensary_id;
                                $patient->to_dispensary_id = $dispensary->clusterDispencery->cluster->clusterDispencery[$current_week - 1]->dispencery_id;
                                $patient->save();

                                $patient_coupon = PatientCoupon::where('id', $patient->id)->with('coupon')->with('userInformation')->with('user')->get();
                                return json_encode(['error_code' => 0, 'patient_coupon' => $patient_coupon]);

                            } else {
                                return json_encode(['error_code' => 1, 'msg' => 'Coupon not available']);
                            }
                        } else {

                            $patient_coupon = PatientCoupon::where('patient_id', $request->pat_id)->where('to_dispensary_id', $dispensary->clusterDispencery->cluster->clusterDispencery[$current_week]->dispencery_id)->first();

                            if($patient_coupon=='')
                            {
                                $patient = new PatientCoupon();
                                $patient->patient_id = $request->pat_id;
                                $patient->coupon_id = $coupon->id;
                                $patient->offer = $coupon->coupon_value;
                                $patient->start_date = Carbon::parse($dispensary->clusterDispencery->cluster->cluster_start_date)->addDays(($current_week) * 7)->format('m/d/Y');
                                $patient->end_date = Carbon::parse($dispensary->clusterDispencery->cluster->cluster_start_date)->addDays(($current_week + 1)* 7)->format('m/d/Y');
                                $patient->from_dispensary_id = $coupon->dispensary_id;
                                $patient->to_dispensary_id = $dispensary->clusterDispencery->cluster->clusterDispencery[$current_week]->dispencery_id;
                                $patient->save();

                                $patient_coupon = PatientCoupon::where('id', $patient->id)->with('coupon')->with('userInformation')->with('user')->get();
                                return json_encode(['error_code' => 0, 'patient_coupon' => $patient_coupon]);
                            }
                            else{
                                return json_encode(['error_code' => 1, 'msg' => 'Coupon not available']);
                            }

                        }
                    } else {
                        return json_encode(['error_code' => 1, 'msg' => 'Coupon not available']);
                    }
                } else {
                    return json_encode(['error_code' => 1, 'msg' => 'Coupon not available']);
                }
            } else {
                return json_encode(['error_code' => 1, 'msg' => 'Coupon not available']);
            }
        } else {

//            search for advertisement offer

            $advertisement = Advertisement::where('unique_code', $request->coupon_id)
                ->where('is_delete', 0)
                ->where('status', 1)
                ->first();
            if($advertisement!='')
            {
                $add_start_date = Carbon::parse($advertisement->start_date)->format('m/d/Y');
                $add_end_date = Carbon::parse($advertisement->end_date)->format('m/d/Y');
                $currunt_date = Carbon::now()->format('m/d/Y');

                if ($advertisement != '' && strtotime($currunt_date) <= strtotime($add_end_date)) {
                    $advertisement->is_delete = 1;
                    $advertisement->save();
                    if(file_exists(base_path().'/storage/app/public/qr-codes/'.$advertisement->qr_code.'.bmp')) {
                        unlink(base_path() . '/storage/app/public/qr-codes/' . $advertisement->qr_code . '.bmp');
                    }
                    $patient_advertizement = PatientAdvertisementOffer::where('advertisement_id', $advertisement->id)->where('patient_id', $request->pat_id)->first();

                    if ($patient_advertizement == '') {
                        $patient_advertizement = new PatientAdvertisementOffer();

                        $patient_advertizement->advertisement_id = $advertisement->id;
                        $patient_advertizement->patient_id = $request->pat_id;

                        $patient_advertizement->save();

                        $advertisement_report = new AdvertisementOfferReport();
                        $advertisement_report->scan_by = $request->pat_id;
                        $advertisement_report->offer_id = $advertisement->id;
                        $advertisement_report->save();

                        return json_encode(['error_code' => 0, 'data' => $patient_advertizement->advertisement,'brand'=> $patient_advertizement->advertisement->brand]);
                    } else {
                        return json_encode(['error_code' => 1, 'msg' => 'Offer already used']);
                    }
                } else {
                    return json_encode(['error_code' => 1, 'msg' => 'offer expired']);
                }
            }
            else
            {
                return json_encode(['error_code' => 1, 'msg' => 'Coupon not available']);
            }

        }
    }

    public function checkCoupon() {
//        check patient's expire coupon and delete it
        PatientCoupon::where('end_date', '<', Carbon::now()->format('m/d/Y'))->delete();

//        change current week of clusters
        $clusters = Cluster::where('current_week', '>', 0)->get();
        foreach ($clusters as $cluster) {
            if ($cluster->clusterDispencery->count() == $cluster->limit) {
                $start_date = Carbon::parse($cluster->cluster_start_date);
                $today = Carbon::now();
                $diff = $start_date->diffInDays($today);
                if ($diff % 7 == 0) {
                    if ($cluster->current_week == $cluster->limit) {
                        $cluster->current_week = 1;
                    } else {
                        $cluster->current_week += 1;
                    }
                    $cluster->save();
                }
            }
        }
    }

    public function useCoupon(Request $request) {
        $patient_coupon = PatientCoupon::find($request->coupon_id);

        if ($patient_coupon != '') {
            $current_date = strtotime(Carbon::now()->format('m/d/Y'));
            $start_date = strtotime(Carbon::parse($patient_coupon->start_date)->format('m/d/Y'));
            if($start_date > $current_date){
                return json_encode(['error_code' => 1, 'msg' => 'Offer not started yet']);
            }

            $use_coupon = new PatientUseCoupon();

            $use_coupon->patient_id = $patient_coupon->patient_id;
            $use_coupon->coupon_id = $patient_coupon->coupon_id;
            $use_coupon->offer = $patient_coupon->offer;
            $use_coupon->to_dispensary = $patient_coupon->to_dispensary_id;
            $use_coupon->from_dispensary = $patient_coupon->from_dispensary_id;

            $use_coupon->save();

            $patientRecord = PatientRecord::where('offer_id', $patient_coupon->coupon_id)->first();

            if($patientRecord){
                $patientRecord->times_of_redeem_mj_offer += 1;
            }else{
                $patientRecord = new PatientRecord();
                $patientRecord->patient_id = $patient_coupon->patient_id;
                $patientRecord->offer_id = $patient_coupon->coupon_id;
                $patientRecord->times_of_redeem_mj_offer = 1;
            }
            $patientRecord->save();
                //new code
            $patient_coupon->delete();
                return json_encode(['error_code' => 0, 'msg' => 'Success']);
        } else {
            return json_encode(['error_code' => 1, 'msg' => 'Something went wrong']);
        }
    }

    public function getPatientCoupon(Request $request) {

        $data = [];
        $shared_offer = PatientSharedOffer::where('sender',$request->pat_id)->pluck('offer_id')->toArray();

        $all_offer = PatientAdvertisementOffer::where('patient_id', $request->pat_id)->whereNotIn('advertisement_id',$shared_offer)->where('is_used',0)->with('advertisement')->get();
        if(isset($all_offer)) {
            $all_offer = $all_offer->filter(function ($offer) {
                if(isset($offer->advertisement)) {
                    return $offer->advertisement->is_mj_offer == 1;
                }
            });

            foreach ($all_offer as $offer) {
                $offer->advertisement->brand_name = $offer->advertisement->brand->name;
                $offer->advertisement->is_share = PatientSharedOffer::where('offer_id', $offer->advertisement_id)->first() ? 1 : 0;
                $data[] = $offer;
            }
        }
        $patient_coupon = PatientCoupon::where('patient_id', $request->pat_id)->with('coupon')->with('user')->with('userInformation')->get();

        if ($patient_coupon != '' || count($data) > 0) {
            return json_encode(['error_code' => 0, 'patient_coupon' => $patient_coupon, 'data' => $data]);
        } else {
            return json_encode(['error_code' => 1, 'msg' => 'No coupons found']);
        }
    }

    public function getPrivacyPolicy() {
        $policy = ContentPage::where('page_alias', 'privacy-policy')->first();
        return json_encode(['error_code' => 0, 'data' => $policy->page_content]);
    }

    public function getAboutUs() {
        $about_us = ContentPage::where('page_alias', 'about-us')->first();
        return json_encode(['error_code' => 0, 'data' => $about_us->page_content]);
    }

    public function offerGetUsed(Request $request) {
        if($request->advertisement_id != '' && $request->pat_id != '') {
            $adv = Advertisement::find($request->advertisement_id);

            $add_start_date = Carbon::parse($adv->start_date)->format('m/d/Y');
            $add_end_date = Carbon::parse($adv->end_date)->format('m/d/Y');
            $currunt_date = Carbon::now()->format('m/d/Y');
            if (isset($adv) && strtotime($currunt_date) < strtotime($add_start_date)) {
                return json_encode(['error_code' => 1, 'msg' => 'Offer is not started yet.']);
            } else {
                $advertisement = PatientAdvertisementOffer::where('patient_id', $request->pat_id)->where('advertisement_id', $request->advertisement_id)->first();

                $advertisement->is_used = 1;
                $advertisement->save();

                $advertisement_offer_report = AdvertisementOfferReport::where('offer_id', $request->advertisement_id)->first();

                if($advertisement_offer_report->is_notification){
                    $advertisement_offer_report->where('scan_by',$request->pat_id)->update(['redeem_by'=>$request->pat_id]);
                }else{
                    $advertisement_offer_report->redeem_by = $request->pat_id;
                    $advertisement_offer_report->save();
                }

                $patientRecord = PatientRecord::where('offer_id', $request->advertisement_id)->first();
                $adv = Advertisement::find($request->advertisement_id);
                if($adv->is_notification == '1') {
                    if($patientRecord) {
                        $patientRecord->times_of_redeem_offer += 1;
                        $patientRecord->is_notification = '1';
                    } else {
                        $patientRecord = new PatientRecord;
                        $patientRecord->offer_id = $request->global_id;
                        $patientRecord->times_of_redeem_offer = 1;
                        $patientRecord->is_notification = '1';
                    }
                    $patientRecord->save();
                }
                return json_encode(['error_code' => 0, 'msg' => 'Offer redeemed successfully']);
            }
        } else {
            return json_encode(['error_code' => 1, 'msg' => 'Please provide your inputs']);
        }
    }

    public function getPatientAdvertisementOffer(Request $request) {
        $shared_offer = PatientSharedOffer::where('sender',$request->pat_id)->pluck('offer_id')->toArray();
        $all_offer = PatientAdvertisementOffer::where('patient_id', $request->pat_id)->whereNotIn('advertisement_id',$shared_offer)->where('is_used',0)->with('advertisement')->orderBy('id', 'desc')->get();
        $all_offer = $all_offer->filter(function($offer){
            if(isset($offer->advertisement)) {
                return $offer->advertisement->is_mj_offer == 0;
            }
        });

        foreach($all_offer as $offer)
        {
            $offer->advertisement->brand_name = $offer->advertisement->brand->name;
            $offer->advertisement->is_share = PatientSharedOffer::where('offer_id',$offer->advertisement_id)->first() ? 1 : 0;
        }

        $all_offer = array_values($all_offer->toArray());

        return json_encode(['error_code' => 0, 'data' => $all_offer]);
    }

    public function deleteCoupon(Request $request)
    {
        if(isset($request->advertisement_id) && $request->advertisement_id != '')
        {
            $advertise = PatientAdvertisementOffer::where('patient_id', $request->pat_id)->where('advertisement_id', $request->advertisement_id)->first();
            if(!isset($advertise)){
                $advertise = PatientAdvertisementOffer::where('patient_id', $request->pat_id)->first();
            }
            $patient_id = $advertise->patient_id;
            $advertise->delete();
            $all_offer = PatientAdvertisementOffer::where('patient_id', $patient_id)->where('is_used',0)->with('advertisement')->get();
            foreach($all_offer as $offer)
            {
                $offer->advertisement->brand_name = $offer->advertisement->brand->name;
            }
            return json_encode(['error_code' => 0, 'data' => $all_offer]);
        }
        elseif (isset($request->coupon_id) && $request->coupon_id != '')
        {
            $coupon = PatientCoupon::find($request->coupon_id);
            $patient_id = $coupon->patient_id;
            $coupon->delete();
            $all_coupon = PatientCoupon::where('patient_id', $patient_id)->with('coupon')->with('user')->with('userInformation')->get();

            if ($all_coupon != '') {
                return json_encode(['error_code' => 0, 'patient_coupon' => $all_coupon]);
            } else {
                return json_encode(['error_code' => 1, 'msg' => 'No coupons found']);
            }
        }
        else
        {
            return json_encode(['error_code' => 1, 'msg' => 'No data found']);
        }
    }

    public function twil(){
        $sid = "AC2c0ff630095d6ba3b837679c2356fccb"; // Your Account SID from www.twilio.com/console
        $token = "d14f64c4642ebe5874a6847f905a2a98"; // Your Auth Token from www.twilio.com/console

        $client = new \Twilio\Rest\Client($sid, $token);
        $message = $client->messages->create(
            "+917709068656", // Text this number
            array(
                'from' => '+12133206986', // From a valid Twilio number
                'body' => 'Your friend Abhijit'.
                    ' share offer with with you. Please enter 1234 code to use. Download app '
            )
        );
    }

    public function shareOffer(Request $request)
    {
        $advertise = PatientAdvertisementOffer::where('id',$request->advertisement_id)->where('is_used',0)->first();
        if($advertise->is_notification == '1') {
            return json_encode(['error_code' => 2, 'msg' => 'Global offer can not be shared.']);
        }
        $sender_user = User::find($advertise->patient_id);
        $is_shared = PatientSharedOffer::where('offer_id',$advertise->advertisement_id)->first();
        $app_store = GlobalSetting::where('slug','app-store')->first();
        $play_store = GlobalSetting::where('slug','play-store')->first();

        $error_msg = $is_shared ? 'shared' : 'used';

//         1 => in Application
//         2 => send mobile message
//         3 => send email
        if($advertise && $sender_user && !$is_shared)
        {
            if($request->share_type == 1)
            {
                $receiver_patient = UserInformation::where('application_id',$request->application_id)->first();
                if($receiver_patient)
                {
                    if($receiver_patient->user_id == $sender_user->id){
                        return json_encode(['error_code' => 2, 'msg' => 'You could not share offer to your self']);
                    }
                    $patient_offer = PatientAdvertisementOffer::find($request->advertisement_id);
                    $patient_offer->patient_id = $receiver_patient->user_id;

                    $msg = $patient_offer->advertisement->is_mj_offer == 1 ? 'You have received an MJ Offer' : 'You have received a Clique Offer';

                    if($receiver_patient->flag == 1)
                    {
                        $resp = $this->iosNotificaton($receiver_patient->token,$msg);
                    }
                    else
                    {
                        $resp = $this->androidNotification($receiver_patient->token,$msg);
                    }

                    $patient_offer->save();
                    PatientSharedOffer::create(['sender'=>$sender_user->id,'receiver'=>$receiver_patient->user_id,'offer_id'=>$advertise->advertisement_id]);
                    return json_encode(['error_code'=>0,'msg'=>'Coupon Shared Successfully']);
                }
                else
                {
                    return json_encode(['error_code' => 1, 'msg' => 'App id not found']);
                }

            }
            elseif ($request->share_type == 2)
            {
                $sid = "AC2c0ff630095d6ba3b837679c2356fccb"; // Your Account SID from www.twilio.com/console
                $token = "d14f64c4642ebe5874a6847f905a2a98"; // Your Auth Token from www.twilio.com/console

                $client = new \Twilio\Rest\Client($sid, $token);
                $message = $client->messages->create(
                    $request->mobile, // Text this number
                    array(
                        'from' => '+12133206986', // From a valid Twilio number
                        'body' => 'Your friend '.$sender_user->userInformation->first_name.
                            ' '.$sender_user->userInformation->last_name.
                            ' has sent you an offer. Please enter coupon code: '
                            .$advertise->advertisement->unique_code.' into promo code page or scan page. Download CliqueMJ here: '.$app_store->value.', '.$play_store->value
                    )
                );

                PatientSharedOffer::create(['sender'=>$sender_user->id,'offer_id'=>$advertise->advertisement_id]);

                return json_encode(['error_code' => 0, 'msg' => 'Coupon sent successfully']);
            }
            else
            {
                PatientSharedOffer::create(['sender'=>$sender_user->id,'offer_id'=>$advertise->advertisement_id]);
                $site_email=GlobalValues::get('site-email');
                $site_title=GlobalValues::get('site-title');
                $arr_keyword_values = array();
                //Assign values to all macros
                $arr_keyword_values['FIRST_NAME'] = $sender_user->userInformation->first_name;
                $arr_keyword_values['LAST_NAME'] = $sender_user->userInformation->last_name;
                $arr_keyword_values['EMAIL'] = $request->email;
                $arr_keyword_values['SITE_TITLE'] =  $site_title;
                $arr_keyword_values['APP_STORE'] =  $app_store->value;
                $arr_keyword_values['PLAY_STORE'] =  $play_store->value;
                $arr_keyword_values['CODE'] =  $advertise->advertisement->unique_code;
                // updating activation code
                $email_template = EmailTemplate::where("template_key",'share-advertisement')->first();

                Mail::send('EmailTemplate::share-advertisement', $arr_keyword_values, function ($message) use ($sender_user,$request,$email_template,$site_email,$site_title) {

                    $message->to($request->email)->subject($email_template->subject)->from($site_email,$site_title);
                });

                return json_encode(['error_code' => 0, 'msg' => 'Coupon sent successfully']);
            }
        }
        else{
            return json_encode(['error_code' => 0, 'msg' => 'Coupon already '.$error_msg]);
        }

    }

    public function getOffer(Request $request)
    {
        $offer = Advertisement::where('unique_code',$request->unique_code)->first();

        if($offer)
        {
            $shared_offer = PatientSharedOffer::where('offer_id',$offer->id)->where('receiver',0)->first();

            if($shared_offer)
            {
                if($shared_offer->sender == $request->pat_id){
                    return json_encode(['error_code' => 0, 'msg' => 'Offer could not accepted by self account']);
                }
                $shared_offer->receiver = $request->pat_id;
                $shared_offer->save();

                $patient_offer = PatientAdvertisementOffer::where('advertisement_id',$offer->id)->first();
                $patient_offer->patient_id = $request->pat_id;
                $patient_offer->save();

                return json_encode(['error_code' => 1, 'msg' => 'Offer get successfully']);
            }
            else
            {
                return json_encode(['error_code' => 0, 'msg' => 'Offer not found']);
            }
        }
        else
        {
            return json_encode(['error_code' => 0, 'msg' => 'Offer not found']);
        }
    }

    public function setPatientData(Request $request)
    {
        if(isset($request->coupon_code) && $request->coupon_code != '')
        {
            $patient_record = PatientRecord::where('patient_id',$request->pat_id)->where('coupon_code',$request->coupon_code)->first();
            $adv = Advertisement::where('qr_code', $request->coupon_code)->first();
            if($patient_record)
            {
                if($adv->is_mj_offer == '1') {
                    $patient_record->times_of_view_mj_offer += 1;
                } else {
                    $patient_record->times_of_view_offer += 1;
                }
            }
            else
            {
                $patient_record = new PatientRecord();
                $patient_record->patient_id = $request->pat_id;
                $patient_record->coupon_code = $request->coupon_code;
                $patient_record->coupon_name = '-';
                if($adv->is_mj_offer == '1') {
                    $patient_record->times_of_view_mj_offer = 1;
                } else {
                    $patient_record->times_of_view_offer = 1;
                }
            }
            $patient_record->save();
        }
        elseif(isset($request->offer_id) && $request->offer_id != '')
        {
            $patient_record = PatientRecord::where('patient_id',$request->pat_id)->where('offer_id',$request->offer_id)->first();
            $adv = Advertisement::find($request->offer_id);
            if($patient_record)
            {
                if($adv->is_mj_offer == '1') {
                    $patient_record->times_of_view_mj_offer += 1;
                } else {
                    $patient_record->times_of_view_offer += 1;
                }
            }
            else
            {
                $patient_record = new PatientRecord();
                $patient_record->patient_id = $request->pat_id;
                if($adv->is_mj_offer == '1') {
                    $patient_record->times_of_view_mj_offer = 1;
                } else {
                    $patient_record->times_of_view_offer = 1;
                }
                $patient_record->offer_id = $request->offer_id;
            }
            $patient_record->save();
        }
    }


    function iosNotificaton($token, $message) {
        $url = "https://fcm.googleapis.com/fcm/send";
        $token = $token;
        $serverKey = 'AIzaSyAxwB0y744VgFewEOsjhydqYo7IkKQbbSA';
        $title = "Clique";
        $body = $message;
        $notification = array('text' => $body, 'sound' => 'default');
        $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//Send the request
        $response = curl_exec($ch);
        curl_close($ch);
        return 1;
    }

    public function androidNotification($token, $message) {
        //using fcm
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => array($token),
            'data' => array("message" => $message)
        );
        $fields = json_encode ( $fields );
        $headers = array(
            'Authorization: key=AIzaSyCH-DvKVeUVjnRRI6JxfjuT1QaJTUSOyZg',
            'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($ch);
        // Close connection
        curl_close($ch);
        return 1;
        //return $result;
    }

    public function customerLogout(Request $request)
    {
        if($request->user_id != "") {
            $user = User::find($request->user_id);
            $user->is_loggedin = '0';
            $user->userInformation->is_loggedin = '0';
            $user->userInformation->save();
            $user->save();
            return json_encode(['error_code' => '0', 'msg' => 'Logged out successfully.']);
        }
        else {
            return json_encode(['error_code' => '1', 'msg' => 'Something went wrong.']);
        }
    }

    public function viewOfferMsgCount(Request $request)
    {
        if($request->global_id != "") {
            if($request->flag == '0') {
                $message = GenericMessage::find($request->global_id);
                if(isset($message)) {
                    $message->view_count = intval($message->view_count) + 1;
                    $message->save();
                }
            }
            if($request->flag == '1' || $request->flag == '2') {
                $useAdvReport = AdvertisementOfferReport::where('offer_id', $request->global_id)->where('is_notification', '1')->first();
                $patientRecord = PatientRecord::where('offer_id', $request->global_id)->first();
                $adv = Advertisement::find($request->global_id);

                if(isset($patientRecord)) {
                    if($adv->is_mj_offer == '1') {
                        $patientRecord->times_of_view_mj_offer += 1;
                    } else {
                        $patientRecord->times_of_view_offer += 1;
                    }
                    $patientRecord->is_notification = ($adv->is_notification == '0') ? '0' : '1';
                } else {
                    $patientRecord = new PatientRecord;
                    $patientRecord->offer_id = $request->global_id;
                    if($adv->is_mj_offer == '1') {
                        $patientRecord->times_of_view_mj_offer = 1;
                    } else {
                        $patientRecord->times_of_view_offer = 1;
                    }
                    $patientRecord->is_notification = ($adv->is_notification == '0') ? '0' : '1';
                }
                $patientRecord->save();

                if(isset($useAdvReport)) {
                    //adv report
                    $useAdvReport->scan_by = '';
                    $useAdvReport->offer_id = $request->global_id;
                    $useAdvReport->is_notification = '1';
                } else {
                    $useAdvReport = new AdvertisementOfferReport();
                    $useAdvReport->scan_by = '';
                    $useAdvReport->offer_id = $request->global_id;
                    $useAdvReport->is_notification = '1';
                }
                $useAdvReport->save();


            }
            return json_encode(['error_code' => '0', 'msg' => 'success']);
        }
        else {
            return json_encode(['error_code' => '1', 'msg' => 'Something went wrong.']);
        }
    }

}
