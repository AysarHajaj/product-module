<?php

namespace App\Formatters;

class AuthFormatter extends Formatter
{
    public function formatUser($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];
    }
}
