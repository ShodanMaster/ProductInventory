<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function loginForm(){
        return view('login.loginForm');
    }

    public function logingIn(){

        $input = request()->only(['email','password']);

        if (auth()->attempt($input)) {

            return redirect()->route('inventory.dashboard')->with('success', 'Logged in Successfully'); // Replace 'dashboard' with your desired route
        } else {
            return redirect()->back()->with("warning", "Wrong Credentials");
        }
    }

    public function userRegister(Request $request){

        try{
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']), // Hash the password
            ]);

            return redirect()->back()->with('success','User Registered Successfully pleace Login');
        }catch(Exception $e){
            return redirect()->back()->with('error','Could not register User: '.$e->getMessage());
        }
    }

    public function logoutUser(){
        auth()->logout();
        return redirect()->route('login')->with('success','Logged Out');
    }
}
