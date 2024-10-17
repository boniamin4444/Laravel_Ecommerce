<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Redirect;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /*public function showLogin()
    {
    	return view('auth.login');
    }*/

    public function login(Request $request)
    {
    	$credentials = $request->validate([

    		'email'=>'required|email',
    		'password'=>'required',
    	]);

    	if(Auth::attempt($credentials))
    	{
    		if(Auth::user()->email_verified_at === null)
    		{
    			Auth::logout();
    			return Redirect::to('/')->with('message','Please verify your email before log in');
    		}

    		$request->session()->regenerate();
    		//return redirect()->intended('products');
            return Redirect::to('/');
    	}

    	return Redirect::to('/')->with('message','Invalid Credentials');

    	/*if(Auth::attempt($request->only('email','password')))
    	{
    		return redirect()->intended('products');
    	}*/

    	return redirect()->back()->with('login_errors','Invalid Credentials');
    }

    public function register(Request $request)
    {
    	//Validation rules

    	$validator = Validator::make($request->all(),[

    		'name'=>'required|string|regex:/^[A-Za-z]+$/|max:100',
    		'email'=>'required|email|max:255|unique:users,email',
    		'password'=>['required','confirmed',Rules\Password::defaults()],
    	]);

    	if($validator->fails())
    	{
    	return redirect()->back()->withErrors($validator,'register')->withInput();
    	}

    	// Save user data in User table

    	$user = User::create([

    		'name'=>$request->name,
    		'email'=>$request->email,
    		'password'=>Hash::make($request->password),
    	]);

    	event(new Registered($user));

    	//Auth::login($user);
    	return Redirect::to('/')->with('message','Registration Successfull. Please verify your email.');
    }

    //Email Verify

    public function verifyEmail(Request $request, $id, $hash)
    {
    	$user = User::findOrFail($id);

    	if(!hash_equals((string)$hash, sha1($user->getEmailForVerification())))
    	{
    		return Redirect::to('/')->with('message','Invalid Verification Link');
    	}

    	if($user->hasVerifiedEmail())
    	{
    		return Redirect::to('/')->with('message','Email already verified. You can just log in');
    	}

    	if($user->markEmailAsVerified())
    	{
    		event(new Verified($user));    		
    	}
    	return Redirect::to('/')->with('message','Email Verified Successfully. Now you can log in');
    }

    public function logout()
    {
    	Auth::logout();
    	return redirect()->route('login')->with('success','Logged out successfully');
    }

}
?>
