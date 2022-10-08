<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\PasswordRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\HttpResponse;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use ProtoneMedia\LaravelVerifyNewEmail\Http\InvalidVerificationLinkException;

class EmailVerifyController extends Controller
{
    use HttpResponse;

    public function send(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->success($user, 200, 'Email is verified!');
        }
        $request->user()->sendEmailVerificationNotification();

        return $this->error(null, 200, 'Verification link sent!');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $user = $request->user();

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
            return $this->success($user, 200, 'Email has been verified!');
        }
        return $this->success($user, 200, 'Email already verified!');
    }

    /**
     * Mark the user's new email address as verified.
     *
     * @param  string $token
     *
     * @throws \ProtoneMedia\LaravelVerifyNewEmail\Http\InvalidVerificationLinkException
     */
    public function verifyNewEmail(string $token)
    {
        $user = app(config('verify-new-email.model'))->whereToken($token)->firstOr(['*'], function () {
            throw new InvalidVerificationLinkException(
                __('The verification link is not valid anymore.')
            );
        })->tap(function ($pendingUserEmail) {
            $pendingUserEmail->activate();
        })->user;

        return $this->success(new UserResource($user), 200, 'Email has been changed!');
    }

    public function notice()
    {
        return $this->error(null, 200, 'Email is not verified! Please confirm your email.');
    }

    public function passwordResetEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? ['status' => __($status)]
            : ['email' => __($status)];
    }

    public function passwordUpdate(PasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? ['status' => __($status)]
            : ['email' => __($status)];
    }
}
