<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function postEmail(Request $request)
    {
        return $this->sendPasswordOnEmail($request);
    }

    private function sendPasswordOnEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        /** @var User $user */
        if (!$user = User::where('email', $request->email)->first()) {
            return ResponseHelper::makeResponse([
                'errorMessage' => "Юзера с email {$request->get('email')} нет",
            ]);
        }

        try {
            $newPassword = str_random(7);
            $emailText = "Ваш аккаунт {$user->username}, ваш новый пароль {$newPassword}";
            Mail::raw($emailText, function ($message) use ($user) {
                $message->from('ihunters@srv-ft-dev.ru', 'iHunters');

                $message->to($user->email);

                $message->subject('Сброс пароля');
            });


            dd($user->update([
                'password' => bcrypt($newPassword),
            ]));


            return $this->getSendResetLinkEmailSuccessResponse(null, $request);
        } catch (\Exception $e) {
            return $this->getSendResetLinkEmailFailureResponse(null, $request, $e->getMessage());
        }
    }
}
