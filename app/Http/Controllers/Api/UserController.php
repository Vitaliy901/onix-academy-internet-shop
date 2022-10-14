<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\IndexRequest;
use App\Http\Requests\Api\Users\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\HttpResponse;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $user = User::email($request->keywords)
            ->interval($request->startDate, $request->endDate)
            ->trashed($request->getTrashed)
            ->sortByDesc($request->sortBy)
            ->paginate($request->per_page ?? 5);

        return UserResource::collection($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user, UserService $userService)
    {
        return new UserResource($userService->itsMe($request, $user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user, UserService $userService)
    {
        $user = $userService->itsMe($request, $user);

        $userService->update($user, $request->safe());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user, UserService $userService)
    {

        $user = $userService->itsMe($request, $user);

        $userService->optionDelete($request, $user);

        return $this->success(null, 200, 'User deleted successfully!');
    }
}
