<?php

namespace App\Http\Controllers\Admin;

use App\Admin\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Validator;

class IndexController extends CommonController
{
    public function index()
    {
        return view('admin.index');
    }

    public function info()
    {
        return view('admin.info');
    }

    public function pass()
    {

        if ($input = Input::all()) {
            $rules = [
                'password' => 'required|between:6,20|confirmed',
            ];
            $message = [
                'password.required' => '新密码不能为空',
                'password.between' => '新密码必须在6-20位之间',
                'password.confirmed' => '确认密码与新密码不一致',
            ];
            $validator = Validator::make($input, $rules, $message);
            if ($validator->passes()) {
                $user = User::first();
                $userpass = Crypt::decrypt($user->userpass);
                if($input['password_o'] == $userpass){
                    $user->userpass = Crypt::encrypt($input['password']);
                    $user->update();
                    return redirect('admin/info');
                }else{
                    return back()->with('errors','原密码错误');
                }
            } else {
                return back()->withErrors($validator);
            }
        } else {
            return view('admin.pass');
        }
    }
}
