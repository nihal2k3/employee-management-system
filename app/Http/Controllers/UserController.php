<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register_view()
    {
        return view('user.register_page');
    }

    public function login_view()
    {
        return view('user.login_page');
    }

    public function login(Request $req)
    {
        $data = $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);

        if (Auth::attempt($data)) {

            return response()->json([
                'status' => true,
                'redirect' => route('addEmployee'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ]);
        }
    }

    public function saveregistration(Request $req)
    {
        $data = $req->validate([
            'name' => 'required|max:20',
            'email' => 'required|email',
            'password' => 'required|min:3|confirmed'
        ]);

        $exist = User::where('email', $data['email'])->first();
        if ($exist) {
            return response()->json([
                'status' => false,
                'message' => 'Email Already Exist'
            ]);
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'redirect' => route('addEmployee')
        ]);
    }
}
