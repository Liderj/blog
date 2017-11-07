<?php

namespace App\Http\Controllers\Admin;

use App\Admin\User;
use Illuminate\Support\Facades\Input;
use Mews\Captcha\Facades\Captcha;

class LoginController extends CommonController
{
    public function login()
    {
        if ($input =Input::all()){
            if(!Captcha::check(Input::get('code'))){
                return back()->with('msg','验证码错误');
            }
            $user=User::first();
            if($user->username!=$input['username'] || decrypt($user->userpass)!=$input['userpass']){
                return back()->with('msg','用户名或者密码错误');
            }
            session(['user'=>$user]);

//            dd(session('user'));
            return 'ok';
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
