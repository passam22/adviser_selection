<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::user()) 
        {            
            return redirect("/dashboard");
        }
        return view('login_form');
    }

    public function check_login(Request $request)
    {
        $credentials = array(
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        );
                 
        if (Auth::attempt($credentials))
        {
            return redirect('/dashboard');
        }
        return redirect('/login')->with('error', 'Wrong Email or Password');
    }

    public function sample($try)
    {
        $val = rand ( 10000 , 99999 );
        echo "Value: $val <br/>";
        echo "Hash: ".Hash::make($val);
    }

    public function dashboard()
    {
        if(Auth::user()->account_type=="ADVISER")
        {
            return redirect("/application_list");
        }
        else
        {
            return redirect("/select_adviser");
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/login');
    }

}
