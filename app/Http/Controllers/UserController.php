<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function save_login(Request $req)
    {
        $data = $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);

        if (Auth::attempt($data)) {

            return response()->json([
                'status' => true,
                'redirect' => route(''),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ]);
        }
    }
}
