<?php

namespace App\Http\Controllers;

use App\SMS;
use App\User;
use App\Verification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerificationV2Controller extends VerificationController
{
    protected $action = 'v2';

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|numeric'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->email),
            'phone' => '+62'.$request->phone
        ]);

        $code = mt_rand(1000, 9999);

        $content = "Code verifikasi Anda adalah: ".$code;
        $message = (new SMS($user))->content($content)->send();

        if(!!$message) {
            $verification = Verification::create([
                'user_id' => $user->id,
                'code' => $code,
                'ref_id' => $message->sid,
                'expired_at' => Carbon::now()->addHour()
            ]);

            return redirect('/v2/verify')->with('status', "Silahkan verifikasi akun Anda.");
        }

        return back()->with('error', "Oops, Ada sesuatu yang salah");
    }

    public function verifyForm() {
        return view('verify');
    }

    public function verify(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|numeric|digits:4'
        ]);

        $verification = Verification::where('code', $request->code)->first();

        if(count($verification) > 0) {
            $verification->user->verify();
            $verification->delete();

            return redirect('ty');
        }

        return back()->with('error', "Kode yang Anda masukan salah.");
    }
}
