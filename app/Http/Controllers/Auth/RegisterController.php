<?php

namespace App\Http\Controllers\Auth;

use App\Actions\RegisterUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
    /**
     * Create a new RegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Undocumented function
     *
     * @param  RegisterUserRequest $request
     * @return UserResource
     */
    public function __invoke(RegisterUserRequest $request)
    {
        $registerUser = new RegisterUser();

        return DB::transaction(function () use ($request, $registerUser) {
            $user = $registerUser->execute($request);

            /** @var NewAccessToken */
            $newAccessToken = $user->createToken($request->header('user-agent', config('app.name')));

            return $this->respondWithToken(
                $newAccessToken->plainTextToken,
                new UserResource($user)
            );
        });
    }
}
