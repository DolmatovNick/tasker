<?php

namespace Core;

class Auth {

    public static function attemptLogin($login, $password) : bool
    {
        if ($login == 'admin' && $password == '123') {

            static::login($login, $password);

            return true;
        }

        return false;
    }

    private static function login($login, $password)
    {
        $_SESSION['user'] = $login;
    }

    public static function logout()
    {
        unset($_SESSION['user']);
    }

    public static function getUser()
    {
        return $_SESSION['user'];
    }

}