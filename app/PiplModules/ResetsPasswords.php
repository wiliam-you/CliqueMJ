<?php

namespace App\PiplModules;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\PiplModules\EmailTemplate\Models\EmailTemplate;
use App\PiplModules\webservice\Models\ForgetPassword;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Mail;
use GlobalValues;
trait ResetsPasswords
{
    use RedirectsUsers;
	
	protected $email_template_key = "request-reset-password";
	protected $email_template_view = "emailtemplate::request-reset-password";

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        return $this->showLinkRequestForm();
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
		
        if (property_exists($this, 'linkRequestView')) {
            return view($this->linkRequestView);
        }

        if (view()->exists('auth.passwords.email')) {

            return view('auth.passwords.email');
        }

        return view('auth.password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $broker = $this->getBroker();
		
		$user = Password::broker($broker)->getUser($request->only('email'));
		
		if (is_null($user))
		{
            return redirect('/password/reset')->with('login-error','This email is not registered yet!');
                }
		else
		{
//			$token =Password::broker($broker)->createToken($user);
//
//			// send email using email templates
//
//			$email_template = EmailTemplate::where("template_key",$this->email_template_key)->first();
//			$site_email=GlobalValues::get('site-email');
//                        $site_title=GlobalValues::get('site-title');
//			$arr_keyword_values = array();
//			$arr_keyword_values['USER_NAME'] = $user->userinformation->first_name;
//
//                        if($user->userinformation->user_type=='1')
//                        {
//                            $arr_keyword_values['RESET_LINK'] = url('admin/password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset());
//                        }else{
//                            $arr_keyword_values['RESET_LINK'] = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset());
//                        }
//			 $arr_keyword_values['SITE_TITLE'] =  $site_title;
//
//			Mail::send($this->email_template_view,$arr_keyword_values, function ($message) use ($user,$email_template,$site_email,$site_title)  {
//
//				$message->to( $user->email, $user->name )->subject($email_template -> subject)->from($site_email,$site_title);
//
//			});
//
//			 return $this->getSendResetLinkEmailSuccessResponse(PasswordBrokerContract::RESET_LINK_SENT);


            // send email using email templates



            $random_password = str_random(8);

            $site_email=GlobalValues::get('site-email');
            $site_title=GlobalValues::get('site-title');
            $arr_keyword_values = array();
            $arr_keyword_values['USER_NAME'] = $user->userinformation->first_name;

            if($user->userinformation->user_type=='1')
            {
                $arr_keyword_values['RESET_LINK'] = url('admin/password/reset', $random_password).'?email='.urlencode($user->getEmailForPasswordReset());
                $email_template = EmailTemplate::where("template_key",$this->email_template_key)->first();
            }else{
                $arr_keyword_values['FIRST_NAME'] = $user->userinformation->first_name;
                $arr_keyword_values['LAST_NAME'] = $user->userinformation->last_name;
                $arr_keyword_values['EMAIL'] = $user->email;
                $arr_keyword_values['PASSWORD'] = $random_password;
                $forgetPassword = new ForgetPassword;
                $forgetPassword->user_id = $user->id;
                //$forgetPassword->created_at = time();
                $forgetPassword->md5 = str_random(32);
                $forgetPassword->save();

                $arr_keyword_values['RESET_LINK'] = url("forget_password/reset?md5=" . $forgetPassword->md5);
                $email_template = EmailTemplate::where("template_key",$this->email_template_key)->first();
            }
            $arr_keyword_values['SITE_TITLE'] =  $site_title;

            Mail::send('EmailTemplate::request-reset-password',$arr_keyword_values, function ($message) use ($user,$email_template,$site_email,$site_title)  {

                $message->to( $user->email, $user->name )->subject($email_template -> subject)->from($site_email,$site_title);

            });

            if($user->userinformation->user_type=='1')
            {
                return $this->getSendResetLinkEmailSuccessResponse(PasswordBrokerContract::RESET_LINK_SENT);
            }
            else
            {
                return redirect('/login')->with('success','Reset Link is sent to your email');
            }


			
		}
		
        return $this->getSendResetLinkEmailFailureResponse(PasswordBrokerContract::INVALID_USER);
     
    }

    /**
     * Get the Closure which is used to build the password reset email message.
     *
     * @return \Closure
     */
    protected function resetEmailBuilder()
    {
        return function (Message $message) {
            $message->subject($this->getEmailSubject());
        };
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        return property_exists($this, 'subject') ? $this->subject : 'Your Password Reset Link';
    }

    /**
     * Get the response for after the reset link has been successfully sent.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailSuccessResponse($response)
    {
        return redirect()->back()->with('status', trans($response));
    }

    /**
     * Get the response for after the reset link could not be sent.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailFailureResponse($response)
    {
        return redirect()->back()->withErrors(['email' => trans($response)]);
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset(Request $request, $token = null)
    {
        return $this->showResetForm($request, $token);
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        if (is_null($token)) {
            return $this->getEmail();
        }

        $email = $request->input('email');

        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with(compact('token', 'email'));
        }

        if (view()->exists('auth.passwords.reset')) {
            return view('auth.passwords.reset')->with(compact('token', 'email'));
        }

        return view('auth.reset')->with(compact('token', 'email'));
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $this->validate(
            $request,
            $this->getResetValidationRules(),
            $this->getResetValidationMessages(),
            $this->getResetValidationCustomAttributes()
        );

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->getResetSuccessResponse($response);

            default:
                return $this->getResetFailureResponse($request, $response);
        }
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function getResetValidationRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Get the password reset validation messages.
     *
     * @return array
     */
    protected function getResetValidationMessages()
    {
        return [];
    }

    /**
     * Get the password reset validation custom attributes.
     *
     * @return array
     */
    protected function getResetValidationCustomAttributes()
    {
        return [];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => $password,
            'remember_token' => Str::random(60),
        ])->save();

        Auth::guard($this->getGuard())->login($user);
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse($response)
    {
        if(Auth::user())
        {
            $user_type=Auth::user()->userInformation->user_type;
            if($user_type=='1')
            {
                Auth::logout();
               return redirect('/admin/login')->with('status', trans($response));
               
            }else{
                Auth::logout();
               return redirect('/login')->with('status', trans($response));
           }
        }
    }

    /**
     * Get the response for after a failing password reset.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetFailureResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return string|null
     */
    public function getBroker()
    {
        return property_exists($this, 'broker') ? $this->broker : null;
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return string|null
     */
    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : null;
    }
}
