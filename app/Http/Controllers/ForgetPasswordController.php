<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PiplModules\ResetsPasswords;
use Illuminate\Http\Request;
use App\PiplModules\webservice\Models\ForgetPassword;

class ForgetPasswordController extends Controller
{

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
     
    use ResetsPasswords;
    
    protected $subject = "";
         
    public function __construct()
    {
        $this->middleware('guest');
           $this->subject = "My Forgot password subject";

    }

    public function forgetPasswordReset(Request $request)
    {
        $str = $request->get("md5");
        if ($str != "") {
            $forgetPassword = ForgetPassword::where("md5", $str)->get()->first();
            if ($forgetPassword && $forgetPassword->created_at + 72 * 60 * 60 < time()) {
                return view("auth.passwords.forget_reset", compact("forgetPassword"));
            }
        }
        return view("auth.passwords.invalid_reset");
    }
    
    
}
