<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use Mews\Captcha\Facades\Captcha;

class LoginController extends CommonController
{
    public function login()
    {
        if ($input =Input::all()){
            if(Captcha::check(Input::get('code'))){
                return 'ok';
            }else{
                return back()->with('msg','验证码错误');
            }
        }else{
            return view('admin.login');
        }
    }


    public function code()
    {
        return Captcha::create('default');
    }

    public function crypt()
    {
        $str ='123456';
        return encrypt($str);
    }
}
