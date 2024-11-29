<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\fcades\Hash;
use Symfony\Contracts\Service\Attribute\Required;

class LoginRegistercontroller extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

public function stare(Request $request)
{
    $request->validate([
        'name'=> 'required|string|nax:258',
        'email'=> 'required|string|nax:258|unique:users',
        'password'=> 'required|min:8|confirmed'
    ]);
    user::create([
        'name' => $request->name,
        'email' => $request->email,
       'password' => Hash::make($request->password), // Tambahkan koma di sin
        'usertype' => 'admin'
    ]);
    $credentials = $request->only('email','password');
    Auth::attempt($credentials);
    $request->session()->regenerate();
    
    if($request->user()->usetype == 'admin') {
        return redirect('admin/dashboard')->withSuccess('You have successfully registered & logged in!');
    }

     return redirect()->intended(route('dashboard'));
}
    
     public function login()
     {
       return view('auth.login');
     }

     public function authenticate(Request $request)
     {
        $credentials = $request->validate([
            'email'=> 'required|email',
            'password'=> 'requirred'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if ($request->user()->usertype == 'admin'){
                return redirect('admin/dashboard')->withSuccess('You have successfully logged in!');
            }
        }

        return back()->withErrors([
            'email'=> 'You provided credentials do not match in our records.',
        ])->onlyInput('email');
     }

     public function logout(Request $request)
     {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
        ->withSuccess('You have loged out successfully!');;
     }
}


