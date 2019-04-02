<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }
    
    public function login(Request $request){

        $this->validateLogin($request);

        if(Auth::attempt(['username' => $request->username, 'password' => $request->password, 'condicion' => 1])){
            return redirect()->route('dashboard');
        }

//        return view('auth.login');
        return back()
            ->withErrors(['username' => trans('auth.failed')])
            ->withInput(request(['username']));
    }

    public function validateLogin(Request $request){
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
