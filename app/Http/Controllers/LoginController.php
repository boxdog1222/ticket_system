<?php

namespace App\Http\Controllers;
use Auth;
use App;
use App\User;
use App\Http\Controllers\Controller;
use Request;
use Cookie;
use DB;



class LoginController extends Controller
{
    public function show_page()
    {
        if(Auth::check()) {
            return redirect('index');
        }
        
        return view('login');
    }

    public function login_check() 
    {
        $input = Request::input();
        $json = [];
        if (!Auth::attempt(['name' => $input['name'], 'password' => $input['password'], 'status' => 1])) {
            $json['error'] = '帳號密碼錯誤或該帳號已被停權!';
        }
        return $json;
    }
    
    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}
