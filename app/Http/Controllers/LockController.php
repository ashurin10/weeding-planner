<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LockController extends Controller
{
    public function index()
    {
        return view('lock.index');
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'pin' => ['required', 'string'],
        ]);

        if (Hash::check($request->pin, Auth::user()->app_pin)) {
            session(['is_unlocked' => true]);
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['pin' => 'Incorrect PIN']);
    }
}
