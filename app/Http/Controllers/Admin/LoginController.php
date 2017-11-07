<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mews\Captcha\Facades\Captcha;

class LoginController extends CommonController
{
    public function login()
    {
        return view('admin.login');
    }


    public function code()
    {
        return Captcha::create('default');
    }
}
