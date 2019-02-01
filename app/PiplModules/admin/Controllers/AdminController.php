<?php
namespace App\PiplModules\admin\Controllers;
use App\Jobs\sendEmail;
use App\PiplModules\feedback\Models\Feedback;
use Session;
use App\User;
use App\UserInformation;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Validator;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Auth;
use Mail;
use Hash;
use Image;
use Datatables;
use App\PiplModules\roles\Models\Role;
use App\PiplModules\roles\Models\RoleUser;
use App\PiplModules\roles\Models\Permission;
use App\PiplModules\admin\Models\GlobalSetting;
use App\PiplModules\admin\Models\Country;
use App\PiplModules\admin\Models\State;
use App\PiplModules\admin\Models\City;
use App\PiplModules\EmailTemplate\Models\EmailTemplate;
use App\PiplModules\zone\Models\ClusterDispencery;
use Storage;
use Cache;
use GlobalValues;
class AdminController extends Controller {

    /**
     * Show the login window for admin.
     *
     * @return Response
     */
    protected function validator(Request $request) {
        //only common files if we have multiple registration
        return Validator::make($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
        ]);
    }

    public function logout() {
        $successMsg = "You have logged out successfully!";
        Auth::logout();
        return redirect("admin/login")->with("register-success", $successMsg);
    }


    public function showDashboard() {
        if (!Auth::user()) {
            return redirect("admin/login")->with("login-error", "uanable to access this page");
            exit;
        }
        if (Auth::user()->userInformation->user_type != "1") {
            return redirect("login")->with("login-error", "uanable to access thi page");
            exit;
        }

        $all_users = UserInformation::all();

        $resistered_dispencery = UserInformation::where('user_type',2)->get();
        $resistered_patient_count = UserInformation::where('user_type',3)->get();

        $resistered_dispencery = $resistered_dispencery->filter(function($dispensary){
            return $dispensary->user->is_delete == 0;
        });



        $resistered_patient_count = $resistered_patient_count->filter(function($patient){
            return $patient->user->is_delete == 0;
        });


        $admin_users = $all_users->reject(function ($user) {
            return $user->user->hasRole('registered.user') === true || $user->user_type!='1' || $user->user->hasRole('superadmin')==true;
        });

//        $resistered_user_count = count($registered_users);
        $admin_user_count = count($admin_users);

        return view("admin::dashboard", array('resistered_dispencery_count' => $resistered_dispencery->count(), 'admin_user_count' => $admin_user_count,'resistered_patient_count'=>$resistered_patient_count->count()));
    }

    public function adminProfile() {
        if (Auth::user()) {
            $arr_user_data = Auth::user();
            return view('admin::profile', array("user_info" => $arr_user_data));
        } else {
            $errorMsg = "Error! Something is wrong going on.";
            Auth::logout();

            return redirect("admin/login")->with("register-success",$successMsg);

        }
    }

    public function showLogin(Request $request) {
        if (Auth::user()) {
            if (Auth::user()->userInformation->user_type != "1") {
                return redirect("login")->with("login-error", "uanable to access this page");
                exit;
            } else if (Auth::user()->userInformation->user_type == "1") {
                return redirect("admin/dashboard");
                exit;
            }
        }

        Session::put('admin-login_page', 'yes');
        return view('admin::login');
    }



    public function showPasswordReset() {

        return view('admin::password_reset');
    }
    public function showPasswordResetPost(Request $request,$token)
    {
        if (is_null($token)) {
            return $this->getEmail();
        }

        $email = $request->input('email');

        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with(compact('token', 'email'));
        }

        if (view()->exists('auth.passwords.reset')) {
            return view('admin::reset')->with(compact('token', 'email'));
        }

        return view('admin::reset')->with(compact('token', 'email'));

    }

    public function updateProfile(Request $data) {
        $data_values = $data->all();
        if (Auth::user()) {
            $arr_user_data = Auth::user();
            $validate_response = Validator::make($data_values, array(
                'first_name' => 'required',
                'last_name' => 'required',
                'gender' => 'required',
                'user_mobile' => 'required|numeric',
            ), array(
                    'user_mobile.min' => 'Please enter valid user mobile number.',
                    'user_mobile.regex' => 'Please enter 10 digit mobile number.',
                )
            );

            if ($validate_response->fails()) {
                return redirect('admin/profile')
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
                if (isset($data["about_me"])) {
                    $arr_user_data->userInformation->about_me = $data["about_me"];
                }

                if (isset($data["user_mobile"])) {
                    $arr_user_data->userInformation->user_mobile = $data["user_mobile"];
                }

                $arr_user_data->userInformation->save();

                $succes_msg = "Your profile has been updated successfully!";
                return redirect("admin/profile")->with("profile-updated", $succes_msg);
            }
        } else {
            $errorMsg = "Error! Something is wrong going on.";
            Auth::logout();
            return redirect("admin/login")->with("issue-profile", $errorMsg);
        }
    }

    protected function updateEmailInfo(Request $data) {
        $data_values = $data->all();
        if (Auth::user()) {
            $arr_user_data = Auth::user();
            $validate_response = Validator::make($data_values, array(
                'email' => 'required|email|max:500|unique:users',
                'confirm_email' => 'required|email|same:email',
            ));

            if ($validate_response->fails()) {
                return redirect('admin/profile')
                    ->withErrors($validate_response)
                    ->withInput();
            } else {
                //updating user email
                $arr_user_data->email = $data->email;
                $arr_user_data->save();

                //updating user status to inactive
                $arr_user_data->userInformation->user_status = 0;
                $arr_user_data->userInformation->save();
                //sending email with verification link
                //sending an email to the user on successfull registration.
                $site_email=GlobalValues::get('site-email');
                $site_title=GlobalValues::get('site-title');
                $arr_keyword_values = array();
                $activation_code = $this->generateReferenceNumber();
                //Assign values to all macros
                $arr_keyword_values['FIRST_NAME'] = $arr_user_data->userInformation->first_name;
                $arr_keyword_values['LAST_NAME'] = $arr_user_data->userInformation->last_name;
                $arr_keyword_values['VERIFICATION_LINK'] = url('admin/verify-user-email/' . $activation_code);
                $arr_keyword_values['SITE_TITLE'] =  $site_title;
                // updating activation code                 
                $arr_user_data->userInformation->activation_code = $activation_code;
                $arr_user_data->userInformation->save();
                $email_template = EmailTemplate::where("template_key",'admin-email-change')->first();
                Mail::send('EmailTemplate::admin-email-change', $arr_keyword_values, function ($message) use ($arr_user_data,$email_template,$site_email,$site_title)  {

                    $message->to($arr_user_data->email)->subject($email_template->subject)->from($site_email,$site_title);
                });

                $successMsg = "Congratulations! your email has been updated successfully. We have sent email verification email to your email address. Please verify";
                Auth::logout();
                return redirect("admin/login")->with("register-success", $successMsg);
            }
        } else {
            $errorMsg = "Error! Something is wrong going on.";
            Auth::logout();
            return redirect("admin/login")->with("issue-profile", $errorMsg);
        }
    }

    protected function updateAdminUserEmailInfo(Request $data, $user_id) {
        $data_values = $data->all();
        if (Auth::user()) {
            $arr_user_data = User::find($user_id);
            $validate_response = Validator::make($data_values, array(
                'email' => 'required|email|max:500|unique:users',
                'confirm_email' => 'required|email|same:email',
            ));

            if ($validate_response->fails()) {
                return redirect('admin/update-admin-user/' . $user_id)
                    ->withErrors($validate_response)
                    ->withInput();
            } else {
                //updating user email
                $arr_user_data->email = $data->email;
                $arr_user_data->save();

                //updating user status to inactive
                $arr_user_data->userInformation->user_status = 0;
                $arr_user_data->userInformation->save();
                //sending email with verification link
                //sending an email to the user on successfull registration.
                $site_email=GlobalValues::get('site-email');
                $site_title=GlobalValues::get('site-title');
                $arr_keyword_values = array();
                $activation_code = $this->generateReferenceNumber();
                //Assign values to all macros
                $arr_keyword_values['FIRST_NAME'] = $arr_user_data->userInformation->first_name;
                $arr_keyword_values['LAST_NAME'] = $arr_user_data->userInformation->last_name;
                $arr_keyword_values['VERIFICATION_LINK'] = url('admin/verify-user-email/' . $activation_code);
                $arr_keyword_values['SITE_TITLE'] =  $site_title;
                // updating activation code                 
                $arr_user_data->userInformation->activation_code = $activation_code;
                $arr_user_data->userInformation->save();
                $email_template = EmailTemplate::where("template_key",'admin-email-change')->first();
                Mail::send('EmailTemplate::admin-email-change', $arr_keyword_values, function ($message) use ($arr_user_data,$email_template,$site_email,$site_title) {

                    $message->to($arr_user_data->email)->subject($email_template->subject)->from($site_email,$site_title);
                });
                //updating user Password

//                $succes_msg = "Admin user email has been updated successfully!";
                $succes_msg = "Sub user email has been updated successfully!";
                return redirect("admin/update-admin-user/" . $user_id)->with("profile-updated", $succes_msg);
            }
        } else {
            $errorMsg = "Error! Something is wrong going on.";
            Auth::logout();
            return redirect("admin/login")->with("issue-profile", $errorMsg);
        }
    }

    protected function updatePasswordInfo(Request $data) {
        $current_password = $data->current_password;
        $data_values = $data->all();
        if (Auth::user()) {
            $arr_user_data = Auth::user();
            $user_password_chk = Hash::check($current_password, $arr_user_data->password);
            $validate_response = Validator::make($data_values, array(
                'current_password' => 'required|min:6',
                'new_password' => 'required|min:6|confirmed',
                'confirm_password' => 'required',
            ));

            if ($validate_response->fails()) {
                return redirect('admin/profile')
                    ->withErrors($validate_response)
                    ->withInput();
            } else {
                if ($user_password_chk) {
                    //updating user Password
                    $arr_user_data->password = $data->new_password;
                    $arr_user_data->save();
                    $succes_msg = "Congratulations! your password has been updated successfully!";
                    return redirect("admin/profile")->with("profile-updated", $succes_msg);
                } else {
                    $errorMsg = "Error! current entered password is not valid.";
                    return redirect("admin/profile")->with("password-update-fail", $errorMsg);
                }
            }
        } else {
            $errorMsg = "Error! Something wrong is going on.";
            Auth::logout();
            return redirect("login")->with("issue-profile", $errorMsg);
        }
    }

    protected function updateAdminUserPasswordInfo(Request $data, $user_id) {

        $data_values = $data->all();


        if (Auth::user()) {
            $arr_user_data = User::find($user_id);

            $validate_response = Validator::make($data_values, array(
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:new_password',
            ));
            if ($validate_response->fails()) {
                return redirect("admin/update-admin-user/" . $user_id)
                    ->withErrors($validate_response)
                    ->withInput();
            } else {

                //updating user Password
                $arr_user_data->password = $data->new_password;
                $arr_user_data->save();
//                $succes_msg = "Admin user password has been updated successfully!";
                $succes_msg = "Sub user password has been updated successfully!";
                return redirect("admin/update-admin-user/" . $user_id)->with("profile-updated", $succes_msg);
            }
        } else {
            $errorMsg = "Error! Something wrong is going on.";
            Auth::logout();
            return redirect("login")->with("issue-profile", $errorMsg);
        }
    }

    protected function verifyUserEmail($activation_code) {
        $user_informations = UserInformation::where('activation_code', $activation_code)->first();
        if (count($user_informations)>0) {

            if ($user_informations->user_status == '0') {

                //updating the user status to active
                $user_informations->user_status = '1';
                $user_informations->activation_code = '';
                $user_informations->save();
                $successMsg = "Congratulations! your account has been successfully verified. Please login to continue";
                Auth::logout();
                return redirect("admin/login")->with("register-success", $successMsg);
            } else {
                $user_informations->activation_code = '';
                $user_informations->save();
                $errorMsg = "Error! this link has been expired";
                Auth::logout();
                return redirect("admin/login")->with("login-error", $errorMsg);
            }
        } else {
            $errorMsg = "Error! this link has been expired";
            Auth::logout();
            return redirect("admin/login")->with("login-error", $errorMsg);
        }
    }

    public function listRegisteredUsers() {

        return view("admin::list-users");
    }


    public function listRegisteredUserData() {
        $all_users = UserInformation::where('user_type',2)->get();
        $all_users=$all_users->sortByDesc('id');
        $registered_users = $all_users->filter(function ($user) {
            return ($user->user_type == 2 && $user->user->is_delete==0);
        });
        return Datatables::of($registered_users)
            ->addColumn('first_name', function($regsiter_user) {
                return $regsiter_user->first_name;
            })
            ->addColumn('last_name', function($regsiter_user) {
                return $regsiter_user->last_name;
            })
            ->addColumn('email', function($regsiter_user) {
                return $regsiter_user->user->email;
            })
            ->addColumn('status', function($admin_users) {

                $html = '';
                if ($admin_users->user_status == 0) {
                    $html = '<div  id="active_div' . $admin_users->user->id . '"    style="display:none;"  >
                                                <a class="label label-success" title="Click to Change UserStatus" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 2);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Active</a> </div>';
                    $html = $html . '<div id="inactive_div' . $admin_users->user->id . '"  style="display:inline-block" >
                                                <a class="label label-warning" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Inactive </a> </div>';
                    $html = $html . '<div id="blocked_div' . $admin_users->user->id . '" style="display:none;"  >
                                                <a class="label label-danger" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Blocked </a> </div>';
                } else if ($admin_users->user_status == 2) {
                    $html = '<div  id="active_div' . $admin_users->user->id . '"  style="display:none;" >
                                                <a class="label label-success" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 2);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Active</a> </div>';
                    $html = $html . '<div id="blocked_div' . $admin_users->user->id . '"    style="display:inline-block" >
                                                <a class="label label-danger" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Blocked</a> </div>';
                } else {//
                    $html = '<div  id="active_div' . $admin_users->user->id . '"   style="display:inline-block" >
                                                <a class="label label-success" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 2);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Active</a> </div>';
                    $html = $html . '<div id="blocked_div' . $admin_users->user->id . '"  style="display:none;"  >
                                                <a class="label label-danger" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Blocked</a> </div>';
                }
////                            return ($regsiter_user->user_status > 0) ? 'Active' : 'Inactive';
                return $html;
            })
            ->addColumn('created_at', function($regsiter_user) {
                return $regsiter_user->user->created_at;
            })
            ->addColumn('rating', function($regsiter_user) {
                return $regsiter_user->rating!=''?$regsiter_user->rating:0;
            })
            ->make(true);
    }

    public function deleteRegisteredUser($user_id) {
        $user = User::find($user_id);
        $cluster_dispensary = ClusterDispencery::where('dispencery_id',$user->id)->first();
        if ($user && $cluster_dispensary=='') {
            $user->email = $user->id.'example@example.com';
            $user->is_delete = 1;
            $user->save();

            return redirect('admin/manage-dispensary-user')->with('delete-user-status', 'User has been deleted successfully!');
        } else {
            return redirect("admin/manage-dispensary-user")->with('status',"Can't delete, this dispensary already added in a cluster");
        }
    }

    public function deleteSelectedRegisteredUser($user_id) {
        $user = User::find($user_id);
        $cluster_dispensary = ClusterDispencery::where('dispencery_id',$user->id)->first();
        if ($user && $cluster_dispensary=='') {
            $user->email = $user->id.'example@example.com';
            $user->is_delete = 1;
            $user->save();
            echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
        } else {
            echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
        }
    }

    public function updateRegisteredUser(Request $request, $user_id) {
        $arr_user_data = User::find($user_id);

        if ($arr_user_data) {
            if ($request->method() == "GET") {

                $states  = State::translatedIn(\App::getLocale())->get();
                $cities  = City::translatedIn(\App::getLocale())->get();
                $all_roles = Role::where('level', "<=", 1)->where('slug', '<>', 'superadmin')->get();
                return view("admin::edit-registered-user", array('user_info' => $arr_user_data, 'roles' => $all_roles,'states'=>$states,'cities'=>$cities));
            } elseif ($request->method() == "POST") {


                $data = $request->all();

                $validate_response = Validator::make($data, array(
//                            'gender' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'dispensary_name' => 'required',
                    'user_mobile' => 'numeric',
                    'address' => 'required',
                    'post_code' => 'required|numeric|between:99,999999',
                    'city' => 'required',
                    'state' => 'required',
                ),array('post_code.between'=>'Please enter valid post code')
                );

                if ($validate_response->fails()) {
                    return redirect('admin/update-registered-user/' . $arr_user_data->id)
                        ->withErrors($validate_response)
                        ->withInput();
                } else {/** user information goes here *** */

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
                    if (isset($data["dispensary_name"])) {
                        $arr_user_data->userInformation->dispensary_name = $data["dispensary_name"];
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
                        $arr_user_data->userInformation->lng = $request->longitude;
                    }

                    $arr_user_data->userInformation->save();
                    $success_msg = "Dispensary profile has been updated successfully!";
                    return redirect("admin/update-dispensary-user/" . $arr_user_data->id)->with("profile-updated", $success_msg);
                }
            }
        } else {
            return redirect("admin/manage-dispensary-user");
        }
    }

    protected function updateRegisteredUserEmailInfo(Request $data, $user_id) {
        $data_values = $data->all();
        if (Auth::user()) {
            $arr_user_data = User::find($user_id);
            $validate_response = Validator::make($data_values, array(
                'email' => 'required|email|max:500|unique:users',
                'confirm_email' => 'required|email|same:email',
            ));

            if ($validate_response->fails()) {
                return redirect('admin/update-dispencery-user/' . $user_id)
                    ->withErrors($validate_response)
                    ->withInput();
            } else {
                //updating user email
                $arr_user_data->email = $data->email;
                $arr_user_data->save();

                //updating user status to inactive
                $arr_user_data->userInformation->user_status = 0;
                $arr_user_data->userInformation->save();
                //sending email with verification link
                //sending an email to the user on successfull registration.
                $site_email=GlobalValues::get('site-email');
                $site_title=GlobalValues::get('site-title');
                $arr_keyword_values = array();
                $activation_code = $this->generateReferenceNumber();
                //Assign values to all macros
                $arr_keyword_values['FIRST_NAME'] = $arr_user_data->userInformation->first_name;
                $arr_keyword_values['LAST_NAME'] = $arr_user_data->userInformation->last_name;
                $arr_keyword_values['VERIFICATION_LINK'] = url('verify-user-email/' . $activation_code);
                $arr_keyword_values['SITE_TITLE'] =  $site_title;
                // updating activation code                 
                $arr_user_data->userInformation->activation_code = $activation_code;
                $arr_user_data->userInformation->save();
                $email_template = EmailTemplate::where("template_key",'email-change')->first();

                Mail::send('EmailTemplate::email-change', $arr_keyword_values, function ($message) use ($arr_user_data,$site_email,$site_title,$email_template) {

                    $message->to($arr_user_data->email)->subject($email_template->subject)->from($site_email,$site_title);
                });

                $succes_msg = "Dispensary email has been updated successfully!";
                return redirect("admin/update-dispensary-user/" . $user_id)->with("profile-updated", $succes_msg);
            }
        } else {
            $errorMsg = "Error! Something is wrong going on.";
            Auth::logout();
            return redirect("admin/login")->with("issue-profile", $errorMsg);
        }
    }

    protected function updateRegisteredUserPasswordInfo(Request $data, $user_id) {
        $data_values = $data->all();
        if (Auth::user()) {
            $arr_user_data = User::find($user_id);
            $validate_response = Validator::make($data_values, array(

                'new_password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:new_password',
            ));

            if ($validate_response->fails()) {
                return redirect("admin/update-dispensary-user/" . $user_id)
                    ->withErrors($validate_response)
                    ->withInput();
            } else {
                //updating user Password
                $arr_user_data->password = $data->new_password;
                $arr_user_data->save();
                $succes_msg = "Dispensary password has been updated successfully!";
                return redirect("admin/update-dispensary-user/" . $user_id)->with("profile-updated", $succes_msg);

            }
        } else {
            $errorMsg = "Error! Something wrong is going on.";
            Auth::logout();
            return redirect("login")->with("issue-profile", $errorMsg);
        }
    }

    public function createRegisteredUser(Request $request) {
        if ($request->method() == "GET") {
            $states = $all_states = State::translatedIn(\App::getLocale())->get();
            return view("admin::create-registered-user",['states'=>$states]);
        } elseif ($request->method() == "POST") {
            $data = $request->all();
//            dd($data);
            $validate_response = Validator::make($data, array(
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:6',
                'password_confirmation'=>'required|min:6|same:password',
                'latitude' => 'required',
                'closing_minut' => 'required',
                'opening_minut' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'dispensary_name' => 'required',
                'state'=>'required',
                'city'=>'required',
                'photo'=>'required',
                'post_code'=>'required|numeric|between:99,999999',
                'address'=>'required',
                'user_birth_date'=>'required',
                'address'=>'required',
                'user_mobile' => 'required|numeric',
            ),array('user_mobile.min'=>'Please enter valid mobile number.','post_code.between'=>'Please enter valid post code','user_birth_date'=>'Please select date of birth.')
            );

            if ($validate_response->fails()) {

                return redirect()->back()
                    ->withErrors($validate_response)
                    ->withInput();
            } else {

                $created_user = User::create(array(
                    'email' => $data['email'],
                    'password' => $data['password'],
                ));


                // update User Information

                /*
                 * Adjusted user specific columns, which may not passed on front end and adjusted with the default values
                 */
                $data["user_type"] = isset($data["user_type"]) ? $data["user_type"] : "2";    // 1 may have several mean as per enum stored in the database. Here we 
                // took 1 means one of the front end registered users													


                $data["user_status"] = isset($data["user_status"]) ? $data["user_status"] : "1";  // 0 means not active

                $data["gender"] = isset($data["gender"]) ? $data["gender"] : "3";       // 3 means not specified

                $data["facebook_id"] = isset($data["facebook_id"]) ? $data["facebook_id"] : "";
                $data["twitter_id"] = isset($data["twitter_id"]) ? $data["twitter_id"] : "";
                $data["google_id"] = isset($data["google_id"]) ? $data["google_id"] : "";
                $data["linkedin_id"] = isset($data["linkedin_id"]) ? $data["linkedin_id"] : "";
                $data["pintrest_id"] = isset($data["pintrest_id"]) ? $data["pintrest_id"] : "";
                $data["first_name"] = isset($data["first_name"]) ? $data["first_name"] : "";
                $data["last_name"] = isset($data["last_name"]) ? $data["last_name"] : "";
                $data["about_me"] = isset($data["about_me"]) ? $data["about_me"] : "";
                $data["user_phone"] = isset($data["user_phone"]) ? $data["user_phone"] : "";
                $data["user_mobile"] = isset($data["user_mobile"]) ? $data["user_mobile"] : "";
                $arr_userinformation = array();

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
                $arr_userinformation["dispensary_name"] = $data["dispensary_name"];
                $arr_userinformation["about_me"] = $data["about_me"];
                $arr_userinformation["user_phone"] = $data["user_phone"];
                $arr_userinformation["user_mobile"] = $data["user_mobile"];
                $arr_userinformation["user_status"] = $data["user_status"];
                $arr_userinformation["user_type"] = $data["user_type"];
                $arr_userinformation["opening_hour"] = $request->opening_hour;
                $arr_userinformation["opening_minut"] = $request->opening_minut;
                $arr_userinformation["opening"] = $request->opening;
                $arr_userinformation["closing_hour"] = $request->closing_hour;
                $arr_userinformation["closing_minut"] = $request->closing_minut;
                $arr_userinformation["closing"] = $request->closing;
                $arr_userinformation["lat"] = $request->latitude;
                $arr_userinformation["lng"] = $request->longitude;
                $arr_userinformation["user_id"] = $created_user->id;
                $arr_userinformation["state_id"] = $request->state;
                $arr_userinformation["city_id"] = $request->city;
                $arr_userinformation["post_code"] = $request->post_code;
                $arr_userinformation["address"] = $request->address;

                if($request->hasFile('photo'))
                {

                    $uploaded_file = $request->file('photo');


                    $extension = $uploaded_file->getClientOriginalExtension();

                    $new_file_name = time().".".$extension;

                    Storage::put('public/dispencery/'.$new_file_name,file_get_contents($uploaded_file->getRealPath()));

                    $arr_userinformation["profile_picture"]  = $new_file_name;
                }

                $updated_user_info = UserInformation::create($arr_userinformation);

                $created_user->attachRole('2');

                $created_user->save();

                $arr_keyword_values = array();
                $activation_code = $this->generateReferenceNumber();
                //Assign values to all macros
                $arr_keyword_values['FIRST_NAME'] = $updated_user_info->first_name;
                $arr_keyword_values['LAST_NAME'] = $updated_user_info->last_name;
                $arr_keyword_values['PASSWORD'] = $data['password'];
                $arr_keyword_values['EMAIL'] = $created_user->email;
                $arr_keyword_values['VERIFICATION_LINK'] = url('verify-user-email/' . $activation_code);
                // updating activation code
                $updated_user_info->activation_code = $activation_code;
                $updated_user_info->save();

                $email_template = EmailTemplate::where("template_key",'registration-successfull')->first();

                $email_data[0]['view'] = 'EmailTemplate::registration-successfull-dispensary';
                $email_data[0]['keywords'] = $arr_keyword_values;
                $email_data[0]['to'] = $created_user->email;
                $email_data[0]['subject'] = $email_template->subject;

                $this->dispatch(new sendEmail($email_data));

//                Mail::send('EmailTemplate::registration-successfull-dispensary', $arr_keyword_values, function ($message) use ($created_user,$email_template,$site_email,$site_title) {
//
//                    $message->to($created_user->email, $created_user->name)->subject($email_template->subject)->from($site_email,$site_title);
//                });

                return redirect('admin/manage-dispensary-user')
                    ->with("update-user-status", "Dispensary has been created successfully");
            }
        }
    }

    public function editUser(Request $request, $user_id) {
        $user_details = User::find($user_id);

        if ($user_details) {

            if ($request->method() == "GET") {

                if ($user_details->level() <= 1) {
                    // he is admin user, redirect to admin update page
                    return redirect('admin/update-admin-user/' . $user_id);
                }

                return view("admin::edit-user", array('userdata' => $user_details));
            } elseif ($request->method() == "POST") {
                $data = $request->all();

                $validate_response = Validator::make($data, array(
                    'email' => 'required|email|max:255|unique:users,email,' . $user_details->id,
                    'gender' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'user_mobile' => 'numeric',
                ));

                if ($validate_response->fails()) {
                    return redirect('admin/update-user/' . $user_details->id)
                        ->withErrors($validate_response)
                        ->withInput();
                } else {
                    $user_details->email = $request->email;

                    $user_details->userInformation->first_name = $request->first_name;
                    $user_details->userInformation->last_name = $request->last_name;
                    $user_details->userInformation->gender = $request->gender;
                    $user_details->userInformation->user_birth_date = $request->user_birth_date;
                    $user_details->userInformation->about_me = $request->about_me;
                    $user_details->userInformation->user_phone = $request->user_phone;
                    $user_details->userInformation->user_mobile = $request->user_mobile;
                    $user_details->userInformation->user_type = $request->user_type;

                    $user_details->save();
                    $user_details->userInformation->save();

                    return redirect('admin/update-user/' . $user_details->id)
                        ->with("update-user-status", "User updated successfully");
                }
            }
        } else {
            return redirect("admin/manage-users");
        }
    }

    public function editUserPassword(Request $request, $user_id) {
        $user_details = User::find($user_id);

        if ($user_details) {
            $data = $request->all();
            $validate_response = Validator::make($data, [

                'new_password' => 'required|min:6|confirmed',
            ], [
                    'new_password.required' => 'Please enter new password',
                    'new_password.min' => 'Please enter atleast 6 characters',
                    'new_password.confirmed' => 'Confirmation password doesn\'t match',
                ]
            );

            $return_url = 'admin/update-user/' . $user_details->id;

            if ($user_details->level() <= 1) {
                $return_url = 'admin/update-admin-user/' . $user_details->id;
            }

            if ($validate_response->fails()) {
                return redirect($return_url)
                    ->withErrors($validate_response)
                    ->withInput();
            } else {

                $user_details->password = $request->new_password;
                $user_details->save();

                return redirect($return_url)
                    ->with("update-user-status", "User's password updated successfully");
            }
        } else {
            return redirect()->back();
        }
    }

    public function editUserStatus(Request $request, $user_id) {
        $user_details = User::find($user_id);

        if ($user_details) {
            $user_details->userInformation->user_status = $request->active_status;
            $user_details->userInformation->save();

            $return_url = 'admin/update-user/' . $user_details->id;

            if ($user_details->level() <= 1) {
                $return_url = 'admin/update-admin-user/' . $user_details->id;
            }

            return redirect($return_url)
                ->with("update-user-status", "User's status updated successfully");
        } else {
            return redirect()->back();
        }
    }

    public function deletAdminUser($user_id) {
        $user = User::find($user_id);

        if ($user) {
            $user->delete();

            return redirect('admin/admin-users')->with('delete-user-status', 'admin user has been deleted successfully!');
        } else {
            return redirect("admin/admin-users");
        }
    }

    public function deletSelectedAdminUser($user_id) {
        $user = User::find($user_id);

        if ($user) {
            $user->delete();
            echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
        } else {
            echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
        }
    }

    public function listRoles() {

        return view("admin::list-roles");
    }

    public function listRolesData() {
        $role_list = Role::all();
        $role_listing = $role_list->reject(function ($role) {
            return ($role->slug == "superadmin") == true;
        });
        return Datatables::collection($role_listing)->make(true);
    }

    public function updateRole(Request $request, $role_id) {

        $role = Role::find($role_id);
        if ($role) {
            if ($request->method() == "GET") {
                return view('admin::edit-role', ['role' => $role]);
            } else {
                $data = $request->all();
                $data['slug']=str_slug($request->slug);
                $validate_response = Validator::make($data, [
                    'name' => 'required'
                ], [
                        'slug.required' => 'Please enter slug for role',
                        'slug.unique' => 'The entered slug is already in use. Please try another',
                        'name.required' => 'Please enter name'
                    ]
                );

                if ($validate_response->fails()) {
                    return redirect('admin/update-role/' . $role->id)
                        ->withErrors($validate_response)
                        ->withInput();
                } else {

                    $role->name = $request->name;
                    $role->slug = str_slug($request->slug);
                    $role->description = $request->description;
                    $role->level = $request->level;
                    $role->save();

                    return redirect('admin/manage-roles')
                        ->with("update-role-status", "Role informations has been updated successfully");
                }
            }
        } else {
            return redirect('admin/manage-roles');
        }
    }

    public function createRole(Request $request) {
        if ($request->method() == "GET") {
            return view('admin::create-role');
        } else {
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'slug' => 'required|unique:roles',
                'name' => 'required'
            ], [
                    'slug.required' => 'Please enter slug for role',
                    'slug.unique' => 'The entered slug is already in use. Please try another',
                    'name.required' => 'Please enter name'
                ]
            );

            if ($validate_response->fails()) {
                return redirect('admin/roles/create')
                    ->withErrors($validate_response)
                    ->withInput();
            } else {

                $role['name'] = $request->name;
                $role['slug'] = str_slug($request->slug);
                $role['description'] = $request->description;
                $role['level'] = $request->level;

                Role::create($role);

                return redirect('admin/manage-roles/')
                    ->with("role-status", "Role created successfully");
            }
        }
    }

    public function updateRolePermissions(Request $request, $role_id) {
        $role = Role::find($role_id);

        if ($role) {
            if ($request->method() == "GET") {
                $all_permissions = Permission::orderBy('model')->get();

                $role_permissions = $role->permissions;

                return view("admin::role-permissions", array('role' => $role, 'permissions' => $all_permissions, 'role_permissions' => $role_permissions));
            } else {
                $role->detachAllPermissions();
                $role->save();
                if (count($request->permission) > 0) {
                    foreach ($request->permission as $sel_permission) {
                        $role->attachPermission($sel_permission);
                    }

                    $role->save();
                }

                return redirect('admin/manage-roles')
                    ->with("set-permission-status", "Role permissions has been updated successfully");
            }
        } else {
            return redirect('admin/manage-roles');
        }
    }

    public function deleteRole($role_id) {


        $role = Role::find($role_id);
        $role_user_count=RoleUser::find($role_id);
        if(count($role_user_count)==0)
        {
            $role->delete();
            return redirect('admin/manage-roles/')
                ->with("delete-role-status", "Role has been deleted successfully");
        } else {
            return redirect('admin/manage-roles')
                ->with("delete-role-status", "Role has Not deleted because User already available for this Role");
        }
    }

    public function deleteRoleFromSelectAll($role_id) {

        $role = Role::find($role_id);
        if ($role) {
            $role->delete();
            echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
        } else {
            echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
        }
    }

    public function listGlobalSettings() {
        return view("admin::list-global-settings");
    }

    public function listGlobalSettingsData() {
        $global_settings = GlobalSetting::all();
        return Datatables::of($global_settings)
            ->addColumn('name', function($global) {
                return $value = $global->name;
            })
            ->addColumn('value', function($global) {
                $value = '';
                if ($global->slug == 'site-logo') {
                    $value = '<img width="100" src="' . asset("/storageasset/global-settings/$global->value") . '">';
                } else {
                    $value = $global->value;
                }
                return $value;
            })
            ->make(true);
    }

    public function updateGlobalSetting(Request $request, $setting_id) {

        $global_setting = GlobalSetting::find($setting_id);
        if ($global_setting) {
            if ($request->method() == "GET") {
                return view("admin::edit-global-settings", array('setting' => $global_setting));
            } else {
                $data = $request->all();
                if(count($request->file())>0){
                    $validate_response = Validator::make($data, array(
                            'value' => 'mimes:jpeg,bmp,png,mp4,avi,3gp',
                        )
                    );
                }else {
                    $validate_response = Validator::make($data, array(
                            'value' => $global_setting->validate,
                        )
                    );
                }

                if ($setting_id == 16) {
                    Validator::extend('phone_number', function($attribute, $value, $parameters, $validator) {
                        return $value > 0;
                    });

                    $validate_response = Validator::make($data, array(
                        'value' => 'required|numeric|min:1|digits_between:10,12'
                    ), array(
                        'value.min' => 'Please enter valid phone number.',
                        'value.digits_between' => 'Please enter phone number between 10 to 12 digits.'
                    ));
                    if ($validate_response->fails()) {
                        return redirect('/admin/update-global-setting/' . $global_setting->id)->withErrors($validate_response)->withInput();
                    }
                }

                if ($validate_response->fails()) {
                    return redirect('/admin/update-global-setting/' . $global_setting->id)->withErrors($validate_response)->withInput();
                } else {

                    if (in_array("image", explode("|", $global_setting->validate))) {
                        if(file_exists(base_path().'/storage/app/public/global-settings/'.$global_setting->value)){
                            unlink(base_path().'/storage/app/public/global-settings/'.$global_setting->value);
                        }
                        $extension = $request->file('value')->getClientOriginalExtension();

                        $new_file_name = time() . "." . $extension;
                        Storage::put('public/global-settings/' . $new_file_name, file_get_contents($request->file('value')->getRealPath()));

                        $global_setting->value = $new_file_name;
                    } else {
                        $global_setting->value = $request->value;
                    }

                    $global_setting->save();
                    Cache::forget($global_setting->slug);
                    return redirect('/admin/global-settings')->with('update-setting-status', 'Global setting info has been updated successfully!');
                }
            }
        } else {
            return redirect('admin/global-settings');
        }
    }

    public function listAdminUsers() {

        return view("admin::list-admin-users");
    }

    public function listAdminUsersData() {
        $all_users = UserInformation::all();
        $all_users=$all_users->sortByDesc('id');
        $admin_users = $all_users->reject(function ($user) {

            return $user->user->hasRole('superadmin') || ($user->user_type > 1);
        });

        return Datatables::of($admin_users)
            ->addColumn('first_name', function($regsiter_user) {
                return $regsiter_user->first_name;
            })
            ->addColumn('last_name', function($regsiter_user) {
                return $regsiter_user->last_name;
            })
            ->addColumn('email', function($admin_users) {
                return $admin_users->user->email;
            })
            ->addColumn('role', function($admin_users) {
                $role = "";
                if (isset($admin_users->user->getRoles()->first()->name)) {
                    $role = $admin_users->user->getRoles()->first()->name;
                }
                return $role;
            })
            ->addColumn('status', function($admin_users) {

                $html = '';
                if ($admin_users->user_status == 0) {
                    $html = '<div  id="active_div' . $admin_users->user->id . '"    style="display:none;"  >
                                                <a class="label label-success" title="Click to Change UserStatus" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 2);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Active</a> </div>';
                    $html = $html . '<div id="inactive_div' . $admin_users->user->id . '"  style="display:inline-block" >
                                                <a class="label label-warning" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Inactive </a> </div>';
                    $html = $html . '<div id="blocked_div' . $admin_users->user->id . '" style="display:none;"  >
                                                <a class="label label-danger" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Blocked </a> </div>';
                } else if ($admin_users->user_status == 2) {
                    $html = '<div  id="active_div' . $admin_users->user->id . '"  style="display:none;" >
                                                <a class="label label-success" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 2);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Active</a> </div>';
                    $html = $html . '<div id="blocked_div' . $admin_users->user->id . '"    style="display:inline-block" >
                                                <a class="label label-danger" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Blocked</a> </div>';
                } else {//
                    $html = '<div  id="active_div' . $admin_users->user->id . '"   style="display:inline-block" >
                                                <a class="label label-success" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 2);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Active</a> </div>';
                    $html = $html . '<div id="blocked_div' . $admin_users->user->id . '"  style="display:none;"  >
                                                <a class="label label-danger" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Blocked</a> </div>';
                }
                return $html;
            })
            ->addColumn('created_at', function($admin_users) {
                return $admin_users->user->created_at;
            })
            ->make(true);
    }

    public function changeUserStatus(Request $request) {
        $data = $request->all();

        $user_details = UserInformation::where('user_id', '=', $data['user_id'])->first();
        //
        if ($user_details) {

            $user_details->user_status = $data['user_status'];
            $user_details->save();
            echo json_encode(array("error" => "0", "message" => "Account status has been changed successfully"));

        }


    }
    public function updateAdminUser(Request $request, $user_id) {
        $arr_user_data = User::find($user_id);
        if ($arr_user_data) {
            if ($request->method() == "GET") {
                $all_roles = Role::where('level', "<=", 1)->where('slug', '<>', 'superadmin')->get();

                return view("admin::edit-admin-user", array('user_info' => $arr_user_data, 'roles' => $all_roles));
            } elseif ($request->method() == "POST") {
                $data = $request->all();
                $validate_response = Validator::make($data, array(
                    'gender' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'role' => 'required|numeric',
                    'user_status' => 'required|numeric',
                    'user_mobile' => 'numeric',
                ), array(
                        'role.numeric' => 'Invalid Role! Please reselect',
                        'user_mobile.min' =>'enter valid user mobile number.'
                    )
                );
                if ($validate_response->fails()) {
                    return redirect('admin/update-admin-user/' . $arr_user_data->id)
                        ->withErrors($validate_response)
                        ->withInput();
                } else {/** user information goes here *** */
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
                    if (isset($data["about_me"])) {
                        $arr_user_data->userInformation->about_me = $data["about_me"];
                    }

                    if (isset($data["user_mobile"])) {
                        $arr_user_data->userInformation->user_mobile = $data["user_mobile"];
                    }
                    $arr_user_data->detachAllRoles();
                    $arr_user_data->attachRole($request->role);
                    $arr_user_data->userInformation->save();
//                    $succes_msg = "Admin user profile has been updated successfully!";
                    $succes_msg = "Sub user profile has been updated successfully!";
                    return redirect("admin/update-admin-user/" . $arr_user_data->id)->with("profile-updated", $succes_msg);
                }
            }
        } else {
            return redirect("admin/manage-admin-users");
        }
    }

    public function createUser(Request $request, $is_admin = false) {

        if ($request->method() == "GET") {

            $all_roles = Role::where('slug', '<>', 'superadmin')->get();
            $filtered_reg_role = $all_roles->filter(function($value, $key) {
                return $value->slug == 'registered.user';
            })->first();
            $role_id_registered_users = $filtered_reg_role;

            return view("admin::create-admin-user", array('roles' => $all_roles, 'is_admin' => $is_admin, 'role_id_register' => $role_id_registered_users));
        } elseif ($request->method() == "POST") {
            $data = $request->all();
            $validate_response = Validator::make($data, array(
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:6',
                'password_confirmation' => 'required|same:password',
                'gender' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'role' => 'required|numeric',
                'user_mobile' => 'numeric',
            ), array(
                    'role.numeric' => 'Invalid Role! Please reselect',
                    'user_mobile.min' =>'enter valid user mobile number.',
                    'password_confirmation.same'=>'Password and Confirm Password Must be same'
                )
            );
            if ($validate_response->fails()) {
                return redirect()->back()
                    ->withErrors($validate_response)
                    ->withInput();
            } else {
                $created_user = User::create(array(
                    'email' => $data['email'],
                    'password' => ($data['password']),
                ));


                // update User Information

                /*
                 * Adjusted user specific columns, which may not passed on front end and adjusted with the default values
                 */
                $data["user_type"] = isset($data["user_type"]) ? $data["user_type"] : "1";    // 1 may have several mean as per enum stored in the database. Here we 
                // took 1 means one of the front end registered users													


                $data["user_status"] = isset($data["user_status"]) ? $data["user_status"] : "0";  // 0 means not active

                $data["gender"] = isset($data["gender"]) ? $data["gender"] : "3";       // 3 means not specified

                $data["profile_picture"] = isset($data["profile_picture"]) ? $data["profile_picture"] : "";
                $data["facebook_id"] = isset($data["facebook_id"]) ? $data["facebook_id"] : "";
                $data["twitter_id"] = isset($data["twitter_id"]) ? $data["twitter_id"] : "";
                $data["google_id"] = isset($data["google_id"]) ? $data["google_id"] : "";
                $data["linkedin_id"] = isset($data["linkedin_id"]) ? $data["linkedin_id"] : "";
                $data["pintrest_id"] = isset($data["pintrest_id"]) ? $data["pintrest_id"] : "";
                $data["user_birth_date"] = isset($data["user_birth_date"]) ? $data["user_birth_date"] : "";
                $data["first_name"] = isset($data["first_name"]) ? $data["first_name"] : "";
                $data["last_name"] = isset($data["last_name"]) ? $data["last_name"] : "";
                $data["about_me"] = isset($data["about_me"]) ? $data["about_me"] : "";
                $data["user_phone"] = isset($data["user_phone"]) ? $data["user_phone"] : "";
                $data["user_mobile"] = isset($data["user_mobile"]) ? $data["user_mobile"] : "";
                $arr_userinformation = array();
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
                $arr_userinformation["user_status"] = $data["user_status"];
                $arr_userinformation["user_type"] = $data["user_type"];
                $arr_userinformation["user_id"] = $created_user->id;

                $updated_user_info = UserInformation::create($arr_userinformation);

                $created_user->attachRole($request->role);

                $created_user->save();
                $site_email=GlobalValues::get('site-email');
                $site_title=GlobalValues::get('site-title');
                $arr_keyword_values = array();
                $activation_code = $this->generateReferenceNumber();
                //Assign values to all macros
                $arr_keyword_values['FIRST_NAME'] = $updated_user_info->first_name;
                $arr_keyword_values['LAST_NAME'] = $updated_user_info->last_name;
                $arr_keyword_values['VERIFICATION_LINK'] = url('verify-user-email/' . $activation_code);
                $arr_keyword_values['SITE_TITLE'] =  $site_title;
                // updating activation code                 
                $updated_user_info->activation_code = $activation_code;
                $updated_user_info->save();
                $email_template = EmailTemplate::where("template_key",'admin-registration-successfull')->first();
                Mail::send('EmailTemplate::admin-registration-successfull', $arr_keyword_values, function ($message) use ($created_user,$email_template,$site_email,$site_title) {

                    $message->to($created_user->email, $created_user->name)->subject($email_template->subject)->from($site_email,$site_title);
                });

                return redirect('admin/admin-users')
//                    ->with("update-user-status", "Admin user has been created successfully");
                    ->with("update-user-status", "Sub user has been created successfully");
            }
        }
    }

    public function resendVerificationLink(Request $request, $user_id = 0)
    {
        $updated_user_info=  UserInformation::where('user_id',$user_id)->first();
        //Assign values to all macros
        $site_email = GlobalValues::get('site-email');
        $site_title = GlobalValues::get('site-title');
        $activation_code = $this->generateReferenceNumber();
        //Assign values to all macros

        // updating activation code
        $updated_user_info->activation_code = $activation_code;
        $updated_user_info->save();
        $arr_keyword_values['FIRST_NAME'] = $updated_user_info->first_name;
        $arr_keyword_values['LAST_NAME'] = $updated_user_info->last_name;
        $arr_keyword_values['VERIFICATION_LINK'] = url('verify-user-email/' . $updated_user_info->activation_code);
        $arr_keyword_values['SITE_TITLE'] =  $site_title;
        $email_template = EmailTemplate::where("template_key",'admin-registration-successfull')->first();

        Mail::send('EmailTemplate::admin-registration-successfull', $arr_keyword_values, function ($message) use ($updated_user_info,$email_template,$site_email, $site_title) {

            $message->to($updated_user_info->user->email, $updated_user_info->name)->subject($email_template->subject)->from($site_email,$site_title);
        });
        return redirect('admin/admin-users')
            ->with("update-user-status", "Verifiation email has been resent successfully");

    }
    public function listCountries() {

        return view('admin::list-countries');
    }

    public function listCountriesData() {

        $all_countries = Country::translatedIn(\App::getLocale())->get();
        $all_countries=$all_countries->sortByDesc('id');

        return Datatables::collection($all_countries)
            ->addColumn('Language', function($country) {
                $language = '<button class="btn btn-sm btn-warning dropdown-toggle" type="button" id="langDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Another Language <span class="caret"></span> </button>
                         <ul class="dropdown-menu multilanguage" aria-labelledby="langDropDown">';
                if (count(config("translatable.locales_to_display"))) {
                    foreach (config("translatable.locales_to_display") as $locale => $locale_full_name) {
                        if ($locale != 'en') {
                            $language.='<li class="dropdown-item"> <a href="update-language/' . $country->id . '/' . $locale . '">' . $locale_full_name . '</a></li>';
                        }
                    }
                }
                return $language;
            })->make(true);
    }

    public function createCountry(Request $request) {
        if ($request->method() == "GET") {
            return view("admin::create-country");
        } else {
            // validate and proceed
            $data = $request->all();
            $data['name']=  trim($data['name']);
            $validate_response = Validator::make($data, array(
                'name' => 'required|unique:country_translations,name',
            ));

            if ($validate_response->fails()) {
                return redirect()->back()->withErrors($validate_response)->withInput();
            } else {
                $country = Country::create();
                $en_country = $country->translateOrNew(\App::getLocale());

                $en_country->name = $request->name;
                $en_country->country_id = $country->id;
                $en_country->save();

                return redirect('admin/countries/list')->with('country-status', 'Country has been created Successfully!');
            }
        }
    }

    public function updateCountry(Request $request, $country_id) {
        $country = Country::find($country_id);

        if ($country) {

            $is_new_entry = !($country->hasTranslation());

            $translated_country = $country->translate();

            if ($request->method() == "GET") {
                return view("admin::update-country", array('country_info' => $translated_country));
            } else {
                // validate and proceed
                $data = $request->all();
                $validate_response = Validator::make($data, array(
                    'name' => 'required|unique:country_translations,name,' . $translated_country->id,
                ));

                if ($validate_response->fails()) {
                    return redirect()->back()->withErrors($validate_response)->withInput();
                } else {
                    $translated_country->name = $request->name;

                    if ($is_new_entry) {
                        $translated_country->country_id = $country_id;
                    }

                    $translated_country->save();

                    return redirect('admin/countries/list')->with('update-country-status', 'Country has been updated successfully!');
                }
            }
        } else {
            return redirect("admin/countries/list");
        }
    }

    public function updateCountryLanguage(Request $request, $country_id, $locale) {
        $country = Country::find($country_id);

        if ($country) {
            $is_new_entry = !($country->hasTranslation($locale));

            $translated_country = $country->translateOrNew($locale);

            if ($request->method() == "GET") {
                return view("admin::update-country-language", array('country_info' => $translated_country));
            } else {
                // validate and proceed
                $data = $request->all();
                $validate_response = Validator::make($data, array(
                    'name' => 'required',
                ));

                if ($validate_response->fails()) {
                    return redirect()->back()->withErrors($validate_response)->withInput();
                } else {
                    $translated_country->name = $request->name;

                    if ($is_new_entry) {
                        $translated_country->country_id = $country_id;
                    }

                    $translated_country->save();

                    return redirect('admin/countries/list')->with('update-country-status', 'Country updated successfully!');
                }
            }
        } else {
            return redirect("admin/countries/list");
        }
    }

    public function deleteCountry($country_id) {
        $country = Country::find($country_id);

        if ($country) {
            $country->delete();

            return redirect('admin/countries/list')->with('country-status', 'Country has been deleted successfully!');
        } else {
            return redirect("admin/countries/list");
        }
    }

    public function deleteCountrySelected($country_id) {
        $country = Country::find($country_id);

        if ($country) {
            $country->delete();
            echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
        } else {
            echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
        }
    }

    public function listStates() {
        return view('admin::list-states');
    }

    public function getAllStatesByCountry($country_id) {
        $states = State::where('country_id', $country_id)->translatedIn(\App::getLocale())->get();
        $select_value = '<option value="">--Select--</option>';
        if ($states) {
            foreach ($states as $key => $value) {

                $select_value.='<option value="' . $value->id . '">' . $value->name . '</option>';
            }
        }
        echo $select_value;
        exit;
    }

    public function getAllCitiesByState(Request $request, $state_id) {

        $cities = City::where('state_id', $state_id)->translatedIn(\App::getLocale())->get();
        $select_value = '<option value="">--Select--</option>';
        if ($cities) {
            foreach ($cities as $key => $value) {

                $select_value.='<option value="' . $value->id . '" >' . $value->name . '</option>';
            }
        }
        echo $select_value;
        exit;
    }

    public function listStatesData() {
        $all_states = State::translatedIn(\App::getLocale())->get();
        //return Datatables::collection($all_states)->make(true);
        $all_states=$all_states->sortByDesc('id');
        return Datatables::of($all_states)
            ->addColumn('Language', function($city) {
                $language = '<button class="btn btn-sm btn-warning dropdown-toggle" type="button" id="langDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Another Language <span class="caret"></span> </button>
                         <ul class="dropdown-menu multilanguage" aria-labelledby="langDropDown">';
                if (count(config("translatable.locales_to_display"))) {
                    foreach (config("translatable.locales_to_display") as $locale => $locale_full_name) {
                        if ($locale != 'en') {
                            $language.='<li class="dropdown-item"> <a href="update-language/' . $city->id . '/' . $locale . '">' . $locale_full_name . '</a></li>';
                        }
                    }
                }
                return $language;
            })
            ->addColumn('country', function($state) {
                return $state->country->translate()->name;
            })
            ->make(true);
    }

    public function createState(Request $request) {
        if ($request->method() == "GET") {
            $all_countries = Country::translatedIn(\App::getLocale())->get();
            return view("admin::create-state", array('countries' => $all_countries));
        } else {
            // validate and proceed
            $data = $request->all();
            $validate_response = Validator::make($data, array(
                'name' => 'required',
            ));

            if ($validate_response->fails()) {
                return redirect()->back()->withErrors($validate_response)->withInput();
            } else {

                $state = State::create(['country_id' => 1]);

                $en_state = $state->translateOrNew(\App::getLocale());

                $en_state->name = $request->name;
                $en_state->state_id = $state->id;
                $en_state->save();

                return redirect('admin/states/list')->with('state-status', 'State Created Successfully!');
            }
        }
    }

    public function updateState(Request $request, $state_id) {
        $state = State::find($state_id);

        if ($state) {
            $is_new_entry = !($state->hasTranslation());

            $translated_state = $state->translate();

            if ($request->method() == "GET") {
                $all_countries = Country::translatedIn(\App::getLocale())->get();
                return view("admin::update-state", array('state_info' => $translated_state, 'state' => $state, 'countries' => $all_countries));
            } else {
                // validate and proceed
                $data = $request->all();
                $validate_response = Validator::make($data, array(
                    'name' => 'required|unique:state_translations,name,' . $translated_state->id,
                ));

                if ($validate_response->fails()) {
                    return redirect()->back()->withErrors($validate_response)->withInput();
                } else {
                    $translated_state->name = $request->name;
                    $state->country_id = 1;

                    if ($is_new_entry) {
                        $translated_state->state_id = $state_id;
                    }

                    $translated_state->save();
                    $state->save();
                    return redirect('admin/states/list')->with('update-state-status', 'States has been updated Successfully!');
                }
            }
        } else {
            return redirect("admin/states/list");
        }
    }

    public function updateStateLanguage(Request $request, $state_id, $locale) {
        $state = State::find($state_id);

        if ($state) {
            $is_new_entry = !($state->hasTranslation($locale));

            $translated_state = $state->translateOrNew($locale);

            if ($request->method() == "GET") {
                return view("admin::update-state-language", array('state_info' => $translated_state));
            } else {
                // validate and proceed
                $data = $request->all();

                $validate_response = Validator::make($data, array(
                    'name' => 'required',
                ));

                if ($validate_response->fails()) {
                    return redirect()->back()->withErrors($validate_response)->withInput();
                } else {
                    $translated_state->name = $request->name;

                    if ($is_new_entry) {
                        $translated_state->state_id = $state_id;
                    }

                    $translated_state->save();

                    return redirect('admin/states/list')->with('update-state-status', 'State has been updated Successfully!');
                }
            }
        } else {
            return redirect("admin/states/list");
        }
    }

    public function deleteState($state_id) {
        $state = State::find($state_id);

        if ($state) {
            $state->delete();
            return redirect('admin/states/list')->with('state-status', 'State deleted successfully!');
        } else {
            return redirect('admin/states/list');
        }
    }

    public function deleteStateSelected($state_id) {
        $state = State::find($state_id);

        if ($state) {
            $state->delete();
            echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
        } else {

            echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
        }
    }

    public function listCities() {

        return view('admin::list-cities');
    }

    public function listCitiesData() {
        $all_cities = City::translatedIn(\App::getLocale())->get();
        $all_cities=$all_cities->sortByDesc('id');
        return Datatables::of($all_cities)
            ->addColumn('Language', function($city) {
                $language = '<button class="btn btn-sm btn-warning dropdown-toggle" type="button" id="langDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Another Language <span class="caret"></span> </button>
                         <ul class="dropdown-menu multilanguage" aria-labelledby="langDropDown">';
                if (count(config("translatable.locales_to_display"))) {
                    foreach (config("translatable.locales_to_display") as $locale => $locale_full_name) {
                        if ($locale != 'en') {
                            $language.='<li class="dropdown-item"> <a href="update-language/' . $city->id . '/' . $locale . '">' . $locale_full_name . '</a></li>';
                        }
                    }
                }
                return $language;
            })
            ->addColumn('country', function($city) {
                return $city->country->translate()->name;
            })
            ->addColumn('state', function($cities) {
                return $cities->state->translate()->name;
            })
            ->make(true);
    }

    public function createCity(Request $request) {
        if ($request->method() == "GET") {
            $countries = Country::translatedIn(\App::getLocale())->get();
            $all_states = State::translatedIn(\App::getLocale())->get();

            return view("admin::create-cities", array('states' => $all_states, "countries" => $countries));
        } else {
            // validate and proceed
            $data = $request->all();
            $validate_response = Validator::make($data, array(
                'name' => 'required',
                'state' => 'required|numeric',
            ));

            if ($validate_response->fails()) {
                return redirect()->back()->withErrors($validate_response)->withInput();
            } else {

                $city = City::create(['state_id' => $request->state, "country_id" => 1]);

                $en_city = $city->translateOrNew(\App::getLocale());

                $en_city->name = $request->name;
                $en_city->city_id = $city->id;
                $en_city->save();

                return redirect('admin/cities/list')->with('city-status', 'City has been created Successfully!');
            }
        }
    }

    public function updateCity(Request $request, $city_id) {
        $city = City::find($city_id);
        $city_values = City::find($city_id)->first();
        $country_id = 0;
        if ($city_values) {
            $country_id = $city_values->country_id;
        }
        if ($city) {
            $is_new_entry = !($city->hasTranslation());

            $translated_city = $city->translate();

            if ($request->method() == "GET") {
                $countries = Country::translatedIn(\App::getLocale())->get();
                $states_info = State::where('country_id', $country_id)->translatedIn(\App::getLocale())->get();
                return view("admin::update-city", array('city' => $city, 'city_info' => $translated_city, 'city' => $city, 'states' => $states_info, 'countries' => $countries));
            } else {
                // validate and proceed
                $data = $request->all();
                $validate_response = Validator::make($data, array(
                    'name' => 'required|unique:city_translations,name,' . $translated_city->id,
                    'state' => 'required',
                ));

                if ($validate_response->fails()) {
                    return redirect()->back()->withErrors($validate_response)->withInput();
                } else {
                    $translated_city->name = $request->name;
                    $city->state_id = $request->state;
                    $city->country_id = 1;

                    $translated_city->save();
                    $city->save();
                    return redirect('admin/cities/list')->with('update-city-status', 'City has been updated successfully!');
                }
            }
        } else {
            return redirect("admin/cities/list");
        }
    }

    public function updateCityLanguage(Request $request, $city_id, $locale) {
        $city = City::find($city_id);

        if ($city) {
            $is_new_entry = !($city->hasTranslation($locale));

            $translated_city = $city->translateOrNew($locale);

            if ($request->method() == "GET") {
                return view("admin::update-city-language", array('city_info' => $translated_city));
            } else {
                // validate and proceed
                $data = $request->all();

                $validate_response = Validator::make($data, array(
                    'name' => 'required',
                ));

                if ($validate_response->fails()) {
                    return redirect()->back()->withErrors($validate_response)->withInput();
                } else {
                    $translated_city->name = $request->name;

                    if ($is_new_entry) {
                        $translated_city->city_id = $city_id;
                    }

                    $translated_city->save();
                    return redirect("admin/cities/list")->with('update-city-status', 'City updated successfully!');
                    //return redirect()->back()->with('update-city-status','City updated successfully!');
                }
            }
        } else {
            return redirect("admin/cities/list");
        }
    }

    public function deleteCity($city_id) {
        $city = City::find($city_id);

        if ($city) {
            $city->delete();
            return redirect('admin/cities/list')->with('city-status', 'City has been deleted successfully!');
        } else {
            return redirect('admin/cities/list');
        }
    }

    public function deleteCitySelected($city_id) {
        $city = City::find($city_id);
        if ($city) {
            $city->delete();
            echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
        } else {
            echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
        }
    }

    private function generateReferenceNumber() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

//function to show the list of patient    
    public function listRegisteredPatient() {
        return view("admin::list-patient");

    }

//function to create the patient    
    public function createPatient(Request $request) {
        if ($request->method() == "GET") {

            return view("admin::create-patient");
        } elseif ($request->method() == "POST") {
            $data = $request->all();
            $validate_response = Validator::make($data, array(
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:6',
                'password_confirmation'=>'required|min:6|same:password',
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',
                'contact' => 'required|numeric',
                'dob' => 'required',

            ));
            if ($validate_response->fails()) {
                return redirect()->back()
                    ->withErrors($validate_response)
                    ->withInput();
            } else {
                $created_user = User::create(array(
                    'email' => $data['email'],
                    'password' => ($data['password']),
                ));


                // update User Information

                /*
                 * Adjusted user specific columns, which may not passed on front end and adjusted with the default values
                 */
                $data["user_type"] = "3";    // 1 may have several mean as per enum stored in the database. Here we 
                // took 1 means one of the front end registered users													


                $data["user_status"] = isset($data["user_status"]) ? $data["user_status"] : "1";  // 0 means not active

                $data["gender"] = isset($data["gender"]) ? $data["gender"] : "3";       // 3 means not specified

                $data["profile_picture"] = isset($data["profile_picture"]) ? $data["profile_picture"] : "";
                $data["facebook_id"] = isset($data["facebook_id"]) ? $data["facebook_id"] : "";
                $data["twitter_id"] = isset($data["twitter_id"]) ? $data["twitter_id"] : "";
                $data["google_id"] = isset($data["google_id"]) ? $data["google_id"] : "";
                $data["linkedin_id"] = isset($data["linkedin_id"]) ? $data["linkedin_id"] : "";
                $data["pintrest_id"] = isset($data["pintrest_id"]) ? $data["pintrest_id"] : "";
                $data["user_birth_date"] = isset($data["dob"]) ? $data["dob"] : "";
                $data["first_name"] = isset($data["first_name"]) ? $data["first_name"] : "";
                $data["last_name"] = isset($data["last_name"]) ? $data["last_name"] : "";
                $data["about_me"] = isset($data["about_me"]) ? $data["about_me"] : "";
                $data["user_mobile"] = isset($data["contact"]) ? $data["contact"] : "";
                $data["user_phone"] = isset($data["contact"]) ? $data["contact"] : "";
                $data["user_name"] = isset($data["user_name"]) ? $data["user_name"] : "";
                $arr_userinformation = array();
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
                $arr_userinformation["user_status"] = $data["user_status"];
                $arr_userinformation["user_type"] = $data["user_type"];
                $arr_userinformation["user_id"] = $created_user->id;
                $arr_userinformation["address"] = $request->address;
                $arr_userinformation["application_id"] = substr(md5(rand()),0,12);

                if($request->hasFile('photo'))
                {

                    $uploaded_file = $request->file('photo');


                    $extension = $uploaded_file->getClientOriginalExtension();

                    $new_file_name = time().".".$extension;

                    Storage::put('public/patient/'.$new_file_name,file_get_contents($uploaded_file->getRealPath()));

                    $arr_userinformation["profile_picture"]  = $new_file_name;
                }

                $updated_user_info = UserInformation::create($arr_userinformation);

                $created_user->attachRole('2');

                $created_user->save();
                $site_email=GlobalValues::get('site-email');
                $site_title=GlobalValues::get('site-title');
                $arr_keyword_values = array();
                //Assign values to all macros
                $arr_keyword_values['FIRST_NAME'] = $updated_user_info->first_name;
                $arr_keyword_values['LAST_NAME'] = $updated_user_info->last_name;
                $arr_keyword_values['EMAIL'] = $data['email'];
                $arr_keyword_values['PASSWORD'] = $data['password'];
                $arr_keyword_values['SITE_TITLE'] =  $site_title;
                // updating activation code                 
                $email_template = EmailTemplate::where("template_key",'registration-successfull')->first();

                Mail::send('EmailTemplate::registration-successfull', $arr_keyword_values, function ($message) use ($created_user,$email_template,$site_email,$site_title) {

                    $message->to($created_user->email, $created_user->name)->subject($email_template->subject)->from($site_email,$site_title);
                });

                return redirect('admin/manage-patient')
                    ->with("update-user-status", "User has been created successfully");
            }
        }
    }
//this is the function for fetching patient data from user information table
    public function listRegisteredPatientData() {
        $all_users = UserInformation::where('user_type','3')->get();
        $all_users = $all_users->filter(function($user){
            return $user->user->is_delete==0;
        });
        $all_users=$all_users->sortByDesc('id');


        return Datatables::of($all_users)
            ->addColumn('user_name', function($regsiter_user) {
                return $regsiter_user->user_name;
            })
            ->addColumn('first_name', function($regsiter_user) {
                return $regsiter_user->first_name;
            })
            ->addColumn('last_name', function($regsiter_user) {
                return $regsiter_user->last_name;
            })
            ->addColumn('email', function($regsiter_user) {
                return $regsiter_user->user->email;
            })
            ->addColumn('status', function($admin_users) {

                $html = '';
                if ($admin_users->user_status == 0) {
                    $html = '<div  id="active_div' . $admin_users->user->id . '"    style="display:none;"  >
                                                <a class="label label-success" title="Click to Change UserStatus" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 2);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Active</a> </div>';
                    $html = $html . '<div id="inactive_div' . $admin_users->user->id . '"  style="display:inline-block" >
                                                <a class="label label-warning" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Inactive </a> </div>';
                    $html = $html . '<div id="blocked_div' . $admin_users->user->id . '" style="display:none;"  >
                                                <a class="label label-danger" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Blocked </a> </div>';
                } else if ($admin_users->user_status == 2) {
                    $html = '<div  id="active_div' . $admin_users->user->id . '"  style="display:none;" >
                                                <a class="label label-success" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 2);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Active</a> </div>';
                    $html = $html . '<div id="blocked_div' . $admin_users->user->id . '"    style="display:inline-block" >
                                                <a class="label label-danger" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Blocked</a> </div>';
                } else {//
                    $html = '<div  id="active_div' . $admin_users->user->id . '"   style="display:inline-block" >
                                                <a class="label label-success" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 2);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Active</a> </div>';
                    $html = $html . '<div id="blocked_div' . $admin_users->user->id . '"  style="display:none;"  >
                                                <a class="label label-danger" title="Click to Change Status" onClick="javascript:changeStatus(' . $admin_users->user->id . ', 1);" href="javascript:void(0);" id="status_' . $admin_users->user->id . '">Blocked</a> </div>';
                }
                return $html;
            })
            ->addColumn('created_at', function($regsiter_user) {
                return $regsiter_user->user->created_at;
            })
            ->make(true);
    }
// this is a function to update the patient
    public function updateRegisteredPatient(Request $request, $user_id) {
        $arr_user_data = User::find($user_id);

        if ($arr_user_data) {
            if ($request->method() == "GET") {


                $all_roles = Role::where('level', "<=", 1)->where('slug', '<>', 'superadmin')->get();
                return view("admin::edit-registered-patient", array('user_info' => $arr_user_data, 'roles' => $all_roles));
            } elseif ($request->method() == "POST") {

                $data = $request->all();

                $validate_response = Validator::make($data, array(
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'contact' => 'numeric',
                    )
                );
                if ($validate_response->fails()) {
                    return redirect('admin/update-registered-patient/' . $arr_user_data->id)
                        ->withErrors($validate_response)
                        ->withInput();
                } else {/** user information goes here *** */

                    if (isset($data["contact"])) {
                        $arr_user_data->userInformation->user_mobile = $data["contact"];
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

                    if (isset($data["dob"])) {
                        $arr_user_data->userInformation->user_birth_date = $data["dob"];
                    }

                    if($request->hasFile('photo'))
                    {

                        $uploaded_file = $request->file('photo');


                        $extension = $uploaded_file->getClientOriginalExtension();

                        $new_file_name = time().".".$extension;

                        Storage::put('public/patient/'.$new_file_name,file_get_contents($uploaded_file->getRealPath()));

                        $arr_user_data->userInformation->profile_picture = $new_file_name;
                    }

                    $arr_user_data->userInformation->save();
                    $success_msg = "User profile has been updated successfully!";
                    return redirect("admin/update-registered-patient/" . $arr_user_data->id)->with("profile-updated", $success_msg);
                }
            }
        } else {
            return redirect("admin/manage-users");
        }
    }
    protected function updateRegisteredPatientEmailInfo(Request $data, $user_id) {
        $data_values = $data->all();
        if (Auth::user()) {
            $arr_user_data = User::find($user_id);
            $validate_response = Validator::make($data_values, array(
                'email' => 'required|email|max:500|unique:users',
                'confirm_email' => 'required|email|same:email',
            ));

            if ($validate_response->fails()) {
                return redirect('admin/update-registered-user/' . $user_id)
                    ->withErrors($validate_response)
                    ->withInput();
            } else {
                //updating user email
                $arr_user_data->email = $data->email;
                $arr_user_data->save();

                //updating user status to inactive
                $arr_user_data->userInformation->save();
                //sending email with verification link
                //sending an email to the user on successfull registration.
                $site_email=GlobalValues::get('site-email');
                $site_title=GlobalValues::get('site-title');
                $arr_keyword_values = array();
                $activation_code = $this->generateReferenceNumber();
                //Assign values to all macros
                $arr_keyword_values['FIRST_NAME'] = $arr_user_data->userInformation->first_name;
                $arr_keyword_values['LAST_NAME'] = $arr_user_data->userInformation->last_name;
                $arr_keyword_values['VERIFICATION_LINK'] = url('verify-user-email/' . $activation_code);
                $arr_keyword_values['SITE_TITLE'] =  $site_title;
                // updating activation code                 
                $arr_user_data->userInformation->activation_code = $activation_code;
                $arr_user_data->userInformation->save();
                $email_template = EmailTemplate::where("template_key",'email-change')->first();

                Mail::send('EmailTemplate::email-change', $arr_keyword_values, function ($message) use ($arr_user_data,$site_email,$site_title,$email_template) {

                    $message->to($arr_user_data->email)->subject($email_template->subject)->from($site_email,$site_title);
                });

                $succes_msg = "User email has been updated successfully!";
                return redirect("admin/update-registered-patient/" . $user_id)->with("profile-updated", $succes_msg);
            }
        } else {
            $errorMsg = "Error! Something is wrong going on.";
            Auth::logout();
            return redirect("admin/login")->with("issue-profile", $errorMsg);
        }
    }
    protected function updateRegisteredPatientPasswordInfo(Request $data, $user_id) {
        $data_values = $data->all();
        if (Auth::user()) {
            $arr_user_data = User::find($user_id);
            $validate_response = Validator::make($data_values, array(

                'new_password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:new_password',
            ));

            if ($validate_response->fails()) {
                return redirect("admin/update-registered-user/" . $user_id)
                    ->withErrors($validate_response)
                    ->withInput();
            } else {
                //updating user Password
                $arr_user_data->password = $data->new_password;
                $arr_user_data->save();
                $succes_msg = "User password has been updated successfully!";
                return redirect("admin/update-registered-patient/" . $user_id)->with("profile-updated", $succes_msg);

            }
        } else {
            $errorMsg = "Error! Something wrong is going on.";
            Auth::logout();
            return redirect("login")->with("issue-profile", $errorMsg);
        }
    }
    public function deleteRegisteredPatient($user_id) {
        $user = User::find($user_id);
        if ($user) {
            $user->email = $user->id.'example@example.com';
            $user->is_delete = 1;
            $user->save();

            $user->userInformation->google_id = '';
            $user->userInformation->facebook_id = '';
            $user->userInformation->is_delete = 1;
            $user->userInformation->save();

            return redirect('admin/manage-patient')->with('delete-user-status', 'User has been deleted successfully!');
        } else {
            return redirect("admin/manage-patient");
        }
    }
    public function deleteSelectedRegisteredPatient($user_id) {
        $user = User::find($user_id);

        if ($user) {
            $user->email = $user->id.'example@example.com';
            $user->is_delete = 1;
            $user->save();

            $user->userInformation->google_id = '';
            $user->userInformation->facebook_id = '';
            $user->userInformation->is_delete = 1;
            $user->userInformation->save();

            echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
        } else {
            echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
        }
    }
}
