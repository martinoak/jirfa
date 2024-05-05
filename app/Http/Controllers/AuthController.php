<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$validator->fails() && Auth::attempt($request->only('email', 'password'))) {
            return to_route('admin.dashboard');
        } else {
            return back()->with('error', 'Neplatný email nebo heslo')->withInput();
        }
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return to_route('homepage');
    }
}
