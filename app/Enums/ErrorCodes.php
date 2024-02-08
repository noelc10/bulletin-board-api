<?php

namespace App\Enums;

use Symfony\Component\HttpFoundation\Response;

enum ErrorCodes: string implements HasDescription
{
    /**
     * Error code for invalid account Credentioals
     */
    case InvalidCredentials = 'INVALID_CREDENTIALS';

    /**
     * Error code for using old password when updating password.
     */
    case UsingOldPassword = 'USING_OLD_PASSWORD';

    /**
     * Error code for invalid username
     */
    case InvalidUsername = 'INVALID_USERNAME';

    /**
     * Error code for invalid password
     */
    case InvalidPassword = 'INVALID_PASSWORD';

    /**
     * Error code for invalid password
     */
    case UsernameNotFound = 'USERNAME_NOT_FOUND';

    /**
     * Error code for invalid email
     */
    case EmailNotFound = 'EMAIL_NOT_FOUND';

    /**
     * Error code for invalid token
     */
    case TokenNotFound = 'TOKEN_NOT_FOUND';

    /**
     * Error code for unathorized access
     */
    case Unauthorized = 'UNAUTHORIZED';

    /**
     * Error code description
     */
    public function description(): string
    {
        return match ($this) {
            self::InvalidCredentials => 'We couldn\'t find any records that matches your credentials.',
            self::InvalidUsername => 'We couldn\'t find any records that matches your username.',
            self::InvalidPassword => 'Your password did not match on our records.',
            self::UsernameNotFound => 'We couldn\'t find any records that matches your username.',
            self::EmailNotFound => 'We couldn\'t find any records that matches your email.',
            self::TokenNotFound => 'Token not found',
            self::UsingOldPassword => 'You are using an old password',
            self::Unauthorized => 'Unauthorized access'
        };
    }

    public function statusCode(): int
    {
        return match ($this) {
            default => Response::HTTP_BAD_REQUEST,
        };
    }
}
