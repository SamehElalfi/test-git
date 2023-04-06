<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class SocialAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $socialUser->id)->first();

            if ($user) {
                Auth::login($user);
                return redirect('/');
            } else {
                $users = User::all()->where('email', $socialUser->email);
                if (count($users) >= 1){
                    return redirect('login')->with('exist', 'البريد الإلكتروني هذا (' . $socialUser->email . ') مسجل بالفعل');
                }
                $createUser = User::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'phone' => time(),
                    'google_id' => $socialUser->id,
                    'password' => encrypt('123456')
                ]);
                Auth::login($createUser);
                return redirect('/');
            }

        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
}
