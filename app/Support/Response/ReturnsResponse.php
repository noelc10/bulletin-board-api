<?php

namespace App\Support\Response;

use App\Enums\ErrorCodes;

trait ReturnsResponse
{
    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user, $statusCode = 200)
    {
        return response()->json(['data' => [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null,
            'user' => $user,
        ]], $statusCode)->header('Authorization', $token);
    }

    /**
     * Get the token array structure.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($errorCode, $statusCode = 400, $message = null, $metadata = [])
    {
        $payload = [
            'message' => $message ?: ErrorCodes::from($errorCode)->description(),
            'error_code' => $errorCode,
        ];

        if (filled($metadata)) {
            $payload = array_merge($payload, ['meta' => $metadata]);
        }

        return response()->json($payload, $statusCode);
    }

    /**
     * Return a empty data response.
     *
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithEmptyData($statusCode = 200)
    {
        return response()->json([], $statusCode);
    }
}
