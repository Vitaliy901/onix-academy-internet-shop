<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\CreateRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Traits\HttpResponse;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	use HttpResponse;

	public function create(CreateRequest $request, UserService $userService)
	{
		$user = $userService->create($request->safe());

		return $this->success([
			'user' => $user,
			'api_token' => $user->createToken($request->device_name, ['default'])->plainTextToken,
		]);
	}

	public function login(LoginRequest $request)
	{
		$credentials = $request->safe()->except('device_name');

		if (!Auth::attempt($credentials)) {
			return $this->error('', 401, 'Credentials don`t matche');
		};

		$user = User::where('email', $request->email)->first();

		return $this->success([
			'user' => $user,
			'api_token' => $user->createToken($request->device_name, ['default'])->plainTextToken,
		], 200, 'User logged in successfully!');
	}

	public function logout(Request $request)
	{
		$request->user()->currentAccessToken()->delete();

		return $this->success(null, 200, 'You have successfully been logged out!');
	}
}
