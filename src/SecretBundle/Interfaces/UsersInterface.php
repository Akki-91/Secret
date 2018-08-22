<?php

namespace SecretBundle\Interfaces;

interface UsersInterface
{
    public static function create(string $userType);
}