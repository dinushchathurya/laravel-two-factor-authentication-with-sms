<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\UserCode;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('2fa');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required',
        ]);
  
        $find = UserCode::where('user_id', auth()->user()->id)->where('code', $request->code)->where('updated_at', '>=', now()->subMinutes(2))->first();
        
        if (!is_null($find)) {
            Session::put('user_2fa', auth()->user()->id);
            return redirect()->route('home');
        }
        return back()->with('error', 'You entered wrong code.');
    }

    public function resend()
    {
        auth()->user()->generateCode();
        return back()->with('success', 'We sent you code on your mobile number.');
    }
}
