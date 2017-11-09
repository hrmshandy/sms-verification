<?php

namespace App\Http\Controllers;

use App\SMS;
use App\User;
use App\Verification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerificationV1Controller extends VerificationController
{
    protected $action = 'v1';

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

        $code = str_random(6);

        $content = "Silahkan verifikasi akun Anda dengan klik link berikut ".url('v1/verify/'.$code);
        $message = (new SMS($user))->content($content)->send();

        if(!!$message) {
            $verification = Verification::create([
                'user_id' => $user->id,
                'code' => $code,
                'ref_id' => $message->sid,
                'expired_at' => Carbon::now()->addHour()
            ]);

            return back()->with('status', "Silahkan verifikasi akun Anda.");
        }

        return back()->with('error', "Oops, Ada sesuatu yang salah");
    }

    public function verify($code)
    {
        $verification = Verification::where('code', $code)->first();

        if(count($verification) > 0) {
            $verification->user->verify();
            $verification->delete();

            return redirect('ty');
        }

        abort("404");
    }
}
