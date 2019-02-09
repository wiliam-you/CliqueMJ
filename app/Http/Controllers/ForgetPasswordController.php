<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PiplModules\webservice\Models\ForgetPassword;
use Carbon\Carbon;

class ForgetPasswordController extends Controller
{

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    
    protected $subject = "";
         
    public function __construct()
    {
        $this->middleware('guest');
           $this->subject = "My Forgot password subject";

    }

    public function forgetPasswordReset(Request $request)
    {
        $this->validate($request, [           
            'md5' => 'required|min:32',
        ]);

        $str = $request->get("md5");
        if ($str != "") {
            $expire_time = Carbon::createFromTimestamp(time() - 60 * 60 * 72);
            $forgetPassword = ForgetPassword::where("md5", $str)->where("created_at", ">", $expire_time)->get()->first();
            if ($forgetPassword) {
                return view("auth.passwords.forget_reset", compact("forgetPassword"));
            }
        }
        return view("auth.passwords.invalid_reset");
    }

    public function storeForgetPassword(Request $request)
    {
        $this->validate($request, [           
            'password' => 'required|min:6|confirmed',
            'md5' => 'required|min:32',
        ]);
        $forgetPassword = ForgetPassword::where("md5", $str)->where("created_at", ">", $expire_time)->get()->first();
        if ($forgetPassword) {
            ForgetPassword::where("md5", $request->get('md5'))->delete();
            return view("auth.passwords.reset_success");
        }
        return view("auth.passwords.invalid_reset");
    }
    
    
}
