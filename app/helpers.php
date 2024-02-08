<?php

use Illuminate\Support\Facades\Hash;

if (! function_exists('check_password')) {
    /**
     * Check password
     */
    function checkPassword(string $password, string $hashedPassword): bool
    {
        return Hash::check($password, $hashedPassword);
    }
}
