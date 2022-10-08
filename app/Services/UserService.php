<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ValidatedInput;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserService
{
    public function create(ValidatedInput $credentials): User
    {
        $password = $credentials->only('password');

        $data = $credentials->merge(['password' => Hash::make($password['password'])])->all();

        return User::create($data);
    }

    public function itsMe(Request $request, User $user)
    {
        if ($request->is('api/users/me')) {
            return $request->user();
        }
        if ($request->user()->isAdmin()) {
            return $user;
        }

        throw new AccessDeniedHttpException('This action is unauthorized.');
    }


    public function optionDelete(Request $request, User $user)
    {
        if ($request->is('api/users/me') && !$request->user()->isAdmin()) {
            return $user->delete();
        }
        if ($request->user()->isAdmin()) {
            return $user->forceDelete();
        }

        throw new AccessDeniedHttpException('This action is unauthorized.');
    }

    public function update(User $user, ValidatedInput $credentials)
    {
        if ($credentials->only('password')) {
            $user->password = Hash::make($credentials['password']);
        }
        if ($credentials['email'] != $user->email) {
            $user->newEmail($credentials['email']);
        }

        $user->update($credentials->except('password', 'email'));
    }
}
