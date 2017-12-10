<?php

namespace App\Http\Controllers\Admin;

use App\Admin\User;
use Illuminate\Support\Facades\Input;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Crypt;

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
            return redirect('admin/index');
        }else{
            return view('admin.login');
        }
    }


    public function code()
    {
        return Captcha::create('default');
    }

    public function logout()
    {
        session(['user'=>null]);
        return redirect('admin/login');
   }
}
