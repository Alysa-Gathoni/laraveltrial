<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthManager extends Controller
{   
    function login(){
        return view('login');
    }

    function registration(){
        return view('registration');
    }
    function loginPost(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            return redirect()->intended(route('home'));
    }
    return redirect(route('login'))->with("error", "Login details are not valid");
}
    function registrationPost(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

        if(!$user){
            return redirect(route('registration'))->with("error" , "Registration failed, try again");
        }
        return redirect(route('login'))->with("success" , "Registration successful, login now");

    }

    function logout(){
        Session::flush();
        Auth::logout();
        return redirect(route(login));
    }
}
