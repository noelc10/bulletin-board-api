<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Enums\ErrorCodes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login']]);
    }

    /**
     * Authenticate user using username and password
     */
    public function login(UserLoginRequest $request)
    {
        /** @var User */
        $user = User::hasUsername($request->username)
            ->first();

        if (is_null($user)) {
            return $this->respondWithError(
                ErrorCodes::InvalidCredentials->value,
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($request->has('password') && !checkPassword($request->input('password'), $user->password)) {
            return $this->respondWithError(
                ErrorCodes::InvalidCredentials->value,
                Response::HTTP_UNAUTHORIZED
            );
        }

        /** @var NewAccessToken */
        $newAccessToken = $user->createToken($request->header('user-agent'));

        return $this->respondWithToken(
            $newAccessToken->plainTextToken,
            $this->user($user)
        );
    }

    /**
     * Get the authenticated User.
     */
    public function user(User $user = null): UserResource
    {
        if (is_null($user)) {
            /** @var \App\Models\User */
            $user = auth()->user();
        }

        return new UserResource($user);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): JsonResponse
    {
        /** @var \App\Models\User */
        $user = auth()->user();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ], Response::HTTP_OK);
    }
}
